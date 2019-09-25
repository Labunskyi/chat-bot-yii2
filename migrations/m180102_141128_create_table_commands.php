<?php

use app\db\Migration;

/**
 * Class m180102_141128_create_table_commands
 */
class m180102_141128_create_table_commands extends Migration
{

    /**
     * @return bool|void
     */
    public function up()
    {
        $this->createTable( '{{%commands}}', [
            'id'         => $this->primaryKey(),
            'base_id'    => $this->integer(),
            'command'    => $this->string(),
            'text'       => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions );


        // create index for column base_id
        $this->createIndex(
            'idx-commands-base_id',
            'commands',
            'base_id'
        );

        // create foreign key - base_id
        $this->addForeignKey(
            'fk-commands-base_id-id',
            'commands',
            'base_id',
            'base',
            'id',
            'CASCADE',
            'CASCADE'
        );


    }

    public function down()
    {
        $this->dropTable( '{{%commands}}' );
    }
}
