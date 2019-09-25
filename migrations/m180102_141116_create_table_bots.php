<?php

use app\db\Migration;
use app\models\User;

/**
 * Class m180102_141116_create_table_bots
 */
class m180102_141116_create_table_bots extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable( '{{%bots}}', [
            'id'         => $this->primaryKey(),
            'user_id'    => $this->integer(),
            'base_id'    => $this->integer(),
            'platform'   => $this->string()->notNull(),
            'platform_id'=> $this->bigInteger( '100' ),
            'username'   => $this->string(),
            'first_name' => $this->string(),
            'token'      => $this->text(),
            'status'     => $this->smallInteger()->notNull()->defaultValue( 0 ),
            'updated_at' => $this->integer(),
            'created_at' => $this->integer(),
        ], $this->tableOptions );

        $this->addForeignKey('fk-bots-user_id-id', 'bots', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-bots-base_id-id', 'bots', 'base_id', 'base', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable( '{{%bots}}' );
    }
}
