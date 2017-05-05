<?php
namespace common\models\user;

use Yii;
use common\helpers\YiiHelpers;
use yii\helpers\ArrayHelper;
use common\models\myFires\MyFires;
use common\models\myLocations\MyLocations;
use common\models\user\UserSettings;
use common\models\messages\Messages;

class User extends \common\modules\User\models\User
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
}
