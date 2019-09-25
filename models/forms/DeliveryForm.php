<?php

namespace app\models\forms;

use app\models\DeliveryMethod;
use app\models\DeliveryPoint;
use app\models\Helper;
use yii\base\Model;

/**
 * Class DeliveryForm
 *
 * @package app\models\forms
 */
class DeliveryForm extends Model
{
    public $id;
    public $base_id;
    public $name;
    public $with_custom;
    public $delivery_points;
    public $min_sum;
    public $status;
    public $created_at;
    public $updated_at;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [ [ 'id', 'status', 'with_custom', 'base_id' ], 'integer' ],
            [ [ 'min_sum' ], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.]?[0-9]+([eE][-+]?[0-9]+)?\s*$/' ],
            [
                'name', function ( $attribute, $params ) {
                    if ( !is_array( $this->name ) ) {
                        $this->addError( 'name', \Yii::t( 'app', 'Entry name!' ) );
                    } else {
                        $i = 0;
                        foreach ( $this->name as $name ) {
                            if ( empty( $name ) ) {
                                $i++;
                            }
                        }
                        if ( $i == 2 ) {
                            $this->addError( 'name', \Yii::t( 'app', 'Entry name!' ) );
                        }
                    }
                },
            ],
            [
                'delivery_points', function ( $attribute, $params ) {
                    if ( !is_array( $this->delivery_points ) ) {
                        $this->addError( 'delivery_points', 'Error!' );
                    } else {
                        foreach ( $this->delivery_points as $key => $delivery_point ) {
                            if ( empty( $delivery_point['delivery_point_stay'] ) ) {
                                $this->addError( 'delivery_points[' . $key . '][delivery_point_stay]', 'Error!' );
                            }
                        }
                    }
                },
            ],
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
     * @return DeliveryMethod|null|static
     */
    public function save()
    {
        if ( $this->validate() ) {
            if ( $this->id ) {
                $delivery = DeliveryMethod::findOne( $this->id );
            } else {
                $delivery = new DeliveryMethod();
                $delivery->base_id = $this->base_id;
                $delivery->created_at = time();
            }

            $delivery->min_sum = $this->min_sum;
            $delivery->with_custom = $this->with_custom;
            $delivery->name = json_encode( $this->name, JSON_UNESCAPED_UNICODE );
            $delivery->status = $this->status;

            if ( $delivery->save() ) {

                $points = [];
                //add delivery points
                if ( $this->delivery_points ) {
                    foreach ( $this->delivery_points as $delivery_point ) {
                        if ( $delivery_point[ Helper::RUS_ID ]['name'] ) {
                            if ( !$delivery_point_current = DeliveryPoint::findOne( [ 'delivery_method_id' => $delivery->id, 'id' => $delivery_point[ Helper::RUS_ID ]['id'] ] ) ) {
                                $delivery_point_current = new DeliveryPoint();
                                $delivery_point_current->base_id = $delivery->base_id;
                                $delivery_point_current->delivery_method_id = $delivery->id;
                                $delivery_point_current->created_at = time();
                            }

                            $delivery_point_current->name = json_encode( [
                                Helper::ENG_ID => $delivery_point[ Helper::ENG_ID ]['name'],
                                Helper::RUS_ID => $delivery_point[ Helper::RUS_ID ]['name'],
                            ], JSON_UNESCAPED_UNICODE );
                            $delivery_point_current->address_point = $delivery_point['address_point'];
                            $delivery_point_current->delivery_point_stay = $delivery_point['delivery_point_stay'];
                            $delivery_point_current->save();
                            $points[] = $delivery_point_current->id;
                        }
                    }

                    $pint = DeliveryPoint::findAll( [ 'delivery_method_id' => $delivery->id ] );
                    foreach ( $pint as $point ) {
                        if(!in_array($point->id, $points)){
                            $point->delete();
                        }

                    }

                }

                return $delivery;
            }
        }

        return null;
    }

}