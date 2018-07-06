<?php

use console\migrations\Migration;

/**
 * Class m180625_171056_addPreferences
 */
class m180625_171056_addPreferences extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('{{%profile}}', 'alert_dist', $this->integer()->notNull()->defaultValue(25));

        $this->createTable('{{%defaultLocation}}', [
            'user_id'       => $this->integer() . ' PRIMARY KEY',
            'address'               => $this->text(),
            'place_id'              => $this->string()->notNull(),
            'latitude'              => $this->decimal(9,6)->notNull(),
            'longitude'             => $this->decimal(9,6)->notNull(),
            'updated_at'    => $this->integer()->notNull(),
            'created_at'    => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->addForeignKey('fk_defaultLocationsUserId','{{%defaultLocation}}', 'user_id','{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%profile}}', 'alert_dist');

        $this->dropForeignKey('fk_defaultLocationsUserId','{{%defaultLocation}}');
        $this->dropTable('{{%defaultLocation}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180625_171056_addPreferences cannot be reverted.\n";

        return false;
    }
    */
}
