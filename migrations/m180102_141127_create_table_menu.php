<?php

use app\db\Migration;

/**
 * Class m180102_141127_create_table_menu
 */
class m180102_141127_create_table_menu extends Migration
{

    /**
     * @return bool|void
     * @throws \yii\base\Exception
     */
    public function up()
    {
        $this->createTable( '{{%menu}}', [
            'id'         => $this->primaryKey(),
            'base_id'    => $this->integer(),
            'name'       => $this->text(),
            'function'   => $this->json(),
            'text'       => $this->text(),
            'sort'       => $this->smallInteger(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions );


        // create index for column base_id
        $this->createIndex(
            'idx-menu-base_id',
            'menu',
            'base_id'
        );

        // create foreign key - base_id
        $this->addForeignKey(
            'fk-menu-base_id-id',
            'menu',
            'base_id',
            'base',
            'id',
            'CASCADE',
            'CASCADE'
        );


    }

    public function down()
    {
        $this->dropTable( '{{%menu}}' );
    }
}
