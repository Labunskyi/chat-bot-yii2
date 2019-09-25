<?php

use app\db\Migration;
use app\models\User;

/**
 * Class m180102_141115_create_table_base
 */
class m180102_141115_create_table_base extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {

        $this->createTable( '{{%base}}', [
            'id'      => $this->primaryKey(),
            'user_id' => $this->integer(),
        ], $this->tableOptions );

        $this->addForeignKey( '{{%base-user_id}}', '{{%base}}', 'user_id', '{{%user}}', 'id', 'CASCADE' );
    }

    public function down()
    {
        $this->dropTable( '{{%base}}' );
    }
}
