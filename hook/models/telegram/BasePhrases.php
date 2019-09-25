<?php

namespace app\hook\models\telegram;

use api\response\Message;
use app\hook\models\ClassHelper;
use app\models\Bots;
use app\models\Commands;
use app\models\Customer;
use app\models\Menu;
use api\base\Request;

class BasePhrases
{
    /**
     * @param Message $whData
     * @param Bots    $bot
     *
     * @return mixed|null
     */
    public static function checkClickMenu( $whData, $bot )
    {
        if( isset($whData->text) ) {
            $menu = Menu::findOne( [ 'base_id' => $bot->base_id, 'name' => $whData->text ] );
            if ( $menu ) {
                if ( $response = ClassHelper::callClassMethod( $menu->function, $whData, $bot, $menu ) ) {
                    return $response;
                } elseif ( $menu->text ) {
                    return RequestHelper::buildArray( $whData->from->id, $menu->text );
                }
            }
        }

        return null;
    }

    /**
     * @param Message $whData
     * @param Bots    $bot
     *
     * @return mixed|null
     */
    public static function checkCommands( $whData, $bot )
    {
        if( isset($whData->text) ) {
            $command = Commands::findOne( [ 'base_id' => $bot->base_id, 'command' => $whData->text ] );

            if ( $command ) {
                $buttons = Response::mainMenuGenerate( $bot->base_id );

                return RequestHelper::buildArray( $whData->from->id, $command->text, json_encode( $buttons ) );
            }
        }

        return null;
    }

    /**
     * @param $whData
     * @param $bot
     * @param null $menu
     * @return array|null
     */
    public static function checkWaitingRecord( $whData, $bot, $menu = null )
    {
        $customer = Customer::findOne( [ 'base_id' => $bot->base_id, 'platform' => $bot->platform, 'platform_id' => $whData->from->id, 'bot_id' => $bot->id ] );

        if( isset($customer) && $customer ) {
            $cart = Cart::checkCart( $customer );
            switch ( 'rec' ) {
                case $cart->name:
                    if( isset($whData->text) ) {
                        $cart->name = $whData->text;
                        $cart->phone = 'rec';
                        $cart->save();
                        return $cart->genRequestPhone($whData->from);
                    }
                    break;
                case $cart->phone:
                    if( isset($whData->contact) ) {
                        $cart->phone = $whData->contact->phone_number;
                        $customer->phone = $whData->contact->phone_number;
                        $cart->apartment = 'rec';
                        $cart->save();
                        $customer->save();
                        return [
                            'method'       => 'sendMessage',
                            'chat_id'      => $whData->from->id,
                            'text'         => \Yii::$app->params['bot_config']['message_write_apartment'],
                            'parse_mode'   => 'HTML',
                            'reply_markup' => json_encode(Response::removeMenu())
                        ];
                    } else {
                        return $cart->genRequestPhone($whData->from);
                    }
                    break;
                case $cart->email:
                    if( isset($whData->text) ) {
                        if( filter_var($whData->text, FILTER_VALIDATE_EMAIL) ) {
                            $cart->email = $whData->text;
                            $cart->save();

                            if( in_array($cart->score, [2, 3, 4]) ) {
                                $cart->addition_info = 'rec';
                                $cart->save();
                                return [
                                    'method'   => 'sendMessage',
                                    'chat_id'  => $whData->from->id,
                                    'text'     => \Yii::$app->params['bot_config']['message_write_comment']
                                ];
                            } else {
                                return $cart->genApplyCart($whData->from);
                            }
                        } else {
                            return [
                                'method'       => 'sendMessage',
                                'chat_id'      => $whData->from->id,
                                'text'         => \Yii::$app->params['bot_config']['message_email_format'],
                                'parse_mode'   => 'HTML'
                            ];
                        }
                    }
                    break;
                case $cart->apartment:
                    if( isset($whData->text) ) {
                        $cart->apartment = $whData->text;
                        $cart->section = 'rec';
                        $cart->save();
                        return [
                            'method'   => 'sendMessage',
                            'chat_id'  => $whData->from->id,
                            'text'     => \Yii::$app->params['bot_config']['message_write_section']
                        ];
                    }
                    break;
                case $cart->section:
                    if( isset($whData->text) ) {
                        $cart->section = $whData->text;
                        $cart->score = 'rec';
                        $cart->save();
                        return $cart->genRequestScore($whData->from);
                    }
                    break;
                case $cart->addition_info:
                    if( isset($whData->text) ) {
                        $cart->addition_info = $whData->text;
                        $cart->save();
                        return $cart->genApplyCart($whData->from);
                    }
                    break;
                default:
                    if( empty($cart->name) ) {
                        $cart->name = 'rec';
                        $cart->save();
                        return [
                            'method'       => 'sendMessage',
                            'chat_id'      => $whData->from->id,
                            'text'         => \Yii::$app->params['bot_config']['message_write_name'],
                            'reply_markup' => json_encode(Response::removeMenu())
                        ];
                    } else {
                        $cart->score = 'rec';
                        $cart->save();
                        return $cart->genRequestScore($whData->from);
                    }
                    break;
            }
        }
        return null;
    }
}