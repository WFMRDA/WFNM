<?php

namespace common\dataMigration\app;

use Yii;

/**
 * This is the model class for table "messages".
 *
 * @property int $id
 * @property int $user_id
 * @property int $type
 * @property string $subject
 * @property string $email
 * @property string $body
 * @property string $irwinID
 * @property string $data
 * @property int $sent_at
 * @property int $seen_at
 * @property int $send_tries
 * @property int $created_at
 */
class Messages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'type', 'subject', 'email', 'irwinID', 'created_at'], 'required'],
            [['id', 'user_id', 'type', 'sent_at', 'seen_at', 'send_tries', 'created_at'], 'integer'],
            [['body', 'data'], 'string'],
            [['subject', 'email', 'irwinID'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
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
}
