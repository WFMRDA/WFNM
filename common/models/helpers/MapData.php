<?php

namespace common\models\helpers;

use Yii;
use common\models\irwin\IrwinDb;
use common\models\helpers\WfnmHelpers;
use yii\helpers\ArrayHelper;
use common\models\helpers\GeoJson;
use common\models\vulcan\DisplayFireSizePrefs;
use common\models\vulcan\DisplayFireStatusPrefs;
use yii\helpers\Url;
use yii\base\Model;
use yii\httpclient\Client;
use yii\base\InvalidParamException;
use yii\helpers\VarDumper;
use yii\data\ArrayDataProvider;

class MapData extends Model{
    const DEV = 'dev';
    const PROD = 'prod';
    const TEST = 'test';
    const EMERGING = 'emerging';
    const NEW0 = 'new';
    const CONTROLLED = 'controlled';
    const CONTAINED = 'contained';
    const ACTIVE = 'active';
    const OUT = 'out';

    public $baseUrl;
    public $username;
    public $password;
    public $environment = self::DEV;
    public $credentials = [];
    public $wfnmCacheKey = 'wfnmFiresDataStore';
    public $wfnmFireInfoCacheKey = 'wfnmFireInfoDataStore';
    public $wfnmEmergingFireInfoCacheKey = 'wfnmEmergingFireInfoDataStore';
    public $wfnmNewFireInfoCacheKey = 'wfnmNewFireInfoDataStore';
    public $sitReportCacheKey = 'wfnmSitReportCacheKey';
    public $fireArrayCacheKey = 'fireArrayDataStore';
    public $myFiresArrayCacheKey = 'myFiresArrayDataStore';
    public $plLevelCacheKey = 'plLevelArrayDataStore';
    protected $_fireIncidents;
    protected $_emergingFires;
    protected $_newFires;
    private $_authKey;
    protected $_client;

    /**
     * @var Logged In User Data Includes Latitude and Longitude
     */
    public $userData = [
        'longitude'=> null,
        'latitude'=> null,
    ];


    public function init(){
        $this->setCredentials();
        if(!isset($this->password)){
            throw new InvalidParamException('Password not specified');
        }
        if(!isset($this->baseUrl)){
            throw new InvalidParamException('BaseUrl not specified');
        }
        if(!isset($this->username)){
            throw new InvalidParamException('Username not specified');
        }
        if(!isset($this->environment)){
            throw new InvalidParamException('Environment not specified');
        }
		$this->_client =  new Client(['baseUrl' => $this->baseUrl]);
        parent::init();
    }

    protected function setCredentials(){
        if(!isset($this->password)){
            $this->password = ArrayHelper::getValue($this->credentials,$this->environment.'.password');
        }
        if(!isset($this->username)){
            $this->username = ArrayHelper::getValue($this->credentials,$this->environment.'.username');
        }
        if(!isset($this->baseUrl)){
            $this->baseUrl = ArrayHelper::getValue($this->credentials,$this->environment.'.baseUrl');
        }
    }


    protected function setAuthKey(){
        $this->_authKey = base64_encode($this->username.':'.$this->password);
    }
    protected function getAuthKey(){
        if(!isset($this->_authKey)){
            $this->setAuthKey();
        }
        return $this->_authKey;
    }
    /*********************
    *   A => NEW
    *   B => EMERGING
    *   C => CONTAINED
    *   D => CONTROLLED
    *   E => ACTIVE
    *   F => OUT
    *********************/
    protected function getFireArrayCacheKey(){
        return $this->fireArrayCacheKey;
    }

    protected function refreshFireArray(){
        $cache = Yii::$app->cache;
        $key  = $this->getFireArrayCacheKey();
        $firedb = $this->getWfnmData();
        $dataSet = $this->processFires($firedb);
        // Yii::trace(VarDumper::dumpAsString($firedb,10),'dev');
        $cache->set($key, $dataSet,$this->nextRefreshTime);
        return $dataSet;
    }

