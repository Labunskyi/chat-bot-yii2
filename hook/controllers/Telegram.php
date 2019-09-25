<?php

namespace app\hook\controllers;

use api\response\Update;
use app\hook\models\ClassHelper;
use app\hook\models\telegram\CallbackQuery;
use app\hook\models\telegram\Response;
use app\models\Bots;
use app\models\Customer;
use app\models\Helper;
use app\models\OffTimeUserNotify;
use app\models\Setting;

/**
 * Class Telegram
 *
 * @package app\hook\controllers
 * @property \api\response\Update $whData
 * @property Bots    $bot
 */
class Telegram
{
    protected $bot, $whData;

    /**
     * Telegram constructor.
     *
     * @param Bots $bot
     * @param Update $whData
     */
    public function __construct( $bot, $whData )
    {
        \Yii::$app->params['bot_config'] = Setting::getArraySettings($bot->base_id);
        $this->bot = $bot;
        $this->whData = json_decode( $whData );

        if ( !$this->whData ) {
            exit;
        }

        $preMethodName = explode( '_', array_keys( json_decode( $whData, true ) )[1] );
        $methodName = 'on';

        foreach ( $preMethodName as $word ) {
            $methodName .= ucfirst( $word );
        }

        if ( method_exists( $this, $methodName ) ) {
            $this->$methodName();
        }
    }

    protected function onMessage()
    {
        $this->whData = $this->whData->message;

        $arrayRequest = [];

        foreach ( Response::getListCheckers() as $item) {
            $arrayRequest = $item[0]::{$item[1]}($this->whData, $this->bot);
            if($arrayRequest){
                break;
            }
        }

        $request = new \api\base\Request( $this->bot->token, $arrayRequest );
        $request->send();

        Customer::customerUpdate($this->whData->from, $this->bot);
    }

    protected function onCallbackQuery()
    {
        $customer = Customer::findOne( [ 'platform' => $this->bot->platform, 'platform_id' => $this->whData->callback_query->from->id, 'bot_id' => $this->bot->id, 'base_id' => $this->bot->base_id ] );

        $callbackQuery = new CallbackQuery($this->whData, $this->bot, $customer);

        $request = new \api\base\Request( $this->bot->token, $callbackQuery->getAnswerCallback() );
        $request->send();
    }
}