<?php
namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

class MapLegendForm extends \yii\base\Model
{

    public $fireSizeList;
    public $fireStatusList;
    public $addtlLayers;


    public function setValues(){
        $userSettings = Yii::$app->systemData->mapLayers;
        // Yii::trace($userSettings,'dev');
        $this->fireStatusList = ArrayHelper::getValue($userSettings,'fireClass');
        $this->fireSizeList = ArrayHelper::getValue($userSettings,'fireSize');
        $this->addtlLayers = ArrayHelper::getValue($userSettings,'addtlLayers');
    }

}
