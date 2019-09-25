<?php

namespace app\models\forms;

use app\models\Category;
use app\models\Commands;
use app\models\Menu;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;

/**
 * Class CommandForm
 *
 * @package app\models\forms
 */
class CommandForm extends Model
{

    public $id;
    public $base_id;
    public $command;
    public $text;
    public $sort;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'id', 'base_id'], 'integer' ],
            [ [ 'command', 'text' ], 'string' ],
        ];
    }

    /**
     * @return Commands|null
     */
    public function save()
    {
        if ( !$this->validate() ) {
            return null;
        }

        if ( $this->id ) {
            $command = Commands::findOne( $this->id );
        } else {
            $command = new Commands();
            $command->base_id = $this->base_id;
            $command->created_at = time();
        }
        $command->command = $this->command;
        $command->text = $this->text;


        if ( $command->save() ) {
            return $command;
        }

        return null;
    }

}