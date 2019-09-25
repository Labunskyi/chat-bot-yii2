<?php

namespace app\models;

use Viber\Client;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\IdentityInterface;

/**
 * Class Customer
 *
 * @package app\models
 * @property int    $id          [int(11)]
 * @property string $platform    [varchar(255)]
 * @property string $platform_id [varchar(255)]
 * @property int    $base_id     [int(11)]
 * @property int    $bot_id      [int(11)]
 * @property string $first_name  [varchar(255)]
 * @property string $last_name   [varchar(255)]
 * @property string $username    [varchar(255)]
 * @property string $avatar      [varchar(255)]
 * @property string $phone       [varchar(255)]
 * @property string $email       [varchar(255)]
 * @property string $tags
 * @property int    $relation    [int(11)]
 * @property int    $status      [smallint(6)]
 * @property int    $updated_at  [timestamp]
 * @property int    $created_at  [int(11)]
 */
class Customer extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE  = 10;

    const PLACEHOLDER_AVATAR = 'placeholder.jpg';

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer}}';
    }

    public function rules()
    {
        return [
            //[ [ 'first_name',  'last_name',  'username', ], 'required', 'message' => Yii::t('app', 'The field can not be empty') ],

            [
                [ 'email', 'phone', 'tags' ], 'string', 'min' => 3, 'max' => 255, 'tooLong' => Yii::t( 'app',
                'up to 255 symbols' ), 'tooShort'             => Yii::t( 'app', 'from 3 symbols' ),
            ],


            [ 'email', 'trim' ],
            [ 'email', 'email', 'message' => Yii::t( 'app', 'No valid mail' ) ],
        ];
    }


    public function attributeLabels()
    {
        return [
            'last_name'  => Yii::t( 'app', 'LastName' ),
            'first_name' => Yii::t( 'app', 'FirstName' ),
            'username'   => Yii::t( 'app', 'Username' ),
            'email'      => Yii::t( 'app', 'Email' ),
            'phone'      => Yii::t( 'app', 'Phone' ),
            'tags'       => Yii::t( 'app', 'Tags' ),
        ];
    }

    public static function platfomrs()
    {
        return [
            'telegram'  => Yii::t( 'app', 'Telegram' ),
            'messenger' => Yii::t( 'app', 'Messenger' ),
            'viber'     => Yii::t( 'app', 'Viber' ),
        ];
    }

    public function getAvatar()
    {
        if ( $this->avatar ) {
            return '/bots/customer/' . $this->platform . '/' . $this->platform_id . '/avatar.png';
        } else {
            return '/img/avatar/' . self::PLACEHOLDER_AVATAR;
        }
    }

    public function genRelationId()
    {

        for ( $i = 0; $i <= 999; $i++ ) {
            $rand = rand( 1, 999999 );
            if ( !Customer::findOne( [ 'relation' => $rand ] ) ) {
                return $rand;
                break;
            }
        }

    }

    /**
     * @param $array
     * @param Bots $bot
     * @return int
     */
    public static function customerUpdate( $array, Bots $bot )
    {
        if ( $customer = Customer::findOne( [ 'platform' => $bot->platform, 'platform_id' => $array->id, 'bot_id' => $bot->id, 'base_id' => $bot->base_id ] ) ) {

            if ( $customer->first_name != $array->first_name && $array->first_name ) {
                $customer->first_name = $array->first_name;
            }

            if ( isset( $array->last_name ) && $customer->last_name != $array->last_name ) {
                $customer->last_name = $array->last_name;
            }

            if ( isset( $array->username ) && $customer->username != $array->username ) {
                $customer->username = $array->username;
            }
            $customer->save();
            $customer_ = $customer;
        } else {
            $customer = new Customer();
            $customer->first_name = $array->first_name ?? '';
            $customer->last_name = $array->last_name ?? '';
            $customer->username = isset($array->username) ? $array->username : '';
            $customer->bot_id = $bot->id;
            $customer->platform = $bot->platform;
            $customer->platform_id = $array->id;
            $customer->base_id = $bot->base_id;
            $method_avatar = 'getAvatar' . ucfirst($bot->platform);
            if(method_exists(__CLASS__, $method_avatar)) {
                $customer->avatar = self::{$method_avatar}( $bot->token, $customer, (isset($array->avatar) ? $array->avatar : null) );
            }
            $customer->save();
            $customer->relation = $customer->id;
            $customer->save();
            $customer_ = $customer;

            if($customers = Customer::find()->where( [ 'platform' => $bot->platform, 'platform_id' => $array->id, 'base_id' => $bot->base_id ] )->andWhere(['!=', 'bot_id', $bot->id])->all()){
                $relationId = $customer->genRelationId();
                $customer->relation = $relationId;
                $customer->save();
                foreach ($customers as $customer) {
                    $customer->relation = $relationId;
                    $customer->save();
                }
            }
        }

        return $customer_->id;
    }

    /**
     * @param $token
     * @param $customer
     * @param null $avatar
     * @return bool
     * @throws \yii\base\Exception
     */
    protected static function getAvatarTelegram( $token, $customer, $avatar = null )
    {

        $api = new \api\base\API( $token );
        $photo = $api->getUserProfilePhotos()->setUserId( $customer->platform_id )->send();

        if ( isset( $photo->photos[0][0] ) ) {
            $file = $api->getFile()->setFileId( $photo->photos[0][0]->file_id )->send();

            $directory = Yii::getAlias( 'bots/customer/' . Bots::PLATFORM_TELEGRAM . '/' . $customer->platform_id . '/' );

            if ( !is_dir( $directory ) ) {
                FileHelper::createDirectory( $directory );
            }

            if ( $file->file_path ) {
                $url = 'https://api.telegram.org/file/bot' . $token . '/' . $file->file_path;
                if ( file_put_contents( $directory . 'avatar.png', file_get_contents( $url ) ) ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param $token
     * @param $customer Customer
     * @param null $avatar
     * @return bool
     * @throws \yii\base\Exception
     */
    public static function getAvatarViber( $token, $customer, $avatar = null )
    {
        if ( empty( $avatar ) ) {
            $req = new Client( [ 'token' => $token ] );
            $customerInfo = $req->getUserDetails( $customer->platform_id )->getData();
        } else {
            $customerInfo['user']['avatar'] = $avatar;
        }

        $directory = Yii::getAlias( 'bots/customer/' . Bots::PLATFORM_VIBER . '/' . $customer->platform_id . '/' );

        if ( !is_dir( $directory ) ) {
            FileHelper::createDirectory( $directory );
        }

        if ( isset( $customerInfo['user']['avatar'] ) ) {
            file_put_contents( $directory . 'avatar.png', file_get_contents( stripcslashes( $customerInfo['user']['avatar'] ) ) );

            return true;
        }

        return false;
    }

}