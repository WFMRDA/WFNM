<?php

use console\migrations\Migration;

class m170404_225253_fireMonitoringTables extends Migration
{
    public function safeUp()
    {

        $this->createTable('{{%myFires}}', [
            'id'                    => $this->primaryKey(),
            'user_id'               => $this->integer()->notNull(),
            'irwinID'               => $this->string()->notNull(),
            'name'                  => $this->string(),
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex ('indxMyFires', '{{%myFires}}', [
            'user_id',
            'irwinID',
        ],$unique = true);

        $this->addForeignKey('fkUserMyFires', '{{%myFires}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%myLocations}}', [
            'id'                    => $this->primaryKey(),
            'user_id'               => $this->integer()->notNull(),
            'address'               => $this->text(),
            'place_id'              => $this->string()->notNull(),
            'latitude'              => $this->decimal(9,6)->notNull(),
            'longitude'             => $this->decimal(9,6)->notNull(),
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex ('indxMyLocationsUnique', '{{%myLocations}}', [
            'user_id',
            'place_id',
        ],$unique = true);

        $this->createIndex ('indxMyLocations', '{{%myLocations}}', [
            'latitude',
            'longitude',
        ],$unique = false);

        $this->addForeignKey('fkUserMyLocations', '{{%myLocations}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');


        $this->createTable('{{%userSettings}}', [
            'id'                    => $this->primaryKey(),
            'user_id'               => $this->integer()->notNull(),
            'key'                   => $this->integer()->notNull(),
            'data'                  => $this->string()->notNull(),
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex ('indxUserSettings', '{{%userSettings}}', [
            'user_id',
            'key',
        ],$unique = true);

        $this->addForeignKey('fkUserSettings', '{{%userSettings}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%messages}}', [
            'id'                    => $this->primaryKey(),
            'user_id'               => $this->integer()->notNull(),
            'type'                  => $this->integer()->notNull(),
            'subject'               => $this->string()->notNull(),
            'email'                 => $this->string()->notNull(),
            'body'                  => 'LONGTEXT',
            'irwinID'               => $this->string()->notNull(),
            'data'                  => 'LONGTEXT',
            'sent_at'               => $this->integer(),
            'seen_at'               => $this->integer(),
            'send_tries'            => $this->integer()->notNull()->defaultValue(0),
            'created_at'            => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex ('indxUserMessages', '{{%messages}}', [
            'user_id',
            'type',
            'email',
            'irwinID',
            'sent_at',
            'seen_at',
            'send_tries',
            'created_at',
        ],$unique = false);

        $this->addForeignKey('fkUserMessages', '{{%messages}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

        $this->alterColumn ( '{{%profile}}', 'email_prefs', $this->smallInteger()->notNull()->defaultValue(100));

    }

    public function safeDown()
    {
        $this->dropForeignKey('fkUserMessages','{{%messages}}');
        $this->dropTable('{{%messages}}');

        $this->dropForeignKey('fkUserSettings','{{%userSettings}}');
        $this->dropTable('{{%userSettings}}');

        $this->dropForeignKey('fkUserMyLocations','{{%myLocations}}');
        $this->dropTable('{{%myLocations}}');

        $this->dropForeignKey('fkUserMyFires','{{%myFires}}');
        $this->dropTable('{{%myFires}}');

    }


}
