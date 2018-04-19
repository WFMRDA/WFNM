<?php

use console\migrations\Migration;


/**
 * Class m171211_181437_init
 */
class m171211_181437_init extends Migration
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


        $this->createTable('{{%appUserSettings}}', [
            'id'                    => $this->primaryKey(),
            'user_id'               => $this->integer()->notNull(),
            'key'                   => $this->integer()->notNull(),
            'data'                  => $this->string()->notNull(),
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex ('indxAppUserSettings', '{{%appUserSettings}}', [
            'user_id',
            'key',
        ],$unique = true);

        $this->addForeignKey('fkappUserSettings', '{{%appUserSettings}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

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

    public function safeDown()
    {

        $this->dropForeignKey('fkPopTable','{{%popTable}}');
        $this->dropTable('{{%popTable}}');

        $this->dropForeignKey('fkUserMessages','{{%messages}}');
        $this->dropTable('{{%messages}}');

        $this->dropForeignKey('fkappUserSettings','{{%appUserSettings}}');
        $this->dropTable('{{%appUserSettings}}');

        $this->dropForeignKey('fkUserMyLocations','{{%myLocations}}');
        $this->dropTable('{{%myLocations}}');

        $this->dropForeignKey('fkUserMyFires','{{%myFires}}');
        $this->dropTable('{{%myFires}}');
    }
}
