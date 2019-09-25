<?php

namespace app\models\forms;

use app\models\Bots;
use app\models\Base;
use Viber\Client;
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
class AddViberBotForm extends Model
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
                'token', 'unique', 'targetClass' => '\app\models\Bots', 'message' => Yii::t( 'app',
                'This token is already taken' ),
            ],

        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'base_id' => Yii::t( 'telegram', 'Choice reply base' ),
        ];
    }

    /**
     * @return bool
     */
    public function getAccountInfo(  )
    {
        try {
            $req = new Client( [ 'token' => $this->token ] );
            $result = $req->getAccountInfo();
        } catch ( \Exception $e ) {
        }

        if(isset($result)){
            return true;
        }
        return false;
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public function setWebHook( $url = '' )
    {
        try {
            $req = new Client( [ 'token' => $this->token ] );
            $req->getAccountInfo();
            $result = $req->setWebhook( $url )->getData();

            return $result['status_message'] == 'ok';
        } catch ( \Exception $e ) {
        }

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

        if ( !$this->getAccountInfo() ) {
            $this->addError( 'token', Yii::t( 'app', 'Wrong token' ) );

            return null;
        }


        if ( !$this->base_id ) { //create new base reply and add two answers (/start and /none)
            $base = new Base();
            $base->user_id = Yii::$app->user->identity->getId();
            $base->save();
            $this->base_id = $base->id;
        }

        $req = new Client( [ 'token' => $this->token ] );
        $botInfo = $req->getAccountInfo()->getData();

        $botId = explode( ':', $botInfo['id'] )[1];
        $directory = Yii::getAlias( 'bots/' . $botId . '/' );

        if ( !is_dir( $directory ) ) {
            FileHelper::createDirectory( $directory );
        }

        if ( isset($botInfo['icon']) ) {
            file_put_contents( $directory . 'avatar.png', file_get_contents( $botInfo['icon'] ) );
        }

        $bot = new Bots();
        $bot->user_id = Yii::$app->user->identity->getId();
        $bot->base_id = $this->base_id;
        $bot->platform = Bots::PLATFORM_VIBER;
        $bot->platform_id = $botId;
        $bot->username = $botInfo['uri'];
        $bot->first_name = $botInfo['name'];
        $bot->token = $this->token;
        $bot->status = Bots::STATUS_ACTIVE;
        $bot->created_at = time();

        if ( $bot->save() ) {
            if ( $this->token ) {
                $this->setWebHook(Url::toRoute( [ 'hook/index', 'user_id' => $bot->user_id, 'bot_id' => $bot->id, 'sign' => md5( $this->token ) ], true ));
            }
            if( !$bot->checkIssetMenu() ) {
                $bot->recordDefaultData();
            }
            return $bot;
        }

        return null;
    }

}