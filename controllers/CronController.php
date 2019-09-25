<?php

namespace app\controllers;

use app\hook\models\viber\Response;
use app\models\Customer;
use app\models\Helper;
use app\models\Newsletter;
use app\models\NewsletterMessages;
use app\models\OffTimeUserNotify;
use app\models\Setting;
use app\widgets\FacebookToken;
use Facebook\Facebook;
use Facebook\FacebookClient;
use Viber\Api\Sender;
use Viber\Bot;
use Viber\Client;
use yii\rest\Controller;
use app\models\Bots;
use app\widgets\HandlerHook;

/**
 * Class HookController
 *
 * @package app\controllers
 */
class CronController extends Controller
{

    const STATUS_SEND = 0;
    const STATUS_SENDED = 10;

    public function actionNewsletter()
    {
        if ( $newsletters = NewsletterMessages::find()->where( [ 'status' => self::STATUS_SEND ] )->limit(100)->all() ) {

            foreach ( $newsletters as $key => $newsletter ) {

                $bot = Bots::findOne( $newsletter->bot_id );

                switch ( $newsletter->platform ) {

                    case Bots::PLATFORM_TELEGRAM:
                        $request = new \api\base\Request( $bot->token, [
                            'method'  => 'sendMessage',
                            'chat_id' => $newsletter->platform_id,
                            'text'    => $newsletter->message,
                        ] );
                        $request->send();
                        break;

                    case Bots::PLATFORM_VIBER:
                        $botSender = new Sender( [
                            'name' => $bot->first_name,
                        ] );

                        try {
                            $botV = new Bot( [ 'token' => $bot->token ] );
                            $client = $botV->getClient();
                            $client->sendMessage(
                                ( new \Viber\Api\Message\Text() )
                                    ->setSender( $botSender )
                                    ->setReceiver( Bots::idDecode( $newsletter->platform_id ) )
                                    ->setText( preg_replace("/[\r\n]+/", "\n", $newsletter->message) )
                                    ->setKeyboard((new \Viber\Api\Keyboard())
                                        ->setButtons( Response::mainMenuGenerate($bot->base_id) ) )
                            );
                        } catch ( \Exception $e ) {


                        }
                        break;

                }

                $newsletter->status = self::STATUS_SENDED;
                $newsletter->save();
            }

        }
    }

    public function actionOffNotify()
    {
        if ( $off_notifies = OffTimeUserNotify::find()->where( [ 'status' => OffTimeUserNotify::STATUS_NEW ] )->limit(100)->all() ) {

            foreach ( $off_notifies as $key => $off_notify ) {

                $bot      = Bots::findOne( $off_notify->bot_id );
                $customer = Customer::findOne( $off_notify->customer_id );

                if( $bot && $customer && Helper::checkWorkingTime( $bot->base_id ) ) {
                    switch ( $bot->platform ) {

                        case Bots::PLATFORM_TELEGRAM:
                            $request = new \api\base\Request( $bot->token, [
                                'method'  => 'sendMessage',
                                'chat_id' => $customer->platform_id,
                                'text'    => Setting::getSetting( $bot->base_id, 'message_off_user_notify' )
                            ] );
                            $request->send();
                            break;

                        case Bots::PLATFORM_VIBER:
                            $botSender = new Sender( [
                                'name' => $bot->first_name,
                            ] );

                            try {
                                $botV = new Bot( [ 'token' => $bot->token ] );
                                $client = $botV->getClient();
                                $client->sendMessage(
                                    ( new \Viber\Api\Message\Text() )
                                        ->setSender( $botSender )
                                        ->setReceiver( Bots::idDecode( $customer->platform_id ) )
                                        ->setText( Setting::getSetting( $bot->base_id, 'message_off_user_notify' ) )

                                );
                            } catch ( \Exception $e ) {}
                            break;

                    }

                    $off_notify->status = OffTimeUserNotify::STATUS_SEND;
                    $off_notify->save();
                }

            }

        }
    }
}
