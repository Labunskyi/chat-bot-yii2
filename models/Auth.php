<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property $id                      integer
 * @property $user_id                 integer
 * @property $source                  string
 * @property $source_id               string
 * @property $access_token            string
 */

/**
 * Class Auth
 *
 * @package app\models
 */
class Auth extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%auth}}';
    }

    public static function getAccessToken(){
        return Auth::findOne(['user_id' => Yii::$app->user->getId(),'source' => 'facebook'])->access_token;
    }

}