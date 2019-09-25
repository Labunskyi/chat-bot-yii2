<?php

namespace app\models\forms;

use app\models\Bots;
use app\models\Base;
use app\models\Helper;
use app\models\Menu;
use Yii;
use yii\base\Model;
use app\models\User;
use yii\helpers\FileHelper;
use yii\helpers\Url;

/**
 * Class AddTelegramBotForm
 *
 * @package app\models\forms
 */
class AddTelegramBotForm extends Model
{

    public $token;
    public $base_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ 'base_id', 'integer' ],
            [ 'token', 'required' ],
            [
                'token', 'match', 'pattern' => '/^[0-9]{1,99}[:]{1}[0-9\A-z\-\_]{1,255}$/', 'message' => Yii::t( 'app',
                'Wrong TG token' ),

            ],
            [ 'token', 'unique', 'targetClass' => '\app\models\Bots', 'message' => Yii::t('app','This token is already taken') ],
            [ 'token', 'checkBot' ],

        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'base_id'      => Yii::t('telegram', 'Choice reply base'),
            'token'      => Yii::t('telegram', 'API token telegram'),
        ];
    }

    /**
     * @return bool
     */
    public function checkBot()
    {
        $api = new \api\base\API( $this->token );
        $botInfo = $api->getMe()->send();

        if($botInfo->id){
           return true;
        }
        $this->addError( 'token', Yii::t( 'app', 'Wrong TG token' ) );
        return false;
    }


    /**
     * @return Bots|null
     * @throws \yii\base\Exception
     */
    public function add()
    {
        if ( !$this->validate() ) {
            return null;
        }

        if ( !$this->base_id ) { //create new base reply and add two answers (/start and /none)
            $base = new Base();
            $base->user_id = Yii::$app->user->identity->getId();
            $base->save();
            $this->base_id = $base->id;
        }

        $api = new \api\base\API( $this->token );
        $botInfo = $api->getMe()->send();
        $photo = $api->getUserProfilePhotos()->setUserId( $botInfo->id )->send();

        if(isset($photo->photos[0][0])){
            $file = $api->getFile()->setFileId( $photo->photos[0][0]->file_id )->send();

            $directory = Yii::getAlias( 'bots/' . $botInfo->id . '/' );

            if ( !is_dir( $directory ) ) {
                FileHelper::createDirectory( $directory );
            }

            if($file->file_path){
                $url = 'https://api.telegram.org/file/bot' . $this->token . '/' . $file->file_path;
                file_put_contents( $directory . 'avatar.png', file_get_contents( $url ) );
            }
        }



        $bot = new Bots();
        $bot->user_id       = Yii::$app->user->identity->getId();
        $bot->base_id       = $this->base_id;
        $bot->platform      = Bots::PLATFORM_TELEGRAM;
        $bot->platform_id   = $botInfo->id;
        $bot->username      = $botInfo->username;
        $bot->first_name    = $botInfo->first_name;
        $bot->token         = $this->token;
        $bot->status        = Bots::STATUS_ACTIVE;
        $bot->created_at    = time();

        if ( $bot->save() ) {
            if ( $this->token ) {
                $webHook = $api->setWebhook( $this->token );
                $webHook->setUrl( Url::toRoute( [ 'hook/index', 'user_id' => $bot->user_id, 'bot_id' => $bot->id, 'sign' => md5( $this->token ) ] ,true ) );
                $webHook->send();
            }
            if( !$bot->checkIssetMenu() ) {
                $bot->recordDefaultData();
            }
            return $bot;
        }

        return null;
    }

}