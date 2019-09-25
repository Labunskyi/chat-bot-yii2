<?php

use app\db\Migration;
use app\models\User;

/**
 * Class m180102_141114_create_table_user
 */
class m180102_141114_create_table_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable( '{{%user}}', [
            'id'                   => $this->primaryKey(),
            'name'                 => $this->string(),
            'surname'              => $this->string(),
            'lastname'             => $this->string(),
            'avatar'               => $this->string(),
            'phone'                => $this->string(),
            'email'                => $this->string()->notNull()->unique(),
            'auth_key'             => $this->string( 32 )->notNull(),
            'password_hash'        => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'status'               => $this->smallInteger()->notNull()->defaultValue( 0 ),
            'created_at'           => $this->integer()->notNull(),
            'updated_at'           => $this->integer()->notNull(),
        ], $this->tableOptions );
    }

    public function down()
    {
        $this->dropTable( '{{%user}}' );
    }
}
