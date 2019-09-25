<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class NewsletterMessages
 *
 * @package app\models
 * @property int    $id            [int(11)]
 * @property int    $bot_id        [int(11)]
 * @property int    $base_id       [int(11)]
 * @property string $platform_id   [varchar(255)]
 * @property string $platform      [varchar(255)]
 * @property int    $newsletter_id [int(11)]
 * @property string $message
 * @property string $media         [json]
 * @property string $media_type    [json]
 * @property string $buttons       [json]
 * @property int    $status        [smallint(6)]
 */

class NewsletterMessages extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%newsletter_messages}}';
    }
}