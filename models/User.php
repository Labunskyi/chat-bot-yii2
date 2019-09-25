<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * @property $id                     integer
 * @property $name                   string
 * @property $surname                string
 * @property $lastname               string
 * @property $platform               string
 * @property $avatar                 string
 * @property $phone                  string
 * @property $email                  string
 * @property $auth_key               string
 * @property $password_hash          string
 * @property $password_reset_token   string
 * @property $status                 integer
 * @property $created_at             integer
 * @property $updated_at             integer
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE  = 10;

    const PLACEHOLDER_AVATAR = 'placeholder.jpg';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ 'status', 'default', 'value' => self::STATUS_ACTIVE ],
            [ 'status', 'in', 'range' => [ self::STATUS_ACTIVE, self::STATUS_DELETED ] ],
        ];
    }

    /**
     * @param int|string $id
     *
     * @return null|static
     */
    public static function findIdentity( $id )
    {
        return static::findOne( [ 'id' => $id, 'status' => self::STATUS_ACTIVE ] );
    }

    /**
     * @param mixed $token
     * @param null  $type
     *
     * @throws NotSupportedException
     * @return null
     */
    public static function findIdentityByAccessToken( $token, $type = null )
    {
        throw new NotSupportedException( '"findIdentityByAccessToken" is not implemented.' );
    }

    /**
     * @param $email
     *
     * @return null|static
     */
    public static function findByEmail( $email )
    {
        return static::findOne( [ 'email' => $email, 'status' => self::STATUS_ACTIVE ] );
    }

    /**
     * @param $token
     *
     * @return null|static
     */
    public static function findByPasswordResetToken( $token )
    {

        if ( !static::isPasswordResetTokenValid( $token ) ) {
            return null;
        }

        return static::findOne( [
            'password_reset_token' => $token,
            'status'               => self::STATUS_ACTIVE,
        ] );
    }

    /**
     * @param $token
     *
     * @return bool
     */
    public static function isPasswordResetTokenValid( $token )
    {

        if ( empty( $token ) ) {
            return false;
        }

        $timestamp = (int)substr( $token, strrpos( $token, '_' ) + 1 );
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * @return integer|null
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @return string|null
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     *
     * @return bool
     */
    public function validateAuthKey( $authKey )
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param $password
     *
     * @return bool
     */
    public function validatePassword( $password )
    {
        return Yii::$app->security->validatePassword( $password, $this->password_hash );
    }

    /**
     * @param $password
     */
    public function setPassword( $password )
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash( $password );
    }

    /**
     * @inheritdoc
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @inheritdoc
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * @inheritdoc
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

}