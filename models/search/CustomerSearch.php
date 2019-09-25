<?php

namespace app\models\search;

use app\models\Bots;
use app\models\Customer;
use Yii;
use yii\data\ActiveDataProvider;

class CustomerSearch extends Customer
{

    public $q, $recently, $tag, $platf, $bots, $linked;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [ [ 'q', 'recently' ], 'string' ],
            [ [ 'linked' ], 'integer' ],
            [ [ 'tag' ], 'each', 'rule' => [ 'string' ] ],
            [ [ 'platf' ], 'each', 'rule' => [ 'string' ] ],
            [ [ 'bots' ], 'each', 'rule' => [ 'string' ] ],
        ];
    }


    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search( $params, $base_id )
    {
        $query = Customer::find()->where( [ 'base_id' => $base_id ] );
        // $query->addGroupBy( 'relation' );


        $dataProvider = new ActiveDataProvider( [
            'query'      => $query,
            'sort'       => [ 'defaultOrder' => [ 'id' => SORT_ASC ] ],
            'pagination' => [
                'defaultPageSize' => '10',
            ],
        ] );

        $this->load( $params );
        if ( !$this->validate() ) {
            return $dataProvider;
        }
        if ( $this->q ) {
            $query->andWhere( [
                'or',
                [ 'like', 'first_name', $this->q ],
                [ 'like', 'last_name', $this->q ],
                [ 'like', 'username', $this->q ],
                [ 'like', 'email', $this->q ],
                [ 'like', 'phone', $this->q ],
                [ 'like', 'tags', $this->q ],
            ] );
        }

        if ( $this->linked ) {
            $query->andFilterHaving( [ '>', 'count(*)', 1 ] );
        }

        if ( $this->tag ) {
            $q[] = 'or';
            foreach ( $this->tag as $key => $value ) {
                if ( $key == Yii::t( 'app', 'Without tags' ) && $value) {
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
                    $q[] = [ 'bot_id' => $key ];
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
                    $q[] = [ 'platform' => $key ];
                }
            }
            $query->andWhere( $q );
        }

        return $dataProvider;
    }

    /**
     * @param $base_id
     *
     * @return array
     */
    public function countTags( $base_id )
    {
        $customers = Customer::find()->select( [ 'tags', 'platform' ] )->where( [ 'base_id' => $base_id ] )->all();

        $counts = [
            Yii::t( 'app',
                'Without tags' ) => Customer::find()->where( [ 'base_id' => $base_id, 'tags' => '' ] )->count(),
        ];

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

        return $counts;
    }

    /**
     * @param $base_id
     *
     * @return array
     */
    public function countPlatforms( $base_id )
    {
        $counts = [];
        foreach ( Bots::platfomrs() as $key => $value ) {
            $count = Customer::find()->where( [ 'base_id' => $base_id, 'platform' => $key ] )->count();
            if ( $count ) {
                $counts[ $value ] = $count;
            }
        }

        arsort( $counts );

        return $counts;
    }

    /**
     * @param $base_id
     *
     * @return int|string
     */
    public function countLinkedProfiles( $base_id )
    {
        $count = Customer::find()->where( [ 'base_id' => $base_id ] )->count();
		
        return $count;
    }

    /**
     * @param $base_id
     *
     * @return array
     */
    public function countBots( $base_id )
    {
        $counts = [];
        foreach ( Bots::find()->where( [ 'base_id' => $base_id ] )->all() as $bot ) {
            $count = Customer::find()->where( [ 'base_id' => $base_id, 'bot_id' => $bot->id ] )->count();
            if ( $count ) {
                $counts[ $bot->id ] = $count;
            }
        }

        arsort( $counts );

        return $counts;
    }
}