    public function getFireArray($type = null){
        //Check Cache for WFNM GeoJson, If unavailable set Cache and return values
        $cache = Yii::$app->cache;
        $key  = $this->getFireArrayCacheKey();
        // Yii::trace($key,'dev');
        // $cache->delete($key) ;
        if(!$cache->exists($key) || empty($data  = $cache->get($key))){
           $data = $this->refreshFireArray();
        }
        // Yii::trace(VarDumper::dumpAsString($this->_fireIncidents,10),'dev');
        return ($type !== null) ? ArrayHelper::getValue($data,$type,[]) : $data;
    }

    public function processFires($array){
        $incident = $dataSet = [];
        foreach ($array as $key => $incident) {
            if($incident['dailyAcres'] == null){
                $incident['dailyAcres'] = 0;
            }else{
                $incident['dailyAcres'] = (float)$incident['dailyAcres'];
            }
            if($incident['fireClassId'] == 'A'){
                $dataSet[self::NEW0][] = $incident;
            }elseif($incident['fireClassId'] == 'B'){
                $dataSet[self::EMERGING][] = $incident;
            }elseif($incident['fireClassId'] == 'C'){
                $dataSet[self::CONTAINED][] = $incident;
            }elseif($incident['fireClassId'] == 'D'){
                $dataSet[self::CONTROLLED][] =$incident ;
            }elseif($incident['fireClassId'] == 'E'){
                $dataSet[self::ACTIVE][] = $incident;
            }elseif($incident['fireClassId'] == 'F'){
                $dataSet[self::OUT][] =$incident ;
            }
        }
        return $dataSet;
    }

    public function setEmergingFires(){
        $this->_emergingFires = $this->getFireArray(self::EMERGING);
    }

    /**
     * Gets WFNM Emerging fire dataset from cache.
     * @return json WFNM Emerging Fire dataset
     */
    public function getEmergingFiresDataProvider(){
        if(!isset($this->_emergingFires)){
            $this->setEmergingFires();
        }
        // Yii::trace(ArrayHelper::getColumn($this->_emergingFires, 'dailyAcres'),'dev');
        // ArrayHelper::multisort($this->_emergingFires, ['dailyAcres', 'incidentName'], [SORT_DESC, SORT_ASC]);
        // Yii::trace(count(ArrayHelper::getColumn($this->_emergingFires, 'incidentName')),'dev');
        // Yii::trace($this->_emergingFires,'dev');
        return new ArrayDataProvider([
            'allModels' => $this->_emergingFires,
            'pagination' => false,
        ]);
    }

    public function setNewFires(){
        $this->_newFires = $this->getFireArray(self::NEW0);
    }

    /**
     * Gets WFNM Emerging fire dataset from cache.
     * @return json WFNM Emerging Fire dataset
     */
    public function getNewFiresDataProvider(){
        if(!isset($this->_newFires)){
            $this->setNewFires();
        }
        // Yii::trace(ArrayHelper::getColumn($this->_emergingFires, 'dailyAcres'),'dev');
        // ArrayHelper::multisort($this->_newFires, ['dailyAcres', 'incidentName'], [SORT_DESC, SORT_ASC]);
        // Yii::trace(ArrayHelper::getColumn($this->_newFires, 'fireClassId'),'dev');
        // Yii::trace(ArrayHelper::map($this->getFireArray(self::NEW0),'irwinID','fireClassId'),'dev'); //array_keys($this->getFireArray()
        // Yii::trace(ArrayHelper::map($this->_newFires,'irwinID','fireClassId'),'dev'); //array_keys($this->getFireArray()
        // Yii::trace($this->getFireInfo('1CB03BD1-5E0A-4EFE-9BAF-F56D3EF5DA9A'),'dev'); //array_keys($this->getFireArray()
        // Yii::trace($this->getWfnmData(),'dev'); //array_keys($this->getFireArray()
        
        return new ArrayDataProvider([
            'allModels' => $this->_newFires,
            'pagination' => false,
        ]);
    }
    /**
     * Gets WFNM fire dataset from cache.
     * @return json WFNM Fire dataset
     */
    public function getWfnmData(){
        //Check Cache for WFNM GeoJson, If unavailable set Cache and return values
        $cache = Yii::$app->cache;
        $key  = $this->wfnmCacheKey;
        // Yii::trace($this->nextRefreshTime,'dev');
        // $cache->delete($key) ;
        if(!$cache->exists($key) || empty($data  = $cache->get($key))){
           $data = $this->refreshWfnmData();
        }
        return $data;
    }
    /**
     * Search WFNM fire dataset from cache.
     * @return json WFNM Fire dataset
     */
    public function searchWfnmData($params){
        // Yii::trace($params,'dev');
        $dataset = $this->getWfnmData();
        return $dataset;
    }

