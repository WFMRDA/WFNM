<?php

namespace common\dataMigration\app;

use Yii;

/**
 * This is the model class for table "myLocations".
 *
 * @property int $id
 * @property int $user_id
 * @property string $address
 * @property string $place_id
 * @property string $latitude
 * @property string $longitude
 * @property int $created_at
 * @property int $updated_at
 */
class MyLocations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'place_id', 'latitude', 'longitude', 'created_at', 'updated_at'], 'required'],
            [['id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['address'], 'string'],
            [['latitude', 'longitude'], 'number'],
            [['place_id'], 'string', 'max' => 255],
            [['user_id', 'place_id'], 'unique', 'targetAttribute' => ['user_id', 'place_id']],
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
            'address' => 'Address',
            'place_id' => 'Place ID',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
