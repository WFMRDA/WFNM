<?php

namespace frontend\components;

use Yii;
use yii\base\Component;
use yii\base\Event;
use yii\di\Instance;
use yii\web\Session;
use yii\helpers\Html;
use yii\helpers\Url;
use \yii\data\ArrayDataProvider;
use common\models\user\User;
use common\models\user\UserSettings;
use common\models\helpers\WfnmHelpers;
use common\models\messages\Messages;
use yii\data\ActiveDataProvider;
use common\models\popup\PopTable;
use ptech\pyrocms\components\SystemData as BaseModel;
use common\models\user\DefaultLocation;

class SystemData extends BaseModel{

    public $user;
    protected $defaultFireClasses = [
       'A' => 'NEW',
       'B' => 'EMERGING',
       'E' => 'ACTIVE',
       'C' => 'CONTAINED',
       'D' => 'CONTROLLED',
    //    'F' => 'OUT',
    ];
    // protected $defaultFireClasses = ['A','B','C','D','E','F'];
    protected $defaultFireSizes = [
        1 => '< 99ac',
        2 => '100ac - 999ac',
        3 => '1000ac - 9999ac',
        4 => '10000ac - 99999ac',
        5 => '>= 100000 ac',
    ];

    protected $defaultMapLayers = [

    ];


    // protected $defaultFireSizes = [1,2,3,4,5];

    public function init()
    {
        if(!Yii::$app->user->isGuest){
            $this->setBaseValues();
        }
        parent::init();
    }

    public function getFireClasses(){
        return $this->defaultFireClasses;
    }
    public function getFireSizes(){
        return $this->defaultFireSizes;
    }


    protected $_userMessages;

    public function setUserMessages(){
        $query = Messages::find()
            ->andWhere(['user_id' => Yii::$app->user->identity->id])
            ->orderBy([
                // 'created_at' => SORT_ASC,
                'created_at' => SORT_DESC,
            ]);
        $this->_userMessages = new ActiveDataProvider([
            'query' => $query,
            /*'pagination' => [
                'pageSize' => 4,
            ],*/
        ]);

    }

    public function getUserMessages(){
        if($this->_userMessages == null){
            $this->setUserMessages();
        }
        return $this->_userMessages;
    }

    public function getLegendHelpToggle(){
        $style = $this->getSetting('legendHelpToggle');
        return ($style == null)?'active' : $style;
    }

    protected $_defaultLoc;
    protected function setDefaultLoaction(){
        $this->_defaultLoc = DefaultLocation::find()->where(['user_id' => Yii::$app->user->identity->id])->asArray()->one();
    }
    public function getDefaultLocation(){
        if(!isset($this->_defaultLoc)){
            $this->setDefaultLoaction();
        }
        return $this->_defaultLoc;
    }

    public function getMapLayers(){
        $layers = $this->getSetting('mapLayers');
        return ($layers == null)? $this->getDefaultLayers() : json_decode($layers,true,512,JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
    }

    protected function getDefaultLayers(){
        return  [
            'incidentLayers'=>array_merge(array_keys($this->defaultFireClasses),array_keys($this->defaultFireSizes)),
            // 'mapLayers'=>array_keys($this->defaultFireSizes),
        ];
    }

    public function getPlLevel(){
        $mapData = Yii::createObject(Yii::$app->params['mapData']);
        return $mapData->getPrepardnessLevel('NIC');
    }

    protected $_userSettings;

    public function setUserSettings(){
        // $this->_userSettings = [];

        $this->_userSettings = UserSettings::find()
            ->andWhere(['user_id' => Yii::$app->user->identity->id])
            ->indexBy('key')
            ->all();
    }

    public function getUserSettings(){
        if($this->_userSettings == null){
            $this->setUserSettings();
        }
        return $this->_userSettings;
    }

    protected $_disclaimer;
    public function setDisclaimer(){
        // $this->_disclaimer = true;
        // return false;
        $query =  PopTable::findOne(['user_id'=> Yii::$app->user->identity->id, 'type' => PopTable::DISCLAIMER]);
        $this->_disclaimer = ($query == null || $query->seen_at < Yii::$app->formatter->asTimestamp('- 1 week'));
    }
    public function getDisclaimer(){
        if($this->_disclaimer == null){
            $this->setDisclaimer();
        }
        return $this->_disclaimer;
    }

/*    public function setDisclaimer(){
        $cache = Yii::$app->cache;
        $key  = $this->userCacheKey.'Disclaimer';
        $cache->set($key,$query,self::getNextRefreshTime());
    }
    public function getDisclaimer(){
        $cache = Yii::$app->cache;
        $key  = $this->userCacheKey.'Disclaimer';
        // $cache->delete($key) ;
        if(!$cache->exists($key) || empty($data  = $cache->get($key))){
           $data = $this->refreshUserData();
        }
        return $data;
    }*/

    protected function getSetting($key){
        $model = new UserSettings;
        $key = $model->getSettingId($key);
        return (empty($this->userSettings[$key])) ? null : $this->userSettings[$key]->data;
    }

    public function refreshUserData(){
        // Yii::trace('data refreshed','dev');
        $query = User::find()
            ->with([
                'socialAccounts'
                // 'settings',
                // 'messages',
            ])
            ->where(['user.id' => Yii::$app->user->identity->id])
            ->asArray()
            ->one();
            $cache = Yii::$app->cache;
            $key  = $this->userCacheKey;
            $cache->set($key,$query,self::getNextRefreshTime());
        return $query;
    }

    protected function getUserCacheKey(){
        return 'wfnmUser'.Yii::$app->user->identity->id.'UserDataStore';
    }

    public function getAvatar($type = ''){
        $url = $this->getAvatarUrl();
        return WfnmHelpers::img($url,['class'=>$type]);
    }

/*    public function getAvatarUrl(){
        if(empty($this->user['socialAccounts'])){
            $url = Url::to('@media/default-user.png', true);
        }else{
            $url = json_decode($this->user['socialAccounts'][0]['data'],true)['image'];
        }
        return $url;
    }*/

    protected function setBaseValues(){
        $this->user = $this->userData;
    }

    protected function getUserData(){
        $cache = Yii::$app->cache;
        $key  = $this->userCacheKey;
        // $cache->delete($key) ;
        if(!$cache->exists($key) || empty($data  = $cache->get($key))){
           $data = $this->refreshUserData();
        }
        return $data;
    }

    /**
     * @var int Seconds till next 5 min clock interval which the fire cache needs to be reset.
     */
    protected $_nextRefreshTime;

     /**
     *  Sets Next Time Refresh Variable for till the next 5 min clock interval which the fire cache needs to be reset.
     */
    protected function setNextRefreshTime(){
        $now = time();
        $this->_nextRefreshTime = (int) ceil($now/(300))*(300) - $now;
    }

    /**
     * Gets Next Time Refresh Variable for till the next 5 min clock interval which the fire cache needs to be reset.
     * @return int Seconds till next 5 min clock interval which the fire cache needs to be reset.
     */
    protected function getNextRefreshTime(){
        if(!isset($this->_nextRefreshTime)){
            $this->setNextRefreshTime();
        }
        return $this->_nextRefreshTime;
    }


}





?>
