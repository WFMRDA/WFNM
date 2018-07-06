<?php
namespace common\models\user;

use Yii;
use common\helpers\YiiHelpers;
use yii\helpers\ArrayHelper;
use common\models\myFires\MyFires;
use common\models\myLocations\MyLocations;
use common\models\user\UserSettings;
use common\models\messages\Messages;
use common\models\popup\PopTable;
use ptech\pyrocms\models\user\User as BaseModel;

class User extends BaseModel
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSettings()
    {
        return $this->hasMany(UserSettings::className(), ['user_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDefaultLocation()
    {
        return $this->hasOne(DefaultLocation::className(), ['user_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMyFires()
    {
        return $this->hasMany(MyFires::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMyLocations()
    {
        return $this->hasMany(MyLocations::className(), ['user_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPopTables()
    {
        return $this->hasMany(PopTable::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSessions()
    {
        return $this->hasMany(Session::className(), ['user_id' => 'id']);
    }


}
