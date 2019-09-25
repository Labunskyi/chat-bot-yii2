<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property $id             integer
 * @property $base_id        integer
 * @property $name           string
 * @property $function       string
 * @property $text           string
 * @property $sort           integer
 * @property $updated_at     timestamp
 * @property $created_at     integer
 */
class Menu extends ActiveRecord
{
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public static $default_buttons = [
        [
            'name' => ['1' => 'Catalog', '2' => 'Каталог'],
            'text' => ['1' => 'Categories and products have not yet been created: (', '2' => 'Категории и товары еще не созданы :('],
            'function' => ['class' => 'app\hook\models\{{platform}}\Category', 'method' => 'getMainCategories']
        ],
        [
            'name' => ['1' => 'Cart', '2' => 'Корзина'],
            'text' => ['1' => 'Cart empty :(', '2' => 'Корзина пуста :('],
            'function' => ['class' => 'app\hook\models\{{platform}}\Cart', 'method' => 'getMiniCart']
        ],
        [
            'name' => ['1' => 'Orders', '2' => 'Заказы'],
            'text' => ['1' => 'Order empty :(', '2' => 'Заказов нету :('],
            'function' => ['class' => 'app\hook\models\{{platform}}\Order', 'method' => 'getAllOrders']
        ],
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }
}