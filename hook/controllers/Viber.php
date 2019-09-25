<?php

namespace app\hook\controllers;

use app\hook\models\viber\RequestHelper;
use app\hook\models\viber\Response;
use app\models\Bots;
use app\models\Commands;
use app\models\Customer;
use app\models\Helper;
use app\models\OffTimeUserNotify;
use app\models\Setting;
use Viber\Api\Sender;
use Viber\Bot;
use Viber\Client;

/**
 * Class Viber
 *
 * @package app\hook\controllers
 * @property Bots    $bot
 */
class Viber
{
    protected $bot, $whData;

    /**
     * Viber constructor.
     * @param $bot
     * @param $whData
     */
    public function __construct( $bot, $whData )
    {
        \Yii::$app->params['bot_config'] = Setting::getArraySettings($bot->base_id);

        $this->bot = $bot;

        $this->whData = json_decode( $whData );

        if ( !$this->whData ) {
            exit;
        }

        $this->handler();
    }

    private function handler()
    {
        $botHandler = $this->bot;
        $botSender = new Sender( [
            'name' => $botHandler->first_name
        ] );

        try {
            $bot = new Bot( [ 'token' => $botHandler->token ] );

            $bot->onConversation(function ($event) use ($bot, $botSender, $botHandler) {

                $response = Commands::findOne(['base_id' => $this->bot->base_id, 'command' => '/start']);

                return (new \Viber\Api\Message\Text())
                    ->setSender($botSender)
                    ->setText($response->text)
                    ->setKeyboard((new \Viber\Api\Keyboard())->setButtons( Response::mainMenuGenerate($botHandler->base_id) ));
            })

            ->onText( '|.*|s', function ( $event ) use ( $botHandler, $bot, $botSender ) {

                $response = [];
                foreach ( Response::getListCheckers() as $item) {
                    $response = $item[0]::{$item[1]}($this->whData, $botHandler);
                    if($response){
                        break;
                    }
                }

                if( $response ) {
                    $return = [];
                    switch ($response['type']) {
                        case RequestHelper::ACTION_MESSAGE :
                            $return = ( new \Viber\Api\Message\Text() )
                                ->setSender( $botSender )
                                ->setReceiver( $event->getSender()->getId() )
                                ->setMinApiVersion(3)
                                ->setText( preg_replace("/[\r\n]+/", "\n", $response['response']) );
                        break;
                        case RequestHelper::ACTION_CAROUSEL :
                            $return =( new \Viber\Api\Message\CarouselContent() )
                                ->setSender( $botSender )
                                ->setReceiver( $event->getSender()->getId() )
                                ->setBgColor('#ffffff')
                                ->setButtonsGroupColumns(6)
                                ->setButtonsGroupRows(7)
                                ->setMinApiVersion(3)
                                ->setButtons( $response['response'] );
                        break;
                    }
                    $bot->getClient()->sendMessage(
                        $return->setKeyboard((new \Viber\Api\Keyboard())->setButtons( $response['keyboard'] ?? Response::mainMenuGenerate($botHandler->base_id) ) )
                    );
                }
            })

            ->run();

        } catch ( \Exception $e ) {
            
        }

        $this->customerUpdate();
    }

    /**
     * @return bool|int
     */
    private function customerUpdate()
    {
        if( isset($this->whData->sender) ) {
            $name = explode( ' ', $this->whData->sender->name );
            $data = [
                'id'          => Bots::idEncode( $this->whData->sender->id ),
                'first_name'  => isset( $name[0] ) ? $name[0] : $this->whData->sender->name,
                'last_name'   => isset( $name[1] ) ? $name[1] : '',
                'avatar'      => isset( $this->whData->sender->avatar ) ? $this->whData->sender->avatar : '',
            ];

            return Customer::customerUpdate((object)$data, $this->bot);
        }

        return false;
    }

}
