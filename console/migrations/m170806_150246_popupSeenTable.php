<?php

use console\migrations\Migration;

class m170806_150246_popupSeenTable extends Migration
{
    public function up()
    {
        $this->createTable('{{%popTable}}', [
            'id'                    => $this->primaryKey(),
            'user_id'               => $this->integer()->notNull(),
            'type'                  => $this->integer()->notNull(),
            'seen_at'               => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex ('indxPopTable', '{{%popTable}}', [
            'user_id',
            'type',
            'seen_at',
        ],$unique = false);
        $this->addForeignKey('fkPopTable', '{{%popTable}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

    }

    public function down()
    {
        $this->dropForeignKey('fkPopTable','{{%popTable}}');
        $this->dropTable('{{%popTable}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
