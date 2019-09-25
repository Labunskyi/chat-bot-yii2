<?php
namespace app\hook\models\viber;


use api\response\Message;
use app\hook\models\ClassHelper;
use app\models\Bots;
use app\models\Commands;
use app\models\Customer;
use app\models\Helper;
use app\models\Menu;
use app\hook\models\viber\CallbackQuery;
use yii\helpers\Url;

class BasePhrases
{
    /**
     * @param Message $whData
     * @param Bots $bot
     *
     * @return mixed|null
     */
    public static function checkClickMenu( $whData, $bot ) {
        $menu = Menu::findOne(['base_id' => $bot->base_id, 'name' => trim($whData->message->text)]);

        if($menu){
            if($response = ClassHelper::callClassMethod( $menu->function, $whData, $bot, $menu )) {
                return $response;
            }elseif($menu->text) {
                return RequestHelper::buildArray( $menu->text, RequestHelper::ACTION_MESSAGE );
            }
        }

        return null;
    }

    /**
     * @param Message $whData
     * @param Bots $bot
     *
     * @return mixed|null
     */
    public static function checkCommands( $whData, $bot ) {
        $command = Commands::findOne(['base_id' => $bot->base_id, 'command' => $whData->message->text]);

        if($command){
            return RequestHelper::buildArray( $command->text, RequestHelper::ACTION_MESSAGE );
        }

        return null;
    }

    /**
     * @param $whData
     * @param $bot
     * @return null
     */
    public static function checkCallBackQuery( $whData, $bot ) {
        $callback = explode(Response::CALLBACK_SEPARATOR, $whData->message->text);
        if( method_exists( 'app\hook\models\viber\CallbackQuery', $callback[0] ) ) {
            $customer = Customer::findOne( [ 'platform' => $bot->platform, 'platform_id' => Bots::idEncode($whData->sender->id), 'bot_id' => $bot->id, 'base_id' => $bot->base_id ] );

            $callback_class = new CallbackQuery( $whData, $bot, $customer );
            return $callback_class->{$callback[0]}( $callback );
        }
        return null;
    }

    /**
     * @param  $whData
     * @param  Bots $bot
     * @return array|null
     */
    public static function checkWaitingRecord( $whData, $bot )
    {
        $customer = Customer::findOne( [ 'platform' => $bot->platform, 'platform_id' => Bots::idEncode($whData->sender->id), 'bot_id' => $bot->id, 'base_id' => $bot->base_id ] );

        if(isset($customer) && $customer){
            $cart = Cart::checkCart( $customer );
            switch ( 'rec' ) {
                case $cart->name:
                    if(isset($whData->message->text)){
                        $cart->name = $whData->message->text;
                        $cart->phone = 'rec';
                        $cart->save();

                        return RequestHelper::buildArray( \Yii::$app->params['bot_config']['message_write_phone'], RequestHelper::ACTION_MESSAGE );
                    }
                    exit;
                    break;
                case $cart->phone:
                    if(isset($whData->message->text)){
                        if( filter_var( $whData->message->text, FILTER_VALIDATE_REGEXP, [ "options" => ["regexp" => "/^[+]{1}[0-9]{12}$/"] ] ) ) {
                            $cart->phone = $whData->message->text;
                            $customer->phone = $whData->message->text;
                            $cart->apartment = 'rec';
                            $cart->save();
                            $customer->save();
                            return RequestHelper::buildArray( \Yii::$app->params['bot_config']['message_write_apartment'], RequestHelper::ACTION_MESSAGE );
                        } else {
                            return RequestHelper::buildArray( \Yii::$app->params['bot_config']['message_phone_format'], RequestHelper::ACTION_MESSAGE );
                        }
                    }
                    exit;
                    break;
                case $cart->email:
                    if( isset($whData->message->text) ) {
                        if( filter_var($whData->message->text, FILTER_VALIDATE_EMAIL) ) {
                            $cart->email = $whData->message->text;
                            $cart->save();

                            if( in_array($cart->score, [2, 3, 4]) ) {
                                $cart->addition_info = 'rec';
                                $cart->save();

                                return RequestHelper::buildArray( \Yii::$app->params['bot_config']['message_write_comment'], RequestHelper::ACTION_MESSAGE );
                            } else {
                                return $cart->genApplyCart();
                            }

                        } else {
                            return RequestHelper::buildArray( \Yii::$app->params['bot_config']['message_email_format'], RequestHelper::ACTION_MESSAGE );
                        }
                    }
                    exit;
                    break;
                case $cart->apartment:
                    if( isset($whData->message->text) ) {
                        $cart->apartment = $whData->message->text;
                        $cart->section = 'rec';
                        $cart->save();

                        return RequestHelper::buildArray( \Yii::$app->params['bot_config']['message_write_section'], RequestHelper::ACTION_MESSAGE );
                    }
                    exit;
                    break;
                case $cart->section:
                    if( isset($whData->message->text) ) {
                        $cart->section = $whData->message->text;
                        $cart->score = 'rec';
                        $cart->save();

                        return $cart->genRequestScore();
                    }
                    exit;
                    break;
                case $cart->addition_info:
                    if( isset($whData->message->text) ) {
                        $cart->addition_info = $whData->message->text;
                        $cart->save();
                        return $cart->genApplyCart();
                    }
                    exit;
                    break;
                default:
                    if( empty($cart->name) ) {
                        $cart->name = 'rec';
                        $cart->save();

                        return RequestHelper::buildArray( \Yii::$app->params['bot_config']['message_write_name'], RequestHelper::ACTION_MESSAGE );
                    } else {
                        $cart->score = 'rec';
                        $cart->save();

                        return $cart->genRequestScore();
                    }
                    break;
            }
        }
        return null;
    }
}