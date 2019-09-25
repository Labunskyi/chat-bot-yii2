<?php

namespace app\models\forms;

use app\models\Category;
use app\models\Menu;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;

/**
 * Class MenuForm
 *
 * @package app\models\forms
 */
class MenuForm extends Model
{

    public $id;
    public $base_id;
    public $name;
    public $text;
    public $sort;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'id', 'base_id', 'sort' ], 'integer' ],
            [ [ 'name', 'text' ], 'string' ],
        ];
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name'  => Yii::t( 'app', 'Name' ),
            'image' => Yii::t( 'app', 'Text' ),
            'sort'  => Yii::t( 'app', 'Sort' ),
        ];
    }

    /**
     * @return Menu|null
     */
    public function save()
    {
        if ( !$this->validate() ) {
            return null;
        }

        if ( $this->id ) {
            $menu = Menu::findOne( $this->id );
        } else {
            $menu = new Menu();
            $menu->base_id = $this->base_id;
            $menu->created_at = time();
        }
        $menu->name = $this->name;
        $menu->text = $this->text;
        $menu->sort = $this->sort;


        if ( $menu->save() ) {
            return $menu;
        }

        return null;
    }

}