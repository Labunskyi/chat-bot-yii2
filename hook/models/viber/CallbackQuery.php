<?php

namespace app\hook\models\viber;

use app\models\Bots;
use app\models\CartProduct;
use app\models\Customer;
use app\models\DeliveryMethod;
use app\models\Helper;
use yii\helpers\Url;

/**
 * Class Telegram
 *
 * @package app\hook\controllers
 * @property \api\response\Update $whData
 * @property Bots                 $bot
 * @property array                $answer
 * @property Customer             $customer
 */
class CallbackQuery
{
    public $whData, $bot, $customer;

    /**
     * @param \api\response\Update $whData
     * @param  Bots                $bot
     * @param  Customer            $customer
     */
    public function __construct( $whData, $bot, $customer )
    {
        $this->whData = $whData;
        $this->bot = $bot;
        $this->customer = $customer;
    }

    /**
     * @param $params
     * @return array|null
     */
    public function score( $params )
    {
        if( $this->customer ) {
            $cart = Cart::checkCart($this->customer);
            $cart->score = $params[1];
            $cart->transfer_form = 'rec';
            $cart->save();

            $buttons[] =
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns(6)
                    ->setText('<b>' . Helper::getTransferForm(1) . '</b>')
                    ->setActionType('reply')
                    ->setActionBody( 'transfer_1' );
            $buttons[] =
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns(6)
                    ->setText('<b>' . Helper::getTransferForm(2) . '</b>')
                    ->setActionType('reply')
                    ->setActionBody( 'transfer_2' );

            return RequestHelper::buildArray( \Yii::$app->params['bot_config']['message_write_transfer_form'], RequestHelper::ACTION_MESSAGE, $buttons );
        }

        return null;
    }

    /**
     * @param $params
     * @return array|null
     */
    public function transfer( $params )
    {
        if( $this->customer ) {
            $cart = Cart::checkCart($this->customer);
            $cart->transfer_form = $params[1];
            $cart->save();

            if( $cart->transfer_form == '1' && empty($cart->email) ) {
                $cart->email = 'rec';
                $cart->save();
                return RequestHelper::buildArray( \Yii::$app->params['bot_config']['message_write_email'], RequestHelper::ACTION_MESSAGE );
            } else {
                if( in_array($cart->score, [2, 3, 4]) ) {
                    $cart->addition_info = 'rec';
                    $cart->save();
                    return RequestHelper::buildArray( \Yii::$app->params['bot_config']['message_write_comment'], RequestHelper::ACTION_MESSAGE );
                } else {
                    return $cart->genApplyCart();
                }
            }
        }

        return null;
    }

    /**
     * @param $params
     * @return null
     */
    public function order( $params )
    {
        if ( $cart = Cart::checkCart( $this->customer ) ) {
            return SetupOrder::{$params[1]}( $cart, $this->bot, $this->whData, $params );
        }

        return null;
    }

    /**
     * @return array
     */
    public function getAnswerCallback()
    {
        if ( $this->answer ) {
            return $this->answer;
        }

        return [
            'method'            => 'answerCallbackQuery',
            'callback_query_id' => $this->whData->callback_query->id,
        ];
    }
}