<?php

namespace common\dataMigration\app;

use Yii;

/**
 * This is the model class for table "myLocations".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $address
 * @property string $place_id
 * @property string $latitude
 * @property string $longitude
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class MyLocations extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myLocations';
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
            [['user_id', 'place_id', 'latitude', 'longitude', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['address'], 'string'],
            [['latitude', 'longitude'], 'number'],
            [['place_id'], 'string', 'max' => 255],
            [['user_id', 'place_id'], 'unique', 'targetAttribute' => ['user_id', 'place_id'], 'message' => 'The combination of User ID and Place ID has already been taken.'],
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
            'address' => 'Address',
            'place_id' => 'Place ID',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
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
