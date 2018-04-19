<?php

namespace common\models\system;

use Yii;

/**
 * This is the model class for table "session".
 *
 * @property string $id
 * @property integer $user_id
 * @property integer $last_write
 * @property integer $expire
 * @property resource $data
 *
 * @property User $user
 */
class Session extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'session';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['user_id', 'last_write', 'expire'], 'integer'],
            [['data'], 'string'],
            [['id'], 'string', 'max' => 255],
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
            'last_write' => 'Last Write',
            'expire' => 'Expire',
            'data' => 'Data',
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
