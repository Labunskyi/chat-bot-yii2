<?php

use app\db\Migration;

/**
 * Class m180102_141129_create_table_setting
 */
class m180102_141129_create_table_setting extends Migration
{

    /**
     * @return bool|void
     */
    public function up()
    {
        $this->createTable( '{{%setting}}', [
            'id'      => $this->primaryKey(),
            'base_id' => $this->integer(),
            'key'     => $this->string(),
            'value'   => $this->text(),
        ], $this->tableOptions );


        // create index for column base_id
        $this->createIndex(
            'idx-setting-base_id',
            'setting',
            'base_id'
        );

        // create index for column base_id
        $this->createIndex(
            'idx-setting-key',
            'setting',
            'key'
        );

        // create foreign key - base_id
        $this->addForeignKey(
            'fk-setting-base_id-id',
            'setting',
            'base_id',
            'base',
            'id',
            'CASCADE',
            'CASCADE'
        );


    }

    public function down()
    {
        $this->dropTable( '{{%setting}}' );
    }
}
