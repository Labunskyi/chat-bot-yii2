<?php

namespace app\models\search;

use app\models\Newsletter;
use yii\data\ActiveDataProvider;

class NewsletterSearch extends Newsletter
{

    public $q, $tag, $platf, $bots;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [ [ 'q' ], 'string' ],
            [ [ 'tag' ], 'each', 'rule' => [ 'string' ] ],
            [ [ 'platf' ], 'each', 'rule' => [ 'string' ] ],
            [ [ 'bots' ], 'each', 'rule' => [ 'string' ] ],
        ];
    }

    public function statuses(){
        return [
            '0' => \Yii::t('app', 'Draft'),
            '1' => \Yii::t('app', 'In line for sending'),
            '2' => \Yii::t('app', 'Sending in progress'),
            '3' => \Yii::t('app', 'Sended'),
        ];
    }


    /**
     * @param $params
     * @param $base_id
     *
     * @return ActiveDataProvider
     */
    public function search( $params, $base_id )
    {
        $query = self::find()->where( [ 'base_id' => $base_id ] )->orderBy( [ 'id' => SORT_DESC ] );

        $dataProvider = new ActiveDataProvider( [
            'query'      => $query,
            'pagination' => [
                'defaultPageSize' => '5',
            ],
        ] );

        $this->load( $params );
        if ( !$this->validate() ) {
            return $dataProvider;
        }

        if ( $this->q ) {
            $query->andWhere( [ 'like', 'message', $this->q ]);
        }

        if($this->status){
//            $count_u = new \app\models\forms\NewsletterForm();
//            $count_u = $count_u->getFilterDataNewsLetter( $searchModel )->count();
//            $count_s = \app\models\NewsletterMessages::find()->where( [ 'newsletter_id' => $searchModel->id, 'status' => 1 ] )->count();
//            if ( $searchModel->status == 0 ) {
//                return Yii::t( 'app', 'Draft' );
//            } elseif ( $searchModel->status == 1 && $count_s == $count_u ) {
//                return Yii::t( 'app', 'Sended' );
//            } elseif ( $searchModel->status == 1 && !$count_s ) {
//                return Yii::t( 'app', 'In line for sending' );
//            } elseif ( $searchModel->status == 1 && $count_s ) {
//                return Yii::t( 'app', 'Sending in progress' );
//            }
        }

        if ( $this->tag ) {
            $q[] = 'or';
            foreach ( $this->tag as $key => $value ) {
                if ( $key == \Yii::t( 'app', 'Without tags' ) && $value) {
                    $q[] = [ 'tags' => '' ];
                } elseif ( $value ) {
                    $q[] = [ 'like', 'tags', $key ];
                }
            }
            $query->andWhere( $q );
        }


        if ( $this->bots ) {
            $q = [];
            foreach ( $this->bots as $key => $value ) {
                if ( $value ) {
                    if ( !isset( $q[0] ) ) {
                        $q[] = 'or';
                    }
                    $q[] = [ 'like', 'settings', $key ];
                }
            }
            $query->andWhere( $q );
        }


        if ( $this->platf ) {
            $q = [];
            foreach ( $this->platf as $key => $value ) {
                if ( $value ) {
                    if ( !isset( $q[0] ) ) {
                        $q[] = 'or';
                    }
                    $q[] = [ 'like', 'settings', $key ];
                }
            }
            $query->andWhere( $q );
        }

        return $dataProvider;
    }
}