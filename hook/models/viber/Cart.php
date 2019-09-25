<?php

namespace app\hook\models\viber;

use app\models\Bots;
use app\models\CartProduct;
use app\models\Customer;
use app\models\DeliveryMethod;
use app\models\DeliveryPoint;
use app\models\Helper;
use app\models\Order;
use app\models\PaymentMethod;

class Cart extends \app\models\Cart
{
    /**
     * @param Customer $customer
     * @return Cart|null|static
     */
    public static function checkCart( Customer $customer )
    {
        if ( !( $cart = self::findOne( [ 'customer_id' => $customer->id, 'status' => Helper::STATUS_ACTIVE ] ) ) ) {
            $cart = new self();
            $cart->base_id = $customer->base_id;
            $cart->bot_id = $customer->bot_id;
            $cart->customer_id = $customer->id;
            $cart->status = self::STATUS_DRAFT;
            if($oldOrder = Order::find()->where(['customer_id' => $cart->customer_id])->orderBy(['create_at' => SORT_DESC])->one()){
                $cart->name = $oldOrder->name;
                $cart->phone = $oldOrder->phone;
                $cart->email = $oldOrder->email;
                $cart->apartment = $oldOrder->apartment;
                $cart->section = $oldOrder->section;
            }

            $cart->save();
        }

        return $cart;
    }

    /**
     * @return bool
     */
    public function isFull()
    {
        if ( !$this->name || $this->name == 'rec' ) {
            return false;
        }
        if ( !$this->phone || $this->phone == 'rec' ) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function genRequestPhone()
    {
        $response[] =
            (new \Viber\Api\Keyboard\Button())
                ->setActionType('share-phone')
                ->setActionBody('reply')
                ->setText( \Yii::$app->params['bot_config']['button_send_phone_number'] );


        return RequestHelper::buildArray( \Yii::$app->params['bot_config']['message_write_phone_number'], RequestHelper::ACTION_MESSAGE, $response );
    }

    /**
     * @return array
     */
    public function genRequestScore()
    {
        $buttons[] =
            (new \Viber\Api\Keyboard\Button())
                ->setColumns(3)
                ->setText('<b>' . Helper::getScore(1) . '</b>')
                ->setActionType('reply')
                ->setActionBody( 'score_1' );
        $buttons[] =
            (new \Viber\Api\Keyboard\Button())
                ->setColumns(3)
                ->setText('<b>' . Helper::getScore(2) . '</b>')
                ->setActionType('reply')
                ->setActionBody( 'score_2' );
        $buttons[] =
            (new \Viber\Api\Keyboard\Button())
                ->setColumns(3)
                ->setText('<b>' . Helper::getScore(3) . '</b>')
                ->setActionType('reply')
                ->setActionBody( 'score_3' );
        $buttons[] =
            (new \Viber\Api\Keyboard\Button())
                ->setColumns(3)
                ->setText('<b>' . Helper::getScore(4) . '</b>')
                ->setActionType('reply')
                ->setActionBody( 'score_4' );

        return RequestHelper::buildArray( \Yii::$app->params['bot_config']['message_write_score'], RequestHelper::ACTION_MESSAGE, $buttons );
    }

    /**
     * @return array
     */
    public function genApplyCart()
    {
        $buttons[] =
            (new \Viber\Api\Keyboard\Button())
                ->setColumns(6)
                ->setText('<b>' . \Yii::$app->params['bot_config']['button_edit_order_full'] . '</b>')
                ->setActionType('reply')
                ->setActionBody( 'order_edit_full' );

        $buttons[] =
            (new \Viber\Api\Keyboard\Button())
                ->setColumns(6)
                ->setText('<b>' . \Yii::$app->params['bot_config']['button_edit_order_apartment'] . '</b>')
                ->setActionType('reply')
                ->setActionBody( 'order_edit_apartment' );

        $buttons[] =
            (new \Viber\Api\Keyboard\Button())
                ->setColumns(6)
                ->setText('<b>' . \Yii::$app->params['bot_config']['button_send_order'] . '</b>')
                ->setActionType('reply')
                ->setActionBody( 'order_success' );

        $placeholder = [
            '{{name}}'          => $this->name,
            '{{phone}}'         => $this->phone,
            '{{apartment}}'     => $this->apartment,
            '{{section}}'       => $this->section,
            '{{score}}'         => Helper::getScore($this->score),
            '{{transfer_form}}' => Helper::getTransferForm($this->transfer_form),
            '{{email}}'         => $this->email ?? '-',
            '{{comment}}'       => $this->addition_info ?? '-',
        ];

        $text = strtr( \Yii::$app->params['bot_config']['message_order_info'], $placeholder );

        return RequestHelper::buildArray( $text, RequestHelper::ACTION_MESSAGE, $buttons );
    }
}