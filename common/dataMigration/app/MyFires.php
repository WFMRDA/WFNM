<?php

namespace common\dataMigration\app;

use Yii;

/**
 * This is the model class for table "myFires".
 *
 * @property int $id
 * @property int $user_id
 * @property string $irwinID
 * @property string $name
 * @property int $created_at
 * @property int $updated_at
 */
class MyFires extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'irwinID', 'created_at', 'updated_at'], 'required'],
            [['id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['irwinID', 'name'], 'string', 'max' => 255],
            [['user_id', 'irwinID'], 'unique', 'targetAttribute' => ['user_id', 'irwinID']],
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
            'irwinID' => 'Irwin ID',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
