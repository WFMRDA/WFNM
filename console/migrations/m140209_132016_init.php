<?php

// namespace common\modules\User\migrations;
use console\migrations\Migration;

class m140209_132016_init extends Migration
{

    public function safeUp()
    {

        if($this->tableExists('user')){
            $this->dropTable('{{%user}}');
        }

        $this->createTable('{{%user}}', [
            'id'                    => $this->primaryKey(),
            'username'              => $this->string()->notNull()->unique(),
            'email'                 => $this->string()->notNull()->unique(),
            'status'                => $this->smallInteger()->notNull()->defaultValue(10),
            'role'                  => $this->smallInteger()->notNull()->defaultValue(10),
            'auth_key'              => $this->string(32)->unique()->notNull(),
            'access_token'          => $this->string(32)->unique()->notNull(),
            'password_hash'         => $this->string()->notNull(),
            'confirmation_token'    => $this->string(32)->notNull(),
            'confirmation_sent_at'  => $this->integer(),
            'confirmed_at'          => $this->integer(),
            'recovery_token'        => $this->string(32),
            'recovery_sent_at'      => $this->integer(),
            'blocked_at'            => $this->integer(),
            'role'                  => $this->smallInteger()->notNull()->defaultValue(10),
            'registration_ip'       => $this->bigInteger(),
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer()->notNull(),
        ], $this->tableOptions);


        $this->createIndex('indxUser_unique_username', '{{%user}}', 'username', true);
        $this->createIndex('indxUser_unique_email', '{{%user}}', 'email', true);
        $this->createIndex('indxUser_unique_auth_key', '{{%user}}', 'auth_key', true);
        $this->createIndex('indxUser_unique_access_token', '{{%user}}', 'access_token', true);
        $this->createIndex('indxUser_unique_confirmation', '{{%user}}', 'id, confirmation_token', true);
        $this->createIndex('indxUser_unique_recovery', '{{%user}}', 'id, recovery_token', true);

        $this->createTable('{{%profile}}', [
            'user_id'           => $this->integer() . ' PRIMARY KEY',
            'first_name'        => $this->string(),
            'middle_name'       => $this->string(),
            'last_name'         => $this->string(),
            'birth_date'        => $this->date(),
            'birth_month'       => $this->integer(),
            'birth_day'         => $this->integer(),
            'birth_year'        => $this->integer(),
            'gender'            => $this->smallInteger(),
            'alternate_email'   => $this->string(),
            'website'           => $this->string(),
            'street'            => $this->string(),
            'city'              => $this->string(),
            'state'             => $this->string(),
            'zip'               => $this->integer(),
            'phone'             => $this->string(),
            'bio'               => $this->text(),
            'email_prefs'       => $this->smallInteger()->notNull()->defaultValue(100),
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex ('indxProfile', '{{%profile}}', [
            'email_prefs',
            'birth_date',
        ],$unique = false);

        $this->addForeignKey('fk_user_profile', '{{%profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%session}}', [
            'id'                    => $this->string() . ' PRIMARY KEY',
            'user_id'               => $this->integer(),
            'last_write'            => $this->integer(),
            'expire'                => $this->integer(),
            'data'                  => $this->sessionBlobType,
        ], $this->tableOptions);

        $this->createIndex ('indxSessionUserLogins', '{{%session}}', [
            'user_id',
            'last_write',
            'expire',
        ],$unique = false);
        $this->addForeignKey('fk_user_session', '{{%session}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%social_accounts}}', [
            'id'                    => $this->primaryKey(),
            'user_id'               => $this->integer()->notNull(),
            'provider'              => $this->string()->notNull(),
            'client_id'             => $this->string()->notNull(),
            'data'                  => $this->text(),
            'token'                 => $this->text(),
            'secret'                => $this->text(),
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->addForeignKey('fk_user_socialAccounts', '{{%social_accounts}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%role}}', [
            'id'        => $this->smallInteger() . ' PRIMARY KEY',
            'name'      => $this->string() . '(45)',
            //'value'     => $this->integer(),
        ], $this->tableOptions);

        $this->insert('role',[
            'id'=>30,
            'name' =>'SuperUser',
        ]);
        $this->insert('role',[
            'id'=>20,
            'name' =>'Admin',
        ]);
        $this->insert('role',[
            'id'=>10,
            'name' =>'Standard',
        ]);

        $this->createTable('{{%status}}', [
            'id'        => $this->smallInteger() . ' PRIMARY KEY',
            'name'      => $this->string() . '(45)',
            //'value'     => $this->integer(),
        ], $this->tableOptions);

        $this->insert('status',[
            'id'=>10,
            'name' =>'Active',
        ]);
        $this->insert('status',[
            'id'=>20,
            'name' =>'Inactive',
        ]);
        $this->createTable('{{%gender}}', [
            'id'        => $this->smallInteger() . ' PRIMARY KEY',
            'name'      => $this->string() . '(45)',
        ], $this->tableOptions);

        $this->insert('gender',[
            'id'=>10,
            'name' =>'Male',
        ]);

        $this->insert('gender',[
            'id'=>20,
            'name' =>'Female',
        ]);

        $this->addForeignKey('fk_role_user_id', '{{%user}}', 'role', '{{%role}}', 'id');
        $this->addForeignKey('fk_status_user_id', '{{%user}}', 'status', '{{%status}}', 'id');

        $this->createTable('{{%sysVars}}', [
            'id'                        => $this->smallInteger() . ' PRIMARY KEY',
            'enableFlashMessages'       => $this->boolean()->notNull(),
            'enableRegistration'        => $this->boolean()->notNull(),
            'enableGeneratingPassword'  => $this->boolean()->notNull(),
            'enableConfirmation'        => $this->boolean()->notNull(),
            'enableUnconfirmedLogin'    => $this->boolean()->notNull(),
            'enablePasswordRecovery'    => $this->boolean()->notNull(),
            'emailChangeStrategy'       => $this->integer()->notNull(),
            'rememberFor'               => $this->integer()->notNull(),
            'confirmWithin'             => $this->integer()->notNull(),
            'recoverWithin'             => $this->integer()->notNull(),
            'gaJsonKey'                 => $this->string(),
            'gaProfileId'               => $this->string(),
        ], $this->tableOptions);

        $this->insert('sysVars',[
            'id'                        => 1,
            'enableFlashMessages'       => false,
            'enableRegistration'        => true,
            'enableGeneratingPassword'  => false,
            'enableConfirmation'        => true,
            'enableUnconfirmedLogin'    => false,
            'enablePasswordRecovery'    => true,
            'emailChangeStrategy'       => 1,
            'rememberFor'               => 1209600,
            'confirmWithin'             => 86400,
            'recoverWithin'             => 21600,
        ]);

    }

    public function safeDown()
    {
        $this->dropTable('{{%sysVars}}');
        $this->dropForeignKey('fk_status_user_id','{{%user}}');
        $this->dropForeignKey('fk_role_user_id','{{%user}}');

        $this->dropTable('{{%role}}');
        $this->dropTable('{{%status}}');
        $this->dropTable('{{%gender}}');

        $this->dropForeignKey('fk_user_socialAccounts','{{%social_accounts}}');
        $this->dropTable('{{%social_accounts}}');

        $this->dropForeignKey('fk_user_session','{{%session}}');
        $this->dropIndex ( 'indxSessionUserLogins',  '{{%session}}' );
        $this->dropTable('{{%session}}');

        $this->dropForeignKey('fk_user_profile','{{%profile}}');
        $this->dropIndex ( 'indxProfile',  '{{%profile}}' );
        $this->dropTable('{{%profile}}');

        $this->dropIndex ( 'indxUser_unique_recovery',  '{{%user}}' );
        $this->dropIndex ( 'indxUser_unique_confirmation',  '{{%user}}' );
        $this->dropIndex ( 'indxUser_unique_access_token',  '{{%user}}' );
        $this->dropIndex ( 'indxUser_unique_auth_key',  '{{%user}}' );
        $this->dropIndex ( 'indxUser_unique_email',  '{{%user}}' );
        $this->dropIndex ( 'indxUser_unique_username',  '{{%user}}' );
        $this->dropTable('{{%user}}');
    }
}
