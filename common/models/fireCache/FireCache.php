<?php

namespace common\models\fireCache;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "fireCache".
 *
 * @property string $irwinID
 * @property string $name
 * @property int $updated_at
 * @property int $created_at
 */
class FireCache extends \yii\db\ActiveRecord
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
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fireCache';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['irwinID', 'name'], 'required'],
            [['updated_at', 'created_at'], 'integer'],
            [['irwinID', 'name'], 'string', 'max' => 255],
            [['irwinID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'irwinID' => 'Irwin ID',
            'name' => 'Name',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}
