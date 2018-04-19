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
    public function getMyfires()
    {
        return $this->hasMany(MyFires::className(), ['user_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMylocations()
    {
        return $this->hasMany(MyLocations::className(), ['user_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettings()
    {
        return $this->hasMany(MyLocations::className(), ['user_id' => 'id']);
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
     public function getPopups()
     {
         return $this->hasMany(PopTable::className(), ['user_id' => 'id']);
     }
     /**
      * @return \yii\db\ActiveQuery
      */
     public function getUserSettings()
     {
         return $this->hasMany(UserSettings::className(), ['user_id' => 'id']);
     }
}
