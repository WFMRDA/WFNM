<?php

namespace common\models\myFires;

use Yii;
use common\models\user\User;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "myFires".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string irwinID
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
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myFires';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'irwinID'], 'required'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['irwinID','name'], 'string', 'max' => 255],
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
            'name'    => 'Name',
            'created_at' => 'Added',
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
