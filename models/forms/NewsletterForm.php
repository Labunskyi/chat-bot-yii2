<?php

namespace app\models\forms;

use app\models\Customer;
use app\models\Newsletter;
use app\models\NewsletterMessages;
use app\models\Reply;
use app\models\Bots;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Url;

/**
 * Class ReplyForm
 *
 * @package app\models\forms
 */
class NewsletterForm extends Model
{

    public $id;
    public $base_id;
    public $message;
    public $settings;
    public $platforms;
    public $tags;
    public $bots;
    public $status;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'message' ], 'string' ],
            [ [ 'status' ], 'integer' ],
            [ [ 'bots' ], 'each', 'rule' => [ 'string' ] ],
            [ [ 'tags' ], 'each', 'rule' => [ 'string' ] ],
            [ [ 'platforms' ], 'each', 'rule' => [ 'string' ] ],
            [ [ 'message' ], 'required', 'message' => Yii::t( 'app', 'The field can not be empty' ) ],
            [ [ 'platforms', 'tags', 'bots' ], 'required', 'message' => Yii::t( 'app', 'Select at least 1 item' ) ],
        ];
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'message'   => Yii::t( 'app', 'Message for newsletter' ),
            'status'    => Yii::t( 'app', 'Status' ),
            'tags'      => Yii::t( 'app', 'Users have one of the tags:' ),
            'platforms' => Yii::t( 'app', '+ came from platforms:' ),
            'bots'      => Yii::t( 'app', '+ came through bots:' ),
        ];
    }

    /**
     * @return array
     */
    public static function statuses()
    {
        return [
            '0' => Yii::t( 'app', 'Draft' ),
            '1' => Yii::t( 'app', 'Send (cannot be edited after saving)' ),
        ];
    }

    public static function userPlatformsArray( $base_id )
    {
        $counts = [];

        foreach ( Bots::platfomrs() as $key => $value ) {
            $count = Customer::find()->where( [ 'base_id' => $base_id, 'platform' => $key ] )->count();
            if ( $count ) {
                $counts[ $key ] = $value . ' (' . $count . ')';
            }
        }

        return $counts;
    }

    public static function BotsArray( $base_id )
    {
        $counts = [];
        foreach ( Bots::find()->where( [ 'base_id' => $base_id ] )->all() as $bot ) {
            $count = Customer::find()->where( [ 'base_id' => $base_id, 'bot_id' => $bot->id ] )->count();
            if ( $count ) {
                $counts[ $bot->id ] = $bot->first_name . ' (' . $count . ')';
            }
        }

        return $counts;
    }

    public static function tagsArray( $base_id )
    {
        $customers = Customer::find()->select( [ 'tags' ] )->where( [ 'base_id' => $base_id ] )->all();

        $counts = [ Yii::t('app', 'Without tags') => Customer::find()->where( [ 'base_id' => $base_id, 'tags' => ''])->count()];

        foreach ( $customers as $customer ) {
            if ( $customer['tags'] ) {
                foreach ( explode( ',', $customer['tags'] ) as $key => $value ) {
                    if ( !isset( $counts[ $value ] ) ) {
                        $counts[ $value ] = 1;
                    } else {
                        $counts[ $value ]++;
                    }
                }
            }
        }
        arsort( $counts );

        foreach ( $counts as $key => $value ) {
            $counts [ $key ] = $key . ' (' . $value . ')';
        }

        return $counts;
    }


    /**
     * @param $base_id
     *
     * @return Newsletter|null
     */
    public function add( $base_id )
    {
        if ( !$this->validate() ) {
            return null;
        }

        $this->settings['platforms'] = $this->platforms;
        $this->settings['tags'] = $this->tags;
        $this->settings['bots'] = $this->bots;

        $newsletter = new Newsletter();
        $newsletter->base_id = $base_id;
        $newsletter->message = $this->message;
        $newsletter->settings = json_encode( $this->settings, JSON_UNESCAPED_UNICODE );
        $newsletter->status = $this->status;

        if(!$this->getFilterDataNewsLetter($newsletter)->count()){
            return null;
        }

        if ( $newsletter->save() ) {
            if ( $this->status == 1 ) {
                $this->createMessages( $newsletter );
            }

            return $newsletter;
        }

        return null;
    }

    /**
     * @param $newsletter Newsletter
     *
     * @return null
     */
    public function edit( $newsletter )
    {
        if ( !$this->validate() ) {
            return null;
        }


        $this->settings['platforms'] = $this->platforms;
        $this->settings['tags'] = $this->tags;
        $this->settings['bots'] = $this->bots;

        $newsletter->message = $this->message;
        $newsletter->settings = json_encode( $this->settings, JSON_UNESCAPED_UNICODE );
        $newsletter->status = $this->status;

        if(!$this->getFilterDataNewsLetter($newsletter)->count()){
            return null;
        }

        if ( $newsletter->save() ) {
            if ( $this->status == 1 ) {
                $this->createMessages( $newsletter );
            }

            return $newsletter;
        }

        return null;
    }

    /**
     * @param $newsletter
     *
     * @return bool
     */

    public function createMessages( $newsletter )
    {
        $customers = $this->getFilterDataNewsLetter( $newsletter );

        foreach ( $customers->all() as $cr ) {
            $nm = new NewsletterMessages();
            $nm->bot_id = $cr->bot_id;
            $nm->base_id = $cr->base_id;
            $nm->platform = $cr->platform;
            $nm->platform_id = $cr->platform_id;
            $nm->newsletter_id = $newsletter->id;
            $nm->message = $newsletter->message;
            $nm->status = 0;
            $nm->save();
        }

        return true;
    }

    /**
     * @param $newsletter
     *
     * @return \yii\db\ActiveQuery
     */

    public function getFilterDataNewsLetter( $newsletter )
    {
        $settings = $newsletter->settings;
        if ( !is_array( $newsletter->settings ) ) {
            $settings = json_decode( $newsletter->settings );
        }

        $customers = Customer::find()->where( [ 'base_id' => $newsletter->base_id ] );
        $t[] = 'or';
        $p[] = 'or';
        $b[] = 'or';

        foreach ( $settings as $key => $value ) {
            if ( $key == 'platforms' ) {
                $o = [];
                foreach ( $value as $item ) {
                    $o[] = $item;
                }
                $p[] = [ 'in', 'platform', $o ];
            }
            if ( $key == 'tags' ) {
                foreach ( $value as $item ) {
                    if ( $item == \Yii::t( 'app', 'Without tags' ) ) {
                        $t[] = [ 'tags' => '' ];
                    } elseif ( $value ) {
                        $t[] = [ 'like', 'tags', $item ];
                    }
                }
            }
            if ( $key == 'bots' ) {
                $o = [];
                foreach ( $value as $item ) {
                    $o[] = $item;
                }
                $b[] = [ 'in', 'bot_id', $o ];
            }
        }


        $customers->andWhere( $t );
        $customers->andWhere( $p );
        $customers->andWhere( $b );

        return $customers;
    }


    /**
     * @param $newsletter Newsletter
     */
    public function loadModel( $newsletter )
    {
        $this->id = $newsletter->id;
        $this->message = $newsletter->message;
        $this->status = $newsletter->status;
        $this->platforms = json_decode( $newsletter->settings )->platforms;
        $this->tags = json_decode( $newsletter->settings )->tags;
        $this->bots = json_decode( $newsletter->settings )->bots;
    }

}