<?php

use app\db\Migration;

/**
 * Class m180102_141131_create_table_order
 */
class m180102_141131_create_table_order extends Migration
{

    /**
     * @return bool|void
     */
    public function up()
    {
        $this->createTable( '{{%order}}', [
            'id'                 => $this->primaryKey(),
            'base_id'            => $this->integer(),
            'bot_id'             => $this->integer(),
            'customer_id'        => $this->integer(),
            'name'               => $this->string(),
            'phone'              => $this->string(),
            'email'              => $this->string(),
            'apartment'          => $this->string()->comment('Квартира'),
            'section'            => $this->string()->comment('Секция'),
            'score'              => $this->string()->comment('Счет'),
            'transfer_form'      => $this->string()->comment('Форма передачи'),
            'addition_info'      => $this->string()->comment('Дополнительная информация'),
            'status'             => $this->smallInteger(),
            'update_at'          => $this->timestamp(),
            'create_at'          => $this->integer(),
        ], $this->tableOptions );


        // create index for column customer_id
        $this->createIndex(
            'idx-order-customer_id',
            'order',
            'customer_id'
        );
        // create index for column id
        $this->createIndex(
            'idx-order-id',
            'order',
            'id'
        );
        // create index for column bot_id
        $this->createIndex(
            'idx-order-bot_id',
            'order',
            'bot_id'
        );
        // create index for column base_id
        $this->createIndex(
            'idx-order-base_id',
            'order',
            'base_id'
        );

        // create foreign key - base_id
        $this->addForeignKey(
            'fk-order-customer_id-id',
            'order',
            'customer_id',
            'customer',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // create foreign key - base_id
        $this->addForeignKey(
            'fk-order-base_id-id',
            'order',
            'base_id',
            'base',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // create foreign key - bot_id
        $this->addForeignKey(
            'fk-order-bot_id-id',
            'order',
            'bot_id',
            'bots',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable( '{{%order}}' );
    }
}
