<?php

namespace app\controllers;

use app\widgets\FacebookToken;
use Facebook\Facebook;
use Facebook\FacebookClient;
use Viber\Client;
use yii\rest\Controller;
use app\models\Bots;
use app\widgets\HandlerHook;

/**
 * Class HookController
 *
 * @package app\controllers
 */
class HookController extends Controller
{
    /**
     * @param $user_id
     * @param $bot_id
     * @param $sign
     *
     * @return mixed
     */
    public function actionIndex( $user_id, $bot_id, $sign )
    {
        $bot = Bots::findOne( [ 'id' => $bot_id, 'user_id' => $user_id ] );
        if ( $bot->checkSign( $sign ) ) {

            $whData = file_get_contents( 'php://input' );
            $className = 'app\\hook\\controllers\\' . ucfirst( $bot->platform );

            if ( class_exists( $className ) ) {
                new $className($bot, $whData);
            }

        }
       return null;
    }
}
