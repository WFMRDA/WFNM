<?php

namespace common\dataMigration\app;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property int $status
 * @property int $role
 * @property string $auth_key
 * @property string $access_token
 * @property string $password_hash
 * @property string $confirmation_token
 * @property int $confirmation_sent_at
 * @property int $confirmed_at
 * @property string $recovery_token
 * @property int $recovery_sent_at
 * @property int $blocked_at
 * @property int $registration_ip
 * @property int $created_at
 * @property int $updated_at
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('migrate');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'username', 'email', 'auth_key', 'access_token', 'password_hash', 'confirmation_token', 'created_at', 'updated_at'], 'required'],
            [['id', 'status', 'role', 'confirmation_sent_at', 'confirmed_at', 'recovery_sent_at', 'blocked_at', 'registration_ip', 'created_at', 'updated_at'], 'integer'],
            [['username', 'email', 'password_hash'], 'string', 'max' => 255],
            [['auth_key', 'access_token', 'confirmation_token', 'recovery_token'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'status' => 'Status',
            'role' => 'Role',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
            'password_hash' => 'Password Hash',
            'confirmation_token' => 'Confirmation Token',
            'confirmation_sent_at' => 'Confirmation Sent At',
            'confirmed_at' => 'Confirmed At',
            'recovery_token' => 'Recovery Token',
            'recovery_sent_at' => 'Recovery Sent At',
            'blocked_at' => 'Blocked At',
            'registration_ip' => 'Registration Ip',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
