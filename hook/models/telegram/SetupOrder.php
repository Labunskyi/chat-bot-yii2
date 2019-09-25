<?php

namespace app\hook\models\telegram;


use api\base\Request;
use api\keyboard\ReplyKeyboardRemove;
use api\response\Update;
use app\models\Bots;
use app\models\Customer;
use app\models\Helper;
use app\models\Order;

class SetupOrder
{

    /**
     * @param Cart $cart
     * @param Bots $bot
     * @param $whData
     * @param $params
     * @return array|null
     */
    public function score( Cart $cart, Bots $bot, $whData, $params )
    {
        $customer = Customer::findOne( [ 'base_id' => $bot->base_id, 'platform' => $bot->platform, 'platform_id' => $whData->callback_query->from->id, 'bot_id' => $bot->id ] );

        if( isset($customer) && $customer ) {
            $cart->score = $params[2];
            $cart->transfer_form = 'rec';
            $cart->save();

            $request = new Request( $bot->token, [
                'method'     => 'editMessageText',
                'chat_id'    => $whData->callback_query->from->id,
                'message_id' => $whData->callback_query->message->message_id,
                'text'       => \Yii::$app->params['bot_config']['message_write_transfer_form'],
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode( [
                    "inline_keyboard" => [
                        [
                            [
                                'text' => Helper::getTransferForm(1),
                                "callback_data" => 'order_transfer_1'
                            ]
                        ],
                        [
                            [
                                'text' => Helper::getTransferForm(2),
                                "callback_data" => 'order_transfer_2'
                            ]
                        ]
                    ]
                ] ),
            ] );
            return $request->send();
        }

        return null;
    }

    /**
     * @param Cart $cart
     * @param Bots $bot
     * @param $whData
     * @param $params
     * @return array|null
     */
    public function transfer( Cart $cart, Bots $bot, $whData, $params )
    {
        $customer = Customer::findOne( [ 'base_id' => $bot->base_id, 'platform' => $bot->platform, 'platform_id' => $whData->callback_query->from->id, 'bot_id' => $bot->id ] );

        if( isset($customer) && $customer ) {
            $cart->transfer_form = $params[2];
            $cart->save();
            if( $cart->transfer_form == '1' && empty($cart->email) ) {
                $cart->email = 'rec';
                $cart->save();
                $request = new Request( $bot->token, [
                    'method'     => 'editMessageText',
                    'chat_id'    => $whData->callback_query->from->id,
                    'message_id' => $whData->callback_query->message->message_id,
                    'text'       => \Yii::$app->params['bot_config']['message_write_email'],
                    'parse_mode' => 'HTML',
                ] );
                return $request->send();
            } else {
                if( in_array($cart->score, [2, 3, 4]) ) {
                    $cart->addition_info = 'rec';
                    $cart->save();
                    $request = new Request( $bot->token, [
                        'method'     => 'editMessageText',
                        'chat_id'    => $whData->callback_query->from->id,
                        'message_id' => $whData->callback_query->message->message_id,
                        'text'       => \Yii::$app->params['bot_config']['message_write_comment'],
                        'parse_mode' => 'HTML',
                    ] );
                    return $request->send();
                } else {
                    $request = new Request( $bot->token,
                        $cart->genApplyCart($whData->callback_query->from, $whData->callback_query->message->message_id, 'editMessageText')
                    );
                    return $request->send();
                }
            }
        }

        return null;
    }

    /**
     * @param Cart $cart
     * @param Bots $bot
     * @param $whData
     * @param $params
     * @return array
     */
    public static function edit( Cart $cart, Bots $bot, $whData, $params )
    {
        $text = \Yii::$app->params['bot_config']['message_error_to_checkout'];
        if( $params[2] == 'full' ) {
            $cart->name = 'rec';
            $cart->email = null;
            $text = \Yii::$app->params['bot_config']['message_write_name'];
        } elseif( $params[2] == 'apartment' ) {
            $cart->apartment = 'rec';
            $text = \Yii::$app->params['bot_config']['message_write_apartment'];
        }

        $cart->save();
        $request = new Request( $bot->token, [
            'method'       => 'editMessageText',
            'chat_id'      => $whData->callback_query->from->id,
            'message_id'   => $whData->callback_query->message->message_id,
            'text'         => $text,
            'parse_mode'   => 'HTML'
        ] );
        return $request->send();
    }

    /**
     * @param Cart $cart
     * @param Bots $bot
     * @param $whData
     * @param $params
     * @return array|bool
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function success( Cart $cart, Bots $bot, $whData, $params )
    {
        if ( $cart->isFull() ) {

            $order = new Order();
            $order->base_id = $cart->base_id;
            $order->bot_id = $cart->bot_id;
            $order->customer_id = $cart->customer_id;
            $order->name = $cart->name;
            $order->phone = $cart->phone;
            $order->email = $cart->email;
            $order->apartment = $cart->apartment;
            $order->section = $cart->section;
            $order->score = $cart->score;
            $order->addition_info = $cart->addition_info;
            $order->transfer_form = $cart->transfer_form;
            $order->status = Order::STATUS_NEW;
            $order->create_at = time();
            $order->save();

            $cart->delete();

            $text = \Yii::$app->params['bot_config']['message_success_order'];

            \Yii::$app
                ->mailer
                ->compose(
                    [ 'html' => 'newOrder-html', 'text' => 'newOrder-text' ],
                    [ 'order' => $order ]
                )
                ->setFrom( [ 'info@' . $_SERVER['HTTP_HOST'] ] )
                ->setTo( \Yii::$app->params['bot_config']['admin_email'] )
                ->setSubject( \Yii::$app->params['bot_config']['message_email_title'] )
                ->send();

            $request = new Request( $bot->token, [
                'method'       => 'deleteMessage',
                'chat_id'      => $whData->callback_query->from->id,
                'message_id'   => $whData->callback_query->message->message_id,
            ] );
            $request->send();

            $request = new Request( $bot->token, [
                'method'       => 'sendMessage',
                'chat_id'      => $whData->callback_query->from->id,
                'text'         => $text,
                'parse_mode'   => 'HTML',
                'reply_markup' => json_encode(Response::mainMenuGenerate($order->base_id))
            ] );
            return $request->send();

        }
        return false;
    }
}