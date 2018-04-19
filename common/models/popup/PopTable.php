<?php

namespace common\models\popup;

use Yii;
use common\models\user\User;

/**
 * This is the model class for table "popTable".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property integer $seen_at
 *
 * @property User $user
 */
class PopTable extends \yii\db\ActiveRecord
{
    const DISCLAIMER = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'popTable';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'seen_at'], 'required'],
            [['user_id', 'type', 'seen_at'], 'integer'],
            [['user_id', 'type'], 'unique'],
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
            'type' => 'Type',
            'seen_at' => 'Seen At',
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
