<?php

namespace common\dataMigration\app;

use Yii;

/**
 * This is the model class for table "myFires".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $irwinID
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class MyFires extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myFires';
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
            [['user_id', 'irwinID', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['irwinID', 'name'], 'string', 'max' => 255],
            [['user_id', 'irwinID'], 'unique', 'targetAttribute' => ['user_id', 'irwinID'], 'message' => 'The combination of User ID and Irwin ID has already been taken.'],
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
            'irwinID' => 'Irwin ID',
            'name' => 'Name',
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
