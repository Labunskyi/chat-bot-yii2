<?php

namespace app\models\search;

use app\models\Commands;
use yii\data\ActiveDataProvider;

class CommandSearch extends Commands
{
    public $q;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [ [ 'q' ], 'string' ],
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
        $query = self::find()->where( [ 'base_id' => $base_id ] );

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
            $query->andWhere( [ 'or', ['like', 'command', $this->q], ['like', 'text', $this->q] ]);
        }


        return $dataProvider;
    }
}