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
 * @property $created_at         integer
 */
class Cart extends ActiveRecord
{

    const STATUS_DRAFT    = 1;
    const STATUS_ARCHIVED = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cart}}';
    }

}