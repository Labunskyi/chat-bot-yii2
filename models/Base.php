<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * @property $id                    integer
 * @property $user_id               integer
 */
class Base extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%base}}';
    }

    /**
     * @return array|bool
     */

    public static function getBases()
    {
        $bases = self::findAll( [ 'user_id' => Yii::$app->user->getId() ] );

        $returnBases = [];
        if ( $bases ) {
            foreach ( $bases as $base ) {
                $bots = Bots::find()->where( [ 'base_id' => $base->id ] )->asArray()->all();
                $returnBases[ $base->id ] = '';
                foreach ( $bots as $key => $bot ) {
                    $returnBases[ $base->id ] .= $bot['first_name'];
                    if ( $key != count( $bots ) - 1 ) {
                        $returnBases[ $base->id ] .= ', ';
                    }
                }
            }
        }
        $returnBases[0] = Yii::t( 'app', 'New base reply' );

        return $returnBases;

    }

    public static function getBasesObject()
    {
        $bases = self::findAll( [ 'user_id' => Yii::$app->user->getId() ] );

        return $bases;
    }

    /**
     * @param $base_id
     *
     * @return bool
     */
    public static function isOwner( $base_id )
    {
        $user_id = self::find()->select( 'user_id' )->where( [ 'id' => $base_id ] )->one();
        if ( isset($user_id->user_id ) && $user_id->user_id == Yii::$app->user->identity->getId() ) {
            return true;
        }
        return false;
    }
}