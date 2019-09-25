<?php

use app\db\Migration;

/**
 * Class m180102_141120_create_table_widget
 */
class m180102_141120_create_table_widget extends Migration
{

    public function up()
    {
        $this->createTable( '{{%widget}}', [
            'id'       => $this->primaryKey(),
            'base_id'  => $this->integer(),
            'settings' => $this->text(),
        ], $this->tableOptions );

        $this->addForeignKey('fk-widget-base_id-base_id', 'widget', 'base_id', 'base', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable( '{{%widget}}' );
    }
}
