<?php

namespace app\hook\models\telegram;

use app\models\Customer;
use app\models\Helper;
use app\models\Order;

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
            $cart->bot_id = $customer->bot_id;
            $cart->base_id = $customer->base_id;
            $cart->customer_id = $customer->id;
            $cart->status = self::STATUS_DRAFT;
            if($oldOrder = Order::find()->where(['customer_id' => $cart->customer_id])->orderBy(['create_at' => SORT_DESC])->one()) {
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
     * @param $from
     * @return array
     */
    public function genRequestPhone( $from )
    {
        $requestArray = [
            'method'       => 'sendMessage',
            'chat_id'      => $from->id,
            'text'         => \Yii::$app->params['bot_config']['message_write_phone_number'],
            'reply_markup' => json_encode( [ "keyboard" => [ [ [ 'text' => \Yii::$app->params['bot_config']['button_send_phone_number'], 'request_contact' => true ] ] ], 'one_time_keyboard' => true, 'resize_keyboard' => true ] ),
        ];

        return $requestArray;
    }

    /**
     * @param $from
     * @return array
     */
    public function genRequestScore( $from )
    {
        $requestArray = [
            'method'       => 'sendMessage',
            'chat_id'      => $from->id,
            'text'         => \Yii::$app->params['bot_config']['message_write_score'],
            'reply_markup' => json_encode( [
                "inline_keyboard" => [
                    [
                        [
                            'text' => Helper::getScore(1),
                            "callback_data" => 'order_score_1'
                        ],
                        [
                            'text' => Helper::getScore(2),
                            "callback_data" => 'order_score_2'
                        ]
                    ],
                    [
                        [
                            'text' => Helper::getScore(3),
                            "callback_data" => 'order_score_3'
                        ],
                        [
                            'text' => Helper::getScore(4),
                            "callback_data" => 'order_score_4'
                        ]
                    ]
                ]
            ] ),
        ];

        return $requestArray;
    }

    /**
     * @param $from
     * @param null $message_id
     * @param string $method
     * @return array
     */
    public function genApplyCart( $from, $message_id = null, $method = 'sendMessage' )
    {
        $buttons = [
            "inline_keyboard" =>
                [
                    [
                        [
                            "text" => \Yii::$app->params['bot_config']['button_edit_order_full'],
                            "callback_data" => 'order_edit_full'
                        ]
                    ],
                    [
                        [
                            "text" => \Yii::$app->params['bot_config']['button_edit_order_apartment'],
                            "callback_data" => 'order_edit_apartment'
                        ]
                    ],
                    [
                        [
                            "text" => \Yii::$app->params['bot_config']['button_send_order'],
                            "callback_data" => 'order_success'
                        ]
                    ]
                ],
        ];

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

        if( $method == 'sendMessage' ) {
            $requestArray = [
                'method'       => 'sendMessage',
                'chat_id'      => $from->id,
                'text'         => $text,
                'parse_mode'   => 'HTML',
                'reply_markup' => json_encode( $buttons ),
            ];
        } else {
            $requestArray = [
                'method'       => $method,
                'chat_id'      => $from->id,
                'message_id'   => $message_id,
                'text'         => $text,
                'parse_mode'   => 'HTML',
                'reply_markup' => json_encode( $buttons ),
            ];
        }

        return $requestArray;
    }

}