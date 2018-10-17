<?php

use console\migrations\Migration;


/**
 * Class m181017_134031_mobileDeviceTables
 */
class m181017_134031_mobileDeviceTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%deviceList}}', [
            'device_id'             => $this->string() . ' PRIMARY KEY',
            'user_id'               => $this->integer()->notNull(),
            'token'                 => $this->string()->notNull(),
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createTable('{{%deviceLocations}}', [
            'device_id'             => $this->string() . ' PRIMARY KEY',
            'latitude'              => $this->decimal(9,6)->notNull(),
            'longitude'             => $this->decimal(9,6)->notNull(),
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->addForeignKey('fk_deviceToUser','{{%deviceList}}', 'user_id','{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_deviceLocToDevice','{{%deviceLocations}}', 'device_id','{{%deviceList}}', 'device_id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_deviceLocToDevice','{{%deviceLocations}}');
        $this->dropForeignKey('fk_deviceToUser','{{%deviceList}}');
        $this->dropTable('{{%deviceLocations}}');
        $this->dropTable('{{%deviceList}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181017_134031_mobileDeviceTables cannot be reverted.\n";

        return false;
    }
    */
}
