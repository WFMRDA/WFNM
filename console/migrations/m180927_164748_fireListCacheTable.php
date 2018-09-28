<?php

use console\migrations\Migration;

/**
 * Class m180927_164748_fireListCacheTable
 */
class m180927_164748_fireListCacheTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%fireCache}}', [
            'irwinID'               => $this->string() . ' PRIMARY KEY',
            'name'                  => $this->string()->notNull(),
            'updated_at'            => $this->integer()->notNull(),
            'created_at'            => $this->integer()->notNull(),
        ], $this->tableOptions);
        
        $this->createIndex ('indxFireCache', '{{%fireCache}}', [
            'irwinID',
            'name',
        ],$unique = false);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%fireCache}}');
    }
}
