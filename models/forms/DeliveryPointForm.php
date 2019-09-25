<?php

namespace app\models\forms;

use app\models\DeliveryPointStay;
use yii\base\Model;

/**
 * Class DeliveryPointForm
 *
 * @package app\models\forms
 */
class DeliveryPointForm extends Model
{
    public $id;
    public $base_id;
    public $name;
    public $status;
    public $created_at;
    public $updated_at;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [ [ 'id', 'status', 'base_id' ], 'integer' ],
            [ [ 'name' ], 'required' ]
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
        $array['status'] = \Yii::t( 'app', 'Status' );

        return $array;
    }

    /**
     * @return DeliveryPointStay|null|static
     */
    public function save()
    {
        if ( $this->validate() ) {
            if ( $this->id ) {
                $delivery_point = DeliveryPointStay::findOne( $this->id );
            } else {
                $delivery_point = new DeliveryPointStay();
                $delivery_point->base_id = $this->base_id;
                $delivery_point->created_at = time();
            }

            $delivery_point->name   = $this->name;
            $delivery_point->status = $this->status;

            if ( $delivery_point->save() ) {

                return $delivery_point;
            }
        }

        return null;
    }

}