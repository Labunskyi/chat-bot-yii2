<?php

namespace app\db;


class Migration extends \yii\db\Migration
{
    protected $tableOptions;

    public function init()
    {
        parent::init();
        $tableOptions = null;
        if ( $this->db->driverName === 'mysql' ) {
            $this->tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }
    }
}