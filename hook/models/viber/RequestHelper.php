<?php
namespace app\hook\models\viber;


class RequestHelper
{

    const ACTION_CAROUSEL = 'carousel';
    const ACTION_MESSAGE  = 'message';

    /**
     * @param $response
     * @param string $type
     * @param null $keyboard
     * @return array
     */
    public static function buildArray( $response, $type = RequestHelper::ACTION_MESSAGE, $keyboard = null )
    {
        return [
            'type'     => $type,
            'response' => $response,
            'keyboard' => $keyboard
        ];
    }
}