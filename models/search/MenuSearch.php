<?php

namespace app\models\search;

use app\models\Menu;
use yii\data\ActiveDataProvider;

class MenuSearch extends Menu
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
            'sort'       => [ 'defaultOrder' => [ 'sort' => SORT_ASC ] ],
            'pagination' => [
                'defaultPageSize' => '10',
            ],
        ] );

        $this->load( $params );
        if ( !$this->validate() ) {
            return $dataProvider;
        }

        if ( $this->q ) {
            $query->andWhere( [ 'or', ['like', 'name', $this->q], ['like', 'text', $this->q] ]);
        }


        return $dataProvider;
    }
}