<?php

namespace common\dataMigration\app;

use Yii;

/**
 * This is the model class for table "userSettings".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $key
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class UserSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'userSettings';
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
            [['user_id', 'key', 'data', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'key', 'created_at', 'updated_at'], 'integer'],
            [['data'], 'string', 'max' => 255],
            [['user_id', 'key'], 'unique', 'targetAttribute' => ['user_id', 'key'], 'message' => 'The combination of User ID and Key has already been taken.'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'key' => 'Key',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
