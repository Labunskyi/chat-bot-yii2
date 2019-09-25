<?php

namespace app\hook\models\telegram;

use api\base\Request;
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

    public $whData, $bot, $answer, $customer;

    /**
     * CallbackQuery constructor.
     *
     *  ///////////////// WARNING!!!!! WRITE!!!! /////////
     *  name methods is very short. only first symbol for
     *  telegram callback data only 1-64 bytes payload
     *
     * @param \api\response\Update $whData
     * @param  Bots                $bot
     * @param  Customer            $customer
     */
    public function __construct( $whData, $bot, $customer )
    {
        $this->whData = $whData;
        $this->bot = $bot;
        $this->customer = $customer;
        $data = explode( '_', $whData->callback_query->data );
        $methodName = $data[0];
        unset( $data[0] );

        if ( method_exists( $this, $methodName ) ) {
            $this->answer = $this->$methodName( $data );
        }

        return null;
    }

    /**
     * @param $params
     *
     * @return null
     */
    protected function order( $params )
    {

        if ( $cart = Cart::checkCart( $this->customer ) ) {
            SetupOrder::{$params[1]}( $cart, $this->bot, $this->whData, $params );
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