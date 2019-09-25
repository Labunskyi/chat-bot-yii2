<?php

namespace app\models;

use Yii;

/**
 * Class Helper
 * @package app\models
 */
class Helper
{
    const ENG_ID = 1;
    const RUS_ID = 2;

    const STATUS_ACTIVE   = 1;
    const STATUS_DISABLED = 2;

    // for order & cart
    const YES_GIFT_CART   = 1;
    const NO_GIFT_CART    = 0;

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => Yii::t( 'app', 'Active' ),
            self::STATUS_DISABLED => Yii::t( 'app', 'Disabled' )
        ];
    }

    /**
     * @return bool|int
     */
    public static function getCurrentLanguageId()
    {
        switch (Yii::$app->language) {
            case 'ru':
                return self::RUS_ID;
            case 'en':
                return self::ENG_ID;
        }

        return false;
    }

    /**
     * @param $score_id
     * @return string
     */
    public static function getScore( $score_id )
    {
        switch ($score_id) {
            case 1:
                return Setting::getSetting('2', 'button_score_1');
                break;
            case 2:
                return Setting::getSetting('2', 'button_score_2');
                break;
            case 3:
                return Setting::getSetting('2', 'button_score_3');
                break;
            case 4:
                return Setting::getSetting('2', 'button_score_4');
                break;
            default:
                return Setting::getSetting('2', 'button_score_5');
                break;
        }
    }

    /**
     * @param $transfer_id
     * @return string
     */
    public static function getTransferForm( $transfer_id )
    {
        switch ($transfer_id) {
            case 1:
                return Setting::getSetting('2', 'button_transfer_form_1');
                break;
            case 2:
                return Setting::getSetting('2', 'button_transfer_form_2');
                break;
            default:
                return Setting::getSetting('2', 'button_transfer_form_3');
                break;
        }
    }

}