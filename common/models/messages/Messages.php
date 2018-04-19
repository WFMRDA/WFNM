<?php

namespace common\models\messages;

use Yii;
use common\models\user\User;
use common\models\user\Profile;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "messages".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property string $subject
 * @property string $email
 * @property string $body
 * @property string $irwinID
 * @property string $data
 * @property integer $sent_at
 * @property integer $send_tries
 * @property integer $created_at
 *
 * @property User $user
 */
class Messages extends \yii\db\ActiveRecord
{
    const ALERTS = 10;
    const UPDATES  = 20;
    const FINAL_MESSAGE  = 30;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'subject', 'email', 'irwinID'], 'required'],
            [['user_id', 'type', 'sent_at','seen_at', 'send_tries', 'created_at'], 'integer'],
            [['body', 'data'], 'string'],
            [['subject', 'email', 'irwinID'], 'string', 'max' => 255],
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
            'subject' => 'Subject',
            'email' => 'Email',
            'body' => 'Body',
            'irwinID' => 'Irwin ID',
            'data' => 'Data',
            'sent_at' => 'Sent At',
            'seen_at' => 'Seen At',
            'send_tries' => 'Send Tries',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'user_id']);
    }
}
