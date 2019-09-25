<?php

use app\db\Migration;

/**
 * Class m180102_141121_create_table_newsletter
 */
class m180102_141121_create_table_newsletter extends Migration
{

    /**
     * @return bool|void
     * @throws \yii\base\Exception
     */
    public function up()
    {
        $this->createTable( '{{%newsletter}}', [
            'id'       => $this->primaryKey(),
            //'bot_id'  => $this->integer(),
            'base_id'  => $this->integer(),
            'settings'  => $this->text(),
            'message' => $this->text(),
            'media' => $this->json(),
            'media_type' => $this->json(),
            'buttons' => $this->json(),
            'status' => $this->smallInteger(),

        ], $this->tableOptions );

        $this->addForeignKey('fk-newsletter-base_id-id', 'newsletter', 'base_id', 'base', 'id', 'CASCADE', 'CASCADE');
        //$this->addForeignKey('fk-newsletter-bot_id-id', 'newsletter', 'bot_id', 'bots', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable( '{{%newsletter}}' );
    }
}
