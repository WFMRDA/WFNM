<?php

namespace common\dataMigration\app;

use Yii;

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
 * @property integer $seen_at
 * @property integer $send_tries
 * @property integer $created_at
 *
 * @property User $user
 */
class Messages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'messages';
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
            [['user_id', 'type', 'subject', 'email', 'irwinID', 'created_at'], 'required'],
            [['user_id', 'type', 'sent_at', 'seen_at', 'send_tries', 'created_at'], 'integer'],
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
}
