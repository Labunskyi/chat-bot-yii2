<?php
namespace app\hook\models\telegram;


class RequestHelper
{
    /**
     * @param      $chat_id
     * @param      $text
     * @param null $buttons
     *
     * @return array
     */
    public static function buildArray( $chat_id, $text, $buttons = null )
    {
        return [
            'method'       => 'sendMessage',
            'chat_id'      => $chat_id,
            'text'         => $text,
            'parse_mode'   => 'HTML',
            'reply_markup' => $buttons,
        ];
    }
}