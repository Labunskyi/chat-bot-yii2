<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * @property $id                 integer
 * @property $base_id            integer
 * @property $key                string
 * @property $value              string
 */
class Setting extends ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'id', 'base_id' ], 'integer' ],
            [ [ 'key', 'value' ], 'string' ],
        ];
    }

    /**
     * @param $base_id
     * @param $key
     *
     * @return null|string
     */
    public static function getSetting( $base_id, $key )
    {
        if ( $setting = self::findOne( [ 'base_id' => $base_id, 'key' => $key ] ) ) {
            return $setting->value;
        }

        return null;

    }

    /**
     * @param $base_id
     * @param $key
     * @param $value
     *
     * @return Setting|null|static
     */
    public static function setSetting( $base_id, $key, $value )
    {
        if ( !$setting = self::findOne( [ 'base_id' => $base_id, 'key' => $key ] ) ) {
            $setting = new self();
            $setting->base_id = $base_id;
            $setting->key = $key;
        }

        $setting->value = $value;
        $setting->save();

        return $setting;
    }

    public static function getArraySettings( $base_id )
    {
        if ( $settings = self::findAll( [ 'base_id' => $base_id ] ) ) {
            return ArrayHelper::map( $settings, 'key', 'value' );
        }

        return null;
    }
}