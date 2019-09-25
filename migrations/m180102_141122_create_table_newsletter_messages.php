<?php

use app\db\Migration;

/**
 * Class m180102_141122_create_table_newsletter_messages
 */
class m180102_141122_create_table_newsletter_messages extends Migration
{

    /**
     * @return bool|void
     * @throws \yii\base\Exception
     */
    public function up()
    {
        $this->createTable( '{{%newsletter_messages}}', [
            'id'       => $this->primaryKey(),
            'bot_id'  => $this->integer(),
            'base_id'  => $this->integer(),
            'platform_id'  => $this->string(),
            'platform'  => $this->string(),
            'newsletter_id'  => $this->integer(),
            'message' => $this->text(),
            'media' => $this->json(),
            'media_type' => $this->json(),
            'buttons' => $this->json(),
            'status' => $this->smallInteger(),

        ], $this->tableOptions );

        $this->addForeignKey('fk-newsletter_messages-base_id-id', 'newsletter_messages', 'base_id', 'base', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-newsletter_messages-bot_id-id', 'newsletter_messages', 'bot_id', 'bots', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-newsletter_messages-newsletter_id-id', 'newsletter_messages', 'newsletter_id', 'newsletter', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable( '{{%newsletter_messages}}' );
    }
}
