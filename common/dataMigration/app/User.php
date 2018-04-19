<?php

namespace common\dataMigration\app;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property integer $status
 * @property integer $role
 * @property string $auth_key
 * @property string $access_token
 * @property string $password_hash
 * @property string $confirmation_token
 * @property integer $confirmation_sent_at
 * @property integer $confirmed_at
 * @property string $recovery_token
 * @property integer $recovery_sent_at
 * @property integer $blocked_at
 * @property integer $registration_ip
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Messages[] $messages
 * @property MyFires[] $myFires
 * @property MyLocations[] $myLocations
 * @property PopTable[] $popTables
 * @property Profile $profile
 * @property Session[] $sessions
 * @property SocialAccounts[] $socialAccounts
 * @property Role $role0
 * @property Status $status0
 * @property UserSettings[] $userSettings
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'auth_key', 'access_token', 'password_hash', 'confirmation_token', 'created_at', 'updated_at'], 'required'],
            [['status', 'role', 'confirmation_sent_at', 'confirmed_at', 'recovery_sent_at', 'blocked_at', 'registration_ip', 'created_at', 'updated_at'], 'integer'],
            [['username', 'email', 'password_hash'], 'string', 'max' => 255],
            [['auth_key', 'access_token', 'confirmation_token', 'recovery_token'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['auth_key'], 'unique'],
            [['access_token'], 'unique'],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['auth_key'], 'unique'],
            [['access_token'], 'unique'],
            [['role'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status' => 'id']],
        ];
    }

    /**
     * @inheritdoc
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMyFires()
    {
        return $this->hasMany(MyFires::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMyLocations()
    {
        return $this->hasMany(MyLocations::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPopTables()
    {
        return $this->hasMany(PopTable::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSessions()
    {
        return $this->hasMany(Session::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialAccounts()
    {
        return $this->hasMany(SocialAccounts::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole0()
    {
        return $this->hasOne(Role::className(), ['id' => 'role']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(Status::className(), ['id' => 'status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSettings()
    {
        return $this->hasMany(UserSettings::className(), ['user_id' => 'id']);
    }
}
