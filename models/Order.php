<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;


/**
 * @property $id                 integer
 * @property $base_id            integer
 * @property $bot_id             integer
 * @property $customer_id        integer
 * @property $name               string
 * @property $phone              string
 * @property $email              string
 * @property $apartment          string
 * @property $section            string
 * @property $score              string
 * @property $transfer_form      string
 * @property $addition_info      string
 * @property $status             integer
 * @property $updated_at         timestamp
 * @property $create_at          integer
 */

class Order extends ActiveRecord
{

    const STATUS_NEW        = 1;
    const STATUS_NEW_VIEWED = 2;
    const STATUS_PROCESSING = 3;
    const STATUS_DELIVERED  = 4;

    public static function getStatusNames( $lang = 'ru' )
    {
        return [
            self::STATUS_NEW        => \Yii::t( 'app', 'new','',$lang ),
            self::STATUS_NEW_VIEWED => \Yii::t( 'app', 'new_v','',$lang ),
            self::STATUS_PROCESSING => \Yii::t( 'app', 'processing','',$lang ),
            self::STATUS_DELIVERED  => \Yii::t( 'app', 'delivered','',$lang ),
        ];
    }

    public static $status_colors = [
        self::STATUS_NEW        => 'info',
        self::STATUS_NEW_VIEWED => 'info',
        self::STATUS_PROCESSING => 'warning',
        self::STATUS_DELIVERED  => 'success',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    public function rules()
    {
        return [
            ['status', 'integer']
        ];
    }
}