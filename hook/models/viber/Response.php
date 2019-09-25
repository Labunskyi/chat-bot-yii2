<?php

namespace app\hook\models\viber;


use app\models\Menu;

class Response
{
    public static function getListCheckers()
    {
        return [
            [ 'app\hook\models\viber\BasePhrases', 'checkCallBackQuery'],
            [ 'app\hook\models\viber\BasePhrases', 'checkClickMenu'],
            [ 'app\hook\models\viber\BasePhrases', 'checkCommands'],
            [ 'app\hook\models\viber\BasePhrases', 'checkWaitingRecord']
        ];
    }

    public static $callback_method = [
        'get_products'     => 'getCatProds',
        'get_categories'   => 'getCatParent',
        'add_to_cart'      => 'addToCart',
        'del_from_cart'    => 'delFromCart',
        'mini_cart'        => 'miniCart',
        'get_full_cart'    => 'fullCart',
        'order_delivery_m' => 'order_setDeliveryMethod',
        'order_delivery_p' => 'order_setDeliveryPoint',
        'order_payment_m'  => 'order_setPaymentMethod',
        'order_gift_cart'  => 'order_setGiftCart',
        'order_delivery_a_error' => 'order_setEmptyDeliveryAddress',
        'order_success'    => 'order_success',
    ];

    const CALLBACK_SEPARATOR = '_';

    /**
     * @return string
     */
    public static function getButtonShadowImage() {
        return "https://{$_SERVER['HTTP_HOST']}/img/buttons/button-border__img3.png";
    }

    /**
     * @param $base_id
     * @return array
     */
    public static function mainMenuGenerate($base_id) {
        $menu = Menu::find()->where(['base_id' => $base_id])->orderBy(['sort' => SORT_ASC])->all();

        $keyboard = [];
        $menu_line_items = \Yii::$app->params['bot_config']['menu_line_items'];

        foreach ($menu as $key => $item) {
            if( (count($menu) % 2) == 1 && !next($menu) ) {
                $columns = 6;
            }else {
                $columns = 6 / $menu_line_items;
            }
            $keyboard[] =
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns( $columns )
                    ->setActionType( 'reply' )
                    ->setActionBody( $item->name )
                    ->setText( '<b>' . $item->name . '</b>' );
        }

        return $keyboard;
    }
}