    /**
     * Refreshes WFNM fire dataset.
     * @see getWfnmData()
     * @return json WFNM Fire dataset
     */
    public function refreshWfnmData(){
        try {
            $updatesResponse = $this->_client->createRequest()
                ->setUrl('map-fires')
                ->setMethod('post')
                ->addHeaders(['Authorization' => 'Basic '.$this->getAuthKey()])
                ->send();

            $cache = Yii::$app->cache;
            $key  = $this->wfnmCacheKey;
            // Yii::trace(VarDumper::dumpAsString($updatesResponse,10),'dev');
            // Yii::trace(VarDumper::dumpAsString($updatesResponse->data,10),'dev');
            if ($updatesResponse->isOk) {
                $data =  $updatesResponse->data;
                $cache->set($key, $data, $this->nextRefreshTime);
            }else{
                //Log Error.
                // $this->errorlog[] = $updatesResponse->data;
                $data = [];
            }
        }catch (\yii\httpclient\Exception $e) {
            //Log Error.
            // $this->errorlog[] = $e->getMessage();
            $data = [];
        }
        return $data;
    }

    protected function getFireSizeClass($acres){
        switch (true) {
            case $acres <= 99: # if true, enter; if false, skips;
                $val = 1;
            break;
            case $acres <= 999: # if true, enter; if false, skips;
                $val = 2;
            break;
            case $acres <= 9999: # if true, enter; if false, skips;
                $val = 3;
            break;
            case $acres <= 99999: # if true, enter; if false, skips;
                $val = 4;
            break;
            case $acres >= 100000: # if true, enter; if false, skips;
                $val = 5;
            break;
            default:
                $val = 1;
            break;
        }
        return $val;
    }
    /**
     * Returns GeoJson Object of all fires in the WFNM ruleset.
     * @return GeoJson Object Cache Key
     */
    public function getWfnmGeoJsonLayer(){
        $firedb = $this->getWfnmData();
        $userSettings = Yii::$app->systemData->mapLayers;
        $fireClasses = ArrayHelper::getValue($userSettings,'fireClass');
        $fireSizes = ArrayHelper::getValue($userSettings,'fireSize');
        $classesHash = array_flip($fireClasses);
        $sizesHash = array_flip($fireSizes);

        // Yii::trace ( $userSettings, 'dev' );
        // Yii::trace ( $classesHash, 'dev' );
        // Yii::trace ( $sizesHash, 'dev' );
        // $dataSet = $this->processFires($firedb);
        // Yii::trace ( $userSettings, 'dev' );
        // Yii::trace ( $hash, 'dev' );
        // return '{}';
        if(!empty($firedb)){
            $json = new GeoJson;
            // Yii::trace ( $fireSizeArray, 'dev' );
            foreach ($firedb as $key => $row){
                $acres = ($row['dailyAcres'] == null) ? 0: (float)$row['dailyAcres'];
                // Yii::trace (isset($hash[$row['fireClassId']]), 'dev' );
                if(!isset($classesHash[$row['fireClassId']]) || !isset($sizesHash[$this->getFireSizeClass($acres)])){
                    continue;
                }
                $options = [
                    'fireType' => $row['fireClassId'],
                    'acres' => $acres,
                    // 'name' => $row['incidentName'],
                    // 'irwinId' => $row['irwinID'],
                ];
                $json->addNode(
                    $row['irwinID'],
                    $row['incidentName'],
                    $row['pooLatitude'],
                    $row['pooLongitude'],
                    $options
                );
            }
            $export =  $json->exportGeoJson();
            // Yii::trace($export,'dev');
            return $export;
        }
    }//END buildWfnmIrwinLayer Layer

