<?php

namespace app\models;

use app\models\forms\SettingsForm;
use Viber\Client;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\IdentityInterface;

/**
 * @property $id                             integer
 * @property $user_id                        integer
 * @property $base_id                        integer
 * @property $platform                       string
 * @property $platform_id                    integer
 * @property $username                       string
 * @property $first_name                     string
 * @property $token                          string
 * @property $status                         integer
 * @property $updated_at                     integer
 * @property $created_at                     integer
 */
class Bots extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE  = 10;

    const PLACEHOLDER_AVATAR = 'placeholder_bot.png';

    const PLATFORM_TELEGRAM  = 'telegram';
    const PLATFORM_MESSEGNER = 'messenger';
    const PLATFORM_VIBER     = 'viber';

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function rules()
    {
        return [
            [
                'token', 'unique', 'targetClass' => '\app\models\Bots', 'message' => Yii::t( 'app',
                'This token is already taken' ),
            ],
        ];
    }

    /**
     * @param $name
     *
     * @return mixed|null
     */
    public static function getIconPlatform( $name )
    {
        $arr = [
            self::PLATFORM_VIBER     => '/img/icon/ico_viber.png',
            self::PLATFORM_TELEGRAM  => '/img/icon/ico_telegram.png',
            self::PLATFORM_MESSEGNER => '/img/icon/ico_messenger.png',
        ];

        return $arr[ $name ] ?? null;
    }

    /**
     * @param $name
     *
     * @return mixed|null
     */
    public static function getBackgroundPlatform( $name )
    {
        $arr = [
            self::PLATFORM_VIBER     => '/img/platforms/background/viber.jpg',
            self::PLATFORM_TELEGRAM  => '/img/platforms/background/telegram.jpg',
            self::PLATFORM_MESSEGNER => '/img/platforms/background/messenger.jpg',
        ];

        return $arr[ $name ] ?? null;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bots}}';
    }

    /**
     * @return array
     */
    public static function platfomrs()
    {
        return [
            'telegram'  => Yii::t( 'app', 'Telegram' ),
            'messenger' => Yii::t( 'app', 'Messenger' ),
            'viber'     => Yii::t( 'app', 'Viber' ),
        ];
    }

    public function getPlatformName()
    {
        return self::platfomrs()[ $this->platform ];
    }

    /**
     * @param $bot_id
     *
     * @return string
     */
    public static function getBotAvatar( $bot_id )
    {

        $avatar = '/app-assets/images/logo/logo-80x80.png';
        $bot = self::findOne( $bot_id );

        if ( $bot->platform == self::PLATFORM_TELEGRAM || $bot->platform == self::PLATFORM_VIBER ) {
            $avatar = is_file( 'bots/' . $bot->platform_id . '/avatar.png' ) ? ( '/bots/' . $bot->platform_id . '/avatar.png' ) : ( '/img/avatar/placeholder_bot.png' );
        }

        if ( $bot->platform == self::PLATFORM_MESSEGNER ) {
            $avatar = 'https://graph.facebook.com/v3.0/' . $bot->platform_id . '/picture?height=150&width=150';
        }

        return $avatar;
    }

    /**
     * @param $id
     *
     * @return string
     */
    public static function idEncode( $id )
    {
        return rtrim( strtr( base64_encode( $id ), '+/', '-_' ), '=' );
    }

    /**
     * @param $id
     *
     * @return bool|string
     */
    public static function idDecode( $id )
    {
        return base64_decode( str_pad( strtr( $id, '-_', '+/' ), strlen( $id ) % 4, '=', STR_PAD_RIGHT ) );
    }

    /**
     * @return string
     */
    public function genLinkBot()
    {
        $link = '';

        if ( $this->platform == self::PLATFORM_MESSEGNER ) {
            $username = $this->username ? $this->username : $this->platform_id;
            $link = 'https://m.me/' . $username;
        } elseif ( $this->platform == self::PLATFORM_TELEGRAM ) {
            $link = 'https://t.me/' . $this->username;
        } elseif ( $this->platform == self::PLATFORM_VIBER ) {
            $link = 'viber://pa/info?uri=' . $this->username;
        }

        return $link;
    }

    /**
     * @return Bots[]
     */
    public static function getPlatforms()
    {
        return self::findAll( [ 'user_id' => Yii::$app->user->getId() ] );
    }

    /**
     * @return Bots[]
     */
    public static function getPlatformsSession()
    {
        $base_id = Yii::$app->session->get( 'base_id' );
        $user_id = Yii::$app->user->getId();

        if ( !$base_id || !Base::isOwner( $base_id ) ) {
            if ( $base = Base::findOne( [ 'user_id' => $user_id ] ) ) {
                $base_id = $base->id;
            }

        }

        return self::findAll( [ 'user_id' => $user_id, 'base_id' => $base_id ] );

    }

    /**
     * @param $sign
     *
     * @return bool
     */
    public function checkSign( $sign )
    {
        if ( md5( $this->token ) == $sign ) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function checkIssetMenu()
    {
        if(Menu::findOne(['base_id' => $this->base_id]))
            return true;

        return false;
    }

    public function recordDefaultData()
    {
        foreach (Menu::$default_buttons as $menu){
            $i = 1;
            $item = new Menu();
            $item->name = $menu['name'][Helper::getCurrentLanguageId()];
            $item->function = json_encode( $menu['function'] );
            $item->text = $menu['text'][Helper::getCurrentLanguageId()];
            $item->sort = $i;
            $item->base_id = $this->base_id;
            $item->save();
            $i++;
        }

        foreach (Commands::$default_commands as $command){
            $item = new Commands();
            $item->command = $command['command'];
            $item->text = $command['text'][Helper::getCurrentLanguageId()];
            $item->base_id = $this->base_id;
            $item->save();
        }

        foreach (SettingsForm::$default_settings as $setting){
            $item = new Setting();
            $item->key = $setting['key'];
            $item->value = $setting['value'][Helper::getCurrentLanguageId()];
            $item->base_id = $this->base_id;
            $item->save();
        }
    }

    public function setNewToken()
    {
        if ( $this->platform == self::PLATFORM_TELEGRAM ) {
            try {
                $api = new \api\base\API( $this->token );
                $botInfo = $api->getMe()->send();
            } catch ( \Exception $e ) {
            }
            if ( isset( $botInfo ) && $botInfo->id ) {
                $webHook = $api->setWebhook( $this->token );
                $webHook->setUrl( Url::toRoute( [ 'hook/index', 'user_id' => $this->user_id, 'bot_id' => $this->id, 'sign' => md5( $this->token ) ],
                    true ) );
                $webHook->send();

                $this->username = $botInfo->username;
                $this->first_name = $botInfo->first_name;
                $this->platform_id = $botInfo->id;
                $this->save();

                return true;
            } else {
                $this->addError( 'token', Yii::t( 'app', 'Wrong token' ) );

                return false;
            }
        } elseif ( $this->platform == self::PLATFORM_VIBER ) {
            try {
                $req = new Client( [ 'token' => $this->token ] );
                $result = $req->getAccountInfo();
            } catch ( \Exception $e ) {
            }
            if ( isset( $result ) ) {
                try {
                    $url = Url::toRoute( [ 'hook/index', 'user_id' => $this->user_id, 'bot_id' => $this->id, 'sign' => md5( $this->token ) ],
                        true );
                    $req = new Client( [ 'token' => $this->token ] );
                    $req->getAccountInfo();
                    $result = $req->setWebhook( $url )->getData();

                    $botInfo = $req->getAccountInfo()->getData();
                    $botId = explode( ':', $botInfo['id'] )[1];
                    $this->platform_id = $botId;
                    $this->username = $botInfo['uri'];
                    $this->first_name = $botInfo['name'];
                    $this->save();

                    return $result['status_message'] == 'ok';
                } catch ( \Exception $e ) {
                }
            }
            $this->addError( 'token', Yii::t( 'app', 'Wrong token' ) );
        }

        return false;
    }

    public function getWebHookInfo()
    {
        try {
            $api = new \api\base\API( $this->token );

            return $api->getWebhookInfo()->send();
        } catch ( \Exception $e ) {
            return false;
        }
    }

    public static function debug($text){
        $request = new \api\base\Request( '758830480:AAEwQEqmSRyqLXS2iM6esQWv8qcUEwUIJZI', [
            'method' => 'sendMessage',
            'chat_id' => '193063972',
            'text' => $text
        ]);
        $request->send();
    }
}