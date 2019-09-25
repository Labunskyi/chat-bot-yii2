<?php

namespace app\models\search;

use app\models\Order;
use yii\data\ActiveDataProvider;

class OrderSearch extends Order
{
    public $q, $delivery_method,$delivery_point, $statu;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [ [ 'q' ], 'string' ],
            [ [ 'delivery_method' ], function(){
                return true;
            } ],
            [ [ 'delivery_point' ], function(){
                return true;
            }],
            [ [ 'statu' ], function(){
                return true;
            }],
//            [ [ 'sstatus', 'category_id' ], 'integer' ]
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $arrayProperties = get_object_vars( $this );
        $array = [];
        foreach ( $arrayProperties as $key => $value ) {
            $array[ $key ] = \Yii::t( 'app', $key );
        }

        return $array;
    }

    /**
     * @param $params
     * @param $base_id
     *
     * @return ActiveDataProvider
     */
    public function search( $params, $base_id )
    {
        $query = self::find()->where( [ 'base_id' => $base_id ] );

        $dataProvider = new ActiveDataProvider( [
            'query'      => $query,
            'sort'       => [ 'defaultOrder' => [ 'id' => SORT_DESC ] ],
            'pagination' => [
                'defaultPageSize' => '10',
            ],
        ] );

        $this->load( $params );
        if ( !$this->validate() ) {
            return $dataProvider;
        }

        if ( $this->q ) {
            $query->andWhere( [ 'or', ['like', 'name', $this->q], ['like', 'phone', $this->q], ['like', 'delivery_address', $this->q]  ]);
        }

        if($this->delivery_method){
            $query->andWhere( [ 'in', 'delivery_method_id', $this->delivery_method ]);
        }

        if($this->delivery_point){
            $query->andWhere( [ 'in', 'delivery_point_id', $this->delivery_point ]);
        }

        if($this->statu){
            $query->andWhere( [ 'in', 'status', $this->statu ]);
        }
//
//        if( $this->sstatus ) {
//            $query->andWhere( [ 'status' => $this->sstatus ]);
//        }
//
//        if( $this->category_id) {
//            $query->andWhere( [ 'category_id' => $this->category_id ]);
//        }

        return $dataProvider;
    }
}