    /**
     * Returns Irwin Fire Object in Array from Cache.
     * @param string Irwin ID
     * @return string Cache Key
     */
    protected function getFireInfoKey($fid){
        return $this->wfnmFireInfoCacheKey.'_'.str_replace('-', '', $fid);
    }

    /**
     * Returns Irwin Fire Object in Array from Cache.
     * @param Irwin ID
     * @return array from Irwin Object
     */
    public function getFireInfo($fid){
        //Check Cache for WFNM GeoJson, If unavailable set Cache and return values
        $cache = Yii::$app->cache;
        $key  = $this->getFireInfoKey($fid);
        // Yii::trace($key,'dev');
        // $cache->delete($key) ;
        if(!$cache->exists($key) || empty($data  = $cache->get($key))){
           $data = $this->refreshFireInfo($fid);
        }
        // Yii::trace($data,'dev');
        return $data;
    }

    /**
     * Refreshes Irwin Fire Object Array In Data Cache and returns it for use.
     * @see getFireInfo()
     * @return array from Irwin Object
     */
    public function refreshFireInfo($fid){
        try {
            $updatesResponse = $this->_client->createRequest()
                ->setUrl('fire-info')
                ->setMethod('post')
                ->addHeaders(['Authorization' => 'Basic '.$this->getAuthKey()])
                ->setData([
                    'fid' => $fid,
                ])
                ->send();
            $cache = Yii::$app->cache;
            $key  = $this->getFireInfoKey($fid);
            // Yii::trace(VarDumper::dumpAsString($updatesResponse,10),'dev');
            // Yii::trace(VarDumper::dumpAsString($updatesResponse->data,10),'dev');
            if ($updatesResponse->isOk) {
                $cache->set($key, $updatesResponse->data,$this->nextRefreshTime);
                $data = $updatesResponse->data;
            }else{
                //Log Error.
                // $this->errorlog[] = $updatesResponse->data;
                $data = [];
            }
        }catch (\yii\httpclient\Exception $e) {
            //Log Error.
            // $this->errorlog[] = $e->getMessage();
            $data = [];
        }
        return $data;
    }

    /**
     * Returns Sit Report Object in Array from Cache.
     * @param string Irwin ID
     * @return string Cache Key
     */
    protected function getSitReportKey(){
        return $this->sitReportCacheKey;
    }

    /**
     * Returns Sit Report Object in Array from Cache.
     * @return array from Sit Report Object
     */
    public function getSitReportInfo(){
        //Check Cache for WFNM GeoJson, If unavailable set Cache and return values
        $cache = Yii::$app->cache;
        $key  = $this->getSitReportKey();
        // Yii::trace($key,'dev');
        // $cache->delete($key) ;
        if(!$cache->exists($key) || empty($data  = $cache->get($key))){
           $data = $this->refreshSitReportInfo();
        }
        // Yii::trace($data,'dev');
        return $data;
    }

