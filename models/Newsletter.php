<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Newsletter
 *
 * @package app\models
 * @property int    $id      [int(11)]
 * @property int    $base_id [int(11)]
 * @property string $settings
 * @property string $message
 * @property int    $status  [smallint(6)]
 */

class Newsletter extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%newsletter}}';
    }
}