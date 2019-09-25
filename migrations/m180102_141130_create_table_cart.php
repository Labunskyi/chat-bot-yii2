<?php

use app\db\Migration;

/**
 * Class m180102_141130_create_table_cart
 */
class m180102_141130_create_table_cart extends Migration
{

    /**
     * @return bool|void
     */
    public function up()
    {
        $this->createTable( '{{%cart}}', [
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
            'idx-cart-customer_id',
            'cart',
            'customer_id'
        );
        // create index for column id
        $this->createIndex(
            'idx-cart-id',
            'cart',
            'id'
        );
        // create index for column bot_id
        $this->createIndex(
            'idx-cart-bot_id',
            'cart',
            'bot_id'
        );
        // create index for column customer_id
        $this->createIndex(
            'idx-cart-base_id',
            'cart',
            'base_id'
        );

        // create foreign key - base_id
        $this->addForeignKey(
            'fk-cart-customer_id-id',
            'cart',
            'customer_id',
            'customer',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // create foreign key - base_id
        $this->addForeignKey(
            'fk-cart-base_id-id',
            'cart',
            'base_id',
            'base',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // create foreign key - bot_id
        $this->addForeignKey(
            'fk-cart-bot_id-id',
            'cart',
            'bot_id',
            'bots',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable( '{{%cart}}' );
    }
}