    /**
     * Refreshes Sit Report Object Array In Data Cache and returns it for use.
     * @see getFireInfo()
     * @return array from Irwin Object
     */
    public function refreshSitReportInfo(){
        try {
            $updatesResponse = $this->_client->createRequest()
                ->setUrl('sitreport')
                ->setMethod('get')
                ->addHeaders(['Authorization' => 'Basic '.$this->getAuthKey()])
                ->send();
            $cache = Yii::$app->cache;
            $key  = $this->getSitReportKey();
            // Yii::trace(VarDumper::dumpAsString($updatesResponse,10),'dev');
            // Yii::trace(VarDumper::dumpAsString($updatesResponse->data,10),'dev');
            if ($updatesResponse->isOk) {
                $cache->set($key, $updatesResponse->data,$this->nextRefreshTime);
                $data = $updatesResponse->data;
            }else{
                //Log Error.
                // $this->errorlog[] = $updatesResponse->data;
                $data = [];
            }
        }catch (\yii\httpclient\Exception $e) {
            //Log Error.
            // $this->errorlog[] = $e->getMessage();
            $data = [];
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
    /**
     * @var int Seconds till next 24hr  clock interval which the fire cache needs to be reset.
     */
    protected $_nextDayRefreshTime;

     /**
     *  Sets Next Time Refresh Variable for till the next 5 min clock interval which the fire cache needs to be reset.
     */
    protected function setNextDayRefreshTime(){
        $now = time();
        if(date('H') < 10){
            $trigger_date_time = date("Y-m-d 10:00:00",$now);
        }else{
            $trigger_date_time = date("Y-m-d 10:00:00",$now + 60*60*24);
        }
        // Yii::trace(Yii::$app->formatter->asTimestamp($trigger_date_time) - $now,'dev');
        $this->_nextDayRefreshTime = (int) Yii::$app->formatter->asTimestamp($trigger_date_time) - $now;
    }

    /**
     * Gets Next Time Refresh Variable for till the next 5 min clock interval which the fire cache needs to be reset.
     * @return int Seconds till next 5 min clock interval which the fire cache needs to be reset.
     */
    protected function getNextDayRefreshTime(){
        if(!isset($this->_nextDayRefreshTime)){
            $this->setNextDayRefreshTime();
        }
        return $this->_nextDayRefreshTime;
    }

    public function getFiresNearUserLocation(){
        //Check to see if user location has been set
        // Yii::trace($this->userData,'dev');
        if( empty($this->userData['longitude']) || empty($this->userData['latitude'])){
            //User Location Not Set .
            $data = [];
        }else{
            //Check Cache for WFNM GeoJson, If unavailable set Cache and return values
            $cache = Yii::$app->cache;
            $key  = $this->getMyLocationFireArrayCacheKey();
            // Yii::trace($key,'dev');
            // $cache->delete($key) ;
            if(!$cache->exists($key) || empty($data  = $cache->get($key))){
               $data = $this->refreshMyLocationsFireInfo();
            }

            // Yii::trace($data,'dev');
        }
        return $data;
    }

    protected function getMyLocationFireArrayCacheKey(){
        $lat = abs(round($this->userData['longitude'],2));
        $lng = abs(round($this->userData['latitude'],2));
        $placeId = '_'.$lng.'_'.$lat;
        return $this->myFiresArrayCacheKey.$placeId;
    }

    public function refreshMyLocationsFireInfo(){
        try {
            $updatesResponse = $this->_client->createRequest()
                ->setUrl('location-based-fires')
                ->setMethod('post')
                ->addHeaders(['Authorization' => 'Basic '.$this->getAuthKey()])
                ->setData([
                    'lat' => $this->userData['latitude'],
                    'lon' => $this->userData['longitude'],
                    'dist' => 100,
                ])
                ->send();
            $cache = Yii::$app->cache;
            $key  = $this->getMyLocationFireArrayCacheKey();
            if ($updatesResponse->isOk) {
                // Yii::trace($updatesResponse->data,'dev');
                $dataSet = $this->processFires($updatesResponse->data);
                $cache->set($key, $dataSet,$this->nextRefreshTime);
                $data = $dataSet;
            }else{
                //Log Error.
                $data = [];
            }
        }catch (\yii\httpclient\Exception $e) {
            //Log Error.

            // Yii::trace($e->getMessage(),'dev');
            $data = [];
        }
        return $data;
    }

    public function getPrepardnessLevel($type){
        $cache = Yii::$app->cache;
        $key  = $this->plLevelCacheKey;
        // $cache->delete($key) ;
        if(!$cache->exists($key) || empty($data  = $cache->get($key))){
           $data = $this->refreshPrepardnessLevel();
        }
        return ArrayHelper::getValue($data,$type.'.gacc_pl','');
    }
    public function refreshPrepardnessLevel(){
        try {
            $updatesResponse = $this->_client->createRequest()
                ->setUrl('prepardness-level')
                ->setMethod('get')
                ->addHeaders(['Authorization' => 'Basic '.$this->getAuthKey()])
                ->send();
            $cache = Yii::$app->cache;
            $key  = $this->plLevelCacheKey;
            if ($updatesResponse->isOk) {
                // Yii::trace($updatesResponse->data,'dev');
                $data = $updatesResponse->data;
                $cache->set($key, $data,$this->nextDayRefreshTime);
            }else{
                //Log Error.
                $data = [];
            }
        }catch (\yii\httpclient\Exception $e) {
            //Log Error.
            // Yii::trace($e->getMessage(),'dev');
            $data = [];
        }
        return $data;
    }
}
