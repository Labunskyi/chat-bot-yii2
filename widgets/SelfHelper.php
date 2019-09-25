<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 10.07.2018
 * Time: 17:42
 */

namespace app\widgets;


class SelfHelper
{
    /**
     * @param $auth_data
     * @param $bot_token
     *
     * @return bool
     */
    public static function checkTelegramAuthorization($auth_data, $bot_token)
    {
        $check_hash = $auth_data['hash'];
        unset($auth_data['hash']);
        $data_check_arr = [];
        foreach ($auth_data as $key => $value) {
            $data_check_arr[] = $key . '=' . $value;
        }
        sort($data_check_arr);
        $data_check_string = implode("\n", $data_check_arr);
        $secret_key = hash('sha256', $bot_token, true);
        $hash = hash_hmac('sha256', $data_check_string, $secret_key);
        if (strcmp($hash, $check_hash) !== 0) {
            return false;
        }
        if ((time() - $auth_data['auth_date']) > 86400) {
            return false;
        }
        return true;
    }
}