<?php

namespace app\hook\models\viber;

use app\models\Bots;
use app\models\Order;

class SetupOrder
{
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
            $cart->apartment = null;
            $cart->score = null;
            $cart->transfer_form = null;
            $cart->email = null;
            $text = \Yii::$app->params['bot_config']['message_write_name'];
        } elseif( $params[2] == 'apartment' ) {
            $cart->apartment = 'rec';
            $cart->score = null;
            $cart->transfer_form = null;
            $text = \Yii::$app->params['bot_config']['message_write_apartment'];
        }
        $cart->save();

        return RequestHelper::buildArray( $text, RequestHelper::ACTION_MESSAGE );
    }

    /**
     * @param Cart $cart
     * @param Bots $bot
     * @param $whData
     * @param $params
     * @return array|null
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
            $order->transfer_form = $cart->transfer_form;
            $order->addition_info = $cart->addition_info;
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

            return RequestHelper::buildArray( $text, RequestHelper::ACTION_MESSAGE );
        }

        return null;
    }
}