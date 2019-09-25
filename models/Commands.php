<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property $id                integer
 * @property $base_id           integer
 * @property $command           string
 * @property $text              string
 * @property $updated_at        timestamp
 * @property $created_at        integer
 */
class Commands extends ActiveRecord
{
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public static $default_commands = [
        [
            'command' => '/start',
            'text' => ['1' => 'Hello', '2' => 'Привет'],
        ]
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%commands}}';
    }

}