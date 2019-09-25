<?php

use app\db\Migration;
use app\models\User;

/**
 * Class m180102_141119_create_table_customer
 */
class m180102_141119_create_table_customer extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable( '{{%customer}}', [
            'id'          => $this->primaryKey(),
            'platform'    => $this->string(),
            'platform_id' => $this->string(),
            'base_id'     => $this->integer(),
            'bot_id'      => $this->integer(),
            'first_name'  => $this->string(),
            'last_name'   => $this->string(),
            'username'    => $this->string(),
            'avatar'      => $this->string(),
            'phone'       => $this->string(),
            'email'       => $this->string(),
            'tags'        => $this->text()->notNull(),
            'relation'    => $this->integer(),
            'status'      => $this->smallInteger()->notNull()->defaultValue( 10 ),
            'updated_at'  => $this->integer(),
            'created_at'  => $this->integer(),
        ], $this->tableOptions );

        $this->addForeignKey('fk-customer-base_id-base_id', 'customer', 'base_id', 'base', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-customer-bot_id-bot_id', 'customer', 'bot_id', 'bots', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        $this->dropTable( '{{%customer}}' );
    }
}
