<?php

namespace common\models\system;

use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use common\models\user\User;
use common\models\user\Profile;
use common\models\helpers\GPS;
use common\models\myLocations\MyLocations;
use common\models\myFires\MyFires;
use common\models\messages\Messages;
use common\models\helpers\MailerProcessor;
use common\models\helpers\WfnmHelpers;
use ptech\pyrocms\models\helpers\Html2Text;
use yii\data\ActiveDataProvider;
use common\models\system\Session;

class System extends Model{
    public $alertDistance = 25;
    protected $_monitoringKeys = [
        ['key'=> 'incidentName','label'=>'Incident Name'],
        ['key'=> 'fireCause', 'label'=>'Fire Cause'],
        ['key'=> 'complexParentIrwinId','label'=>'Complexed'],
        ['key'=> 'dailyAcres','label'=>'Daily Acres'],
        ['key'=> 'totalIncidentPersonnel','label'=>'Incident Personnel'],
        ['key'=> 'fireMgmtComplexity','label'=>'Fire Complexity'],
        ['key'=> 'estimatedContainmentDate','label'=>'Estimated Containement Date'],
        ['key'=> 'percentContained','label'=>'Percent Contained'],
        ['key'=> 'containmentDateTime','label'=>'Containment Date'],
        ['key'=> 'controlDateTime','label'=>'Controlled Date'],
        ['key'=> 'fireOutDateTime','label'=>'Fire Out Date'],
        ['key'=> 'incidentShortDescription','label'=>'Incident Short Description'],
        ['key'=> 'significantEvents','label'=>'Significant Events'],
        ['key'=> 'primaryFuelModel','label'=>'Primary Fuel Model'],
        ['key'=> 'weatherConcerns','label'=>'Weather Concerns'],
        ['key'=> 'plannedActions','label'=>'Planned Actions'],
    ];
    protected $mapData;
    protected $_emailList;
    protected $_fireDb;
    protected $_locations;
    protected $_fires;
    protected $_mappedMonitoringKeys;


    public function init(){
        parent::init();
        $this->mapData = Yii::createObject(Yii::$app->params['mapData']);
    }

    public function findNewAlerts(){
        $alerts = $this->();
        $updates = $this->findFireUpdates();
        // Yii::trace($alerts,'dev');
        // Yii::trace($updates,'dev');
        return [
            'fireCount' => count($this->fireDb),
            'montitoredFires' => count($this->fires),
            'montitoredLocations' => count($this->locations),
        ];
    }

    protected function findUndiscoveredAlerts(){
        $query = MyLocations::find()
            ->joinWith('user')
            ->andWhere(['user.status' => \ptech\pyrocms\models\helpers\UserHelpers::getStatusId(User::STATUS_ACTIVE)]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pageSize' => 100],
        ]);
        $count = $dataProvider->totalCount;
        $pages = (ceil($dataProvider->totalCount/100));
        for ($i = 0; $i < $pages; $i++) {
            $dataProvider->pagination->page = (int)$i;
            $dataProvider->refresh();
            $models = $dataProvider->getModels();
            $keys = $dataProvider->getKeys();
            $rows = [];
            foreach (array_values($models) as $index => $model) {
                $key = $keys[$index];
                $response = $this->findFires($model);
            }
        }
    }

    protected function findFires($location){
        $fireDb = $this->fireDb;
        $alertDistance = $location->user->profile->alert_dist;
        if( !is_numeric($alertDistance)){
            $alertDistance = 25;
        }
        foreach ($fireDb as $key => $fire) {
            /*
                We No Longer Send Alerts for Complexes due to partner system bad practices
                We can revisit this later.

                Only Send Alerts for A B C class fires
            */
            if(
                $fire['fireClassId'] != 'A' ||
                $fire['fireClassId'] != 'B' ||
                $fire['fireClassId'] != 'C'
            ){
                // Yii::trace($fire,'dev');
                continue;
            }
            $distance = GPS::distance($location->latitude, $location->longitude, $fire['pooLatitude'], $fire['pooLongitude']);
            if($distance <= $alertDistance){
                //User need to be alerted of new fire
                //Let's make sure they haven't been alerted already First
                $this->storeFireAlert($location,$fire,$distance);
            }
        }
    }

    public function cleanSessions(){
        return Session::deleteAll(['<','expire',time()]);
    }

    public function sendNewEmails(){
        // Messages::updateAll(['sent_at' => null]);
        // return [];
        $models = $this->buildEmailUserArray();
        // Yii::trace(count($models),'dev');
        $messages = [];
        $sent = 0;
        $failed = 0;
        foreach ($models as $userID) {
            $alerts = [];
            $updates = [];
            $query = Messages::find()
                ->joinWith(['user','profile'])
                ->andWhere([
                    'and',
                    ['messages.user_id' => $userID],
                    ['messages.sent_at' => NULL],
                    ['messages.seen_at' => NULL],
                    ['>=', 'messages.created_at', Yii::$app->formatter->asTimestamp('-24 hours')]
                ])
                ->orderBy(['created_at' => SORT_DESC])
                ->all();
            if(!empty($query)){
                foreach ($query as $key => $message) {
                    if($message->type ==  Messages::UPDATES || $message->type ==  Messages::FINAL_MESSAGE){
                        $updates[] = WfnmHelpers::getUpdatesLine($message);
                    }elseif ($message->type == Messages::ALERTS ) {
                        $alerts[] = WfnmHelpers::getAlertsLine($message);
                    }
                }
                $user = $query[0]->user;
                $html = $this->buildEmail($user,$updates,$alerts);
                $html2text = new Html2Text($html);
                $textHtml = $html2text->getText();
                $message = Yii::$app->mailer->compose()
                    ->setFrom(Yii::$app->params['adminEmail'])
                    ->setTo($user->email)
                    ->setSubject($user->fullName.' You Have New Information In WIldfires Near Me')
                    ->setTextBody($textHtml)
                    ->setHtmlBody($html);


                try {
                    if($message->send()){
                        $sent++;
                        Messages::updateAll(['sent_at' => time()], 'user_id = '.$userID);
                    }else{
                        $failed++;
                        $userMessages = Messages::find()->andWhere(['user_id'=>$userID])->all();
                        foreach ($userMessages as $row) {
                            $count = $row->send_tries++;
                            $row->updateAttributes(['send_tries'=>$count]);
                        }
                    }
                } catch (ErrorException $e) {
                    $userMessages = Messages::find()->andWhere(['user_id'=>$userID])->all();
                    foreach ($userMessages as $row) {
                        $count = $row->send_tries++;
                        $row->updateAttributes(['send_tries'=>$count]);
                    }
                }
            }
        }
        return ['sent'=>$sent,'failed'=>$failed];
    }

    protected function buildEmail($user,$updatesArray = [],$alertsArray = []){
        $alerts = (empty($alertsArray))?'All Clear. No New Alerts. Please check your fire Updates':implode(' ', $alertsArray);
        $updates = (empty($updatesArray))?'All Clear. No New Alerts. Please check your fire Alerts':implode(' ', $updatesArray);
        $processor  = new MailerProcessor();
        $messageTemplate = Yii::$app->view->render('@common/mail/notifications');
        $html =  $processor->processHtml($user,$messageTemplate,[
            'ALERTS' => $alerts,
            'UPDATES' => $updates,
            'PREF_LIST' => Yii::$app->urlManager->createAbsoluteUrl(['/user/settings/profile']),
        ]);
        return $html;
    }

    protected function buildEmailUserArray(){
        // $models = $this->getEmailList();
        $models = Messages::find()
            ->select(['messages.user_id'])
            ->joinWith(['user','profile'])
            ->andWhere([
                'and',
                ['user.status' => \ptech\pyrocms\models\helpers\UserHelpers::getStatusId(User::STATUS_ACTIVE)],
                ['>=', 'profile.email_prefs', Profile::ALERTS_EMAILS_ONLY],
                ['messages.sent_at' => NULL],
                ['messages.seen_at' => NULL],
                ['>=', 'messages.created_at', Yii::$app->formatter->asTimestamp('-12 hours')]
            ])
            ->distinct()
            ->asArray()
            ->column();
        return $models;
           /* $count = Messages::find()
            ->select(['messages.user_id'])
            ->joinWith(['user','profile'])
            ->andWhere([
                'and',
                ['user.status' => \ptech\pyrocms\models\helpers\UserHelpers::getStatusId(User::STATUS_ACTIVE)],
                ['>=', 'profile.email_prefs', Profile::ALERTS_EMAILS_ONLY],
                ['messages.sent_at' => NULL],
                ['messages.seen_at' => NULL],
                ['>=', 'messages.created_at', Yii::$app->formatter->asTimestamp('-24 hours')]
            ])
            ->asArray()
            ->all();
            $c = array_unique(ArrayHelper::getColumn($count,'user_id'));

        return ['column'=>count($models),'count'=>count($c)];*/
    }

    public function setEmailList(){
        $this->_emailList = Messages::find()
        ->joinWith(['user','profile'])
        ->andWhere([
            'and',
            ['user.status' => \ptech\pyrocms\models\helpers\UserHelpers::getStatusId(User::STATUS_ACTIVE)],
            ['>=', 'profile.email_prefs', Profile::ALERTS_EMAILS_ONLY],
            ['messages.sent_at' => NULL],
            ['messages.seen_at' => NULL],
            ['>=', 'messages.created_at', Yii::$app->formatter->asTimestamp('-24 hours')]
        ])
        ->orderBy(['created_at' => SORT_DESC])
        ->all();
    }

    public function getEmailList(){
        if(!isset($this->_emailList)){
            $this->setEmailList();
        }
        return $this->_emailList;
    }



    public function setFireDb(){
        $this->_fireDb =  ArrayHelper::index($this->mapData->getWfnmData(), 'irwinID');
    }

    public function getFireDb(){
        if(!isset($this->_fireDb)){
            $this->setFireDb();
        }
        return $this->_fireDb;
    }

    protected function setLocations(){
        $this->_locations = MyLocations::find()
            ->joinWith('user')
            ->andWhere(['user.status' => \ptech\pyrocms\models\helpers\UserHelpers::getStatusId(User::STATUS_ACTIVE)])
            ->all();
    }
    public function getLocations(){
        if(!isset($this->_locations)){
            $this->setLocations();
        }
        return $this->_locations;
    }

    protected function setFires(){
        $this->_fires = MyFires::find()
            ->joinWith('user')
            ->andWhere(['user.status' => \ptech\pyrocms\models\helpers\UserHelpers::getStatusId(User::STATUS_ACTIVE)])
            ->all();
    }
    public function getFires(){
        if(!isset($this->_fires)){
            $this->setFires();
        }
        return $this->_fires;
    }



    protected function findFireUpdates(){
        $query = MyFires::find()
            ->joinWith('user')
            ->andWhere(['user.status' => \ptech\pyrocms\models\helpers\UserHelpers::getStatusId(User::STATUS_ACTIVE)]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pageSize' => 100],
        ]);
        $count = $dataProvider->totalCount;
        $pages = (ceil($dataProvider->totalCount/100));
        for ($i = 0; $i < $pages; $i++) {
            $dataProvider->pagination->page = (int)$i;
            $dataProvider->refresh();
            $models = $dataProvider->getModels();
            $keys = $dataProvider->getKeys();
            $rows = [];
            foreach (array_values($models) as $index => $model) {
                $key = $keys[$index];
                $response = $this->processUpdate($model);
            }
        }
    }


    protected function processUpdate($fire){
        // Yii::trace($fire,'dev');
        //See if Fire is still in WFNM
        // Yii::trace(array_keys($this->fireDb),'dev');
        // Yii::trace(isset($this->fireDb[$fire->irwinID]),'dev');
        // Yii::trace($fire->attributes,'dev');
        // Yii::trace(isset($this->fireDb[$fire->irwinID]),'dev');
        // Yii::trace(Messages::UPDATES ,'dev');
        // Yii::trace($this->storedMessages ,'dev');
        if(isset($this->fireDb[$fire->irwinID]) ){
            // Yii::trace('Fire In','dev');
            // Yii::trace($fire->attributes,'dev');
            //Fire is still in WFNM
            //See if this is the users first updates
            // Yii::trace($this->storedMessages[$fire->user_id][Messages::UPDATES],'dev');
            // Yii::trace($this->storedMessages[$fire->user_id][Messages::UPDATES][$fire['irwinID']],'dev');
            if($this->storeMessageExist($fire->user_id, Messages::UPDATES ,$fire['irwinID'])){
                $messages = Messages::find()
                ->andWhere([
                    'and',
                    [
                        'type'      =>  Messages::UPDATES,
                        'user_id'   =>  $fire->user_id,
                        'irwinID'   =>  $fire['irwinID']
                    ]
                ])
                ->orderBy(['created_at' => SORT_DESC])
                ->limit(1)
                ->one();
                //We need to compare the last fire Update Data with the Current Data.
                $data = json_decode($messages->data,true,512,JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
                $this->storeUpdatedFireMonitoringAlert($fire,$data);
            }else{
                //Log Initial Update
                $this->storeInitialFireMonitoringAlert($fire);
            }
        }else{
            //Fire is no long in WFNM
            // Yii::trace('Fire Out','dev');
            // Yii::trace($fire->attributes,'dev');
            if(!$this->storeMessageExist($fire->user_id, Messages::FINAL_MESSAGE ,$fire['irwinID'])){
                $this->storeExpiredFireMonitoringAlert($fire);
            }
        }
    }

    protected function storeExpiredFireMonitoringAlert($fire){
        $subject =  'Your Fire '. $fire->name .' has been removed from our System.';
        $body = 'Your Fire '. $fire->name .' has been removed from System. You can still view this record by following the link';
        $data = [];
        $message = Yii::createObject([
            'class'=> Messages::className(),
            'user_id' => $fire->user_id,
            'type' => Messages::FINAL_MESSAGE,
            'subject' => $subject,
            'email' => $fire->user->email,
            'body' => $body,
            'irwinID' => $fire->irwinID,
            'data' => json_encode($data),
            'sent_at' => null,
            'seen_at' => null,
            'send_tries' => '0',
        ]);
        // Yii::trace($message->attributes,'dev');
        if(!$message->save()){
            throw new InvalidParamException($message->errors);
        }
    }

    protected function setMonitoringKeys(){
        $this->_mappedMonitoringKeys = ArrayHelper::map($this->_monitoringKeys, 'key', 'label');
    }

    public function getMonitoringKeys(){
        if(!isset($this->_mappedMonitoringKeys)){
            $this->setMonitoringKeys();
        }
        return $this->_mappedMonitoringKeys;
    }

    protected function buildComparableArray($array){
        $keys = $this->monitoringKeys;
        foreach ($array as $key => $element) {
            if(!isset($keys[$key])){
                unset($array[$key]);
            }
        }
        return $array;
    }
    protected function formatUpdateMessage($array = []){
        $message = '';
        if(!empty($array)){
            foreach ($array['new'] as $key => $value) {
                $label = ArrayHelper::getValue($this->monitoringKeys,$key,'');
                $message .= WfnmHelpers::tag('p','<b>'.$label .'</b>: '. $value).'</br>';
            }
        }
        return $message;
    }
    protected function storeUpdatedFireMonitoringAlert($fire,$data){
        $oldData = ArrayHelper::getValue($data,'baseData',[]);
        $newData = ArrayHelper::getValue( $this->fireDb,$fire->irwinID,[]);
        $oldCompareData = $this->buildComparableArray($oldData);
        $newCompareData = $this->buildComparableArray($newData);
        //See if Data is the same
        if($oldCompareData == $newCompareData){
            return true;
        }
        //Data is different
        $arrayDiff = array_replace_recursive(
            WfnmHelpers::arrayDiffRecursive($oldCompareData, $newCompareData),
            WfnmHelpers::arrayDiffRecursive($newCompareData, $oldCompareData, true)
        );
        // Yii::trace($arrayDiff,'dev');
        $subject = 'Information for fire '. $fire->name.' has been updated';
        $body = $this->formatUpdateMessage($arrayDiff);
        // Yii::trace($body,'dev');
        $data = [
            'baseData' => $newData,
            'oldData' => $oldData,
            'arrayDiff' => $arrayDiff,
        ];
        $message = Yii::createObject([
            'class'=> Messages::className(),
            'user_id' => $fire->user_id,
            'type' => Messages::UPDATES,
            'subject' => $subject,
            'email' => $fire->user->email,
            'body' => $body,
            'irwinID' => $fire->irwinID,
            'data' => json_encode($data),
            'sent_at' => null,
            'seen_at' => null,
            'send_tries' => '0',
        ]);
        // Yii::trace($message->attributes,'dev');
        if(!$message->save()){
            throw new InvalidParamException($message->errors);
        }
    }


    protected function storeInitialFireMonitoringAlert($fire){
        $subject = 'You\'re Now Following ' . $fire->name . ' fire';
        $body = 'You\'re now following the ' . $fire->name . ' fire. We\'ll update you of any significant changes';
        $data = [
            'baseData' => $this->fireDb[$fire->irwinID],
            'oldData' => null,
        ];

        $message = Yii::createObject([
            'class'=> Messages::className(),
            'user_id' => $fire->user_id,
            'type' => Messages::UPDATES,
            'subject' => $subject,
            'email' => $fire->user->email,
            'body' => $body,
            'irwinID' => $fire->irwinID,
            'data' => json_encode($data),
            'sent_at' => null,
            'seen_at' => null,
            'send_tries' => '0',
        ]);
        // Yii::trace($message->attributes,'dev');
        if(!$message->save()){
            throw new InvalidParamException($message->errors);
        }
    }

    protected function storeMessageExist ($userId, $type,$irwinID){
        return Messages::find()
           ->andWhere(['user_id' => $userId])
           ->andWhere(['type' => $type])
           ->andWhere(['irwinID' => $irwinID])
           ->exists();
    }

    protected function storeFireAlert($location,$fire,$distance){
        if( !$this->storeMessageExist($location->user_id, Messages::ALERTS ,$fire['irwinID']) ){
            $subject = $fire['incidentName'] . ' Fire is within '. round($distance,2).'mi of '. $location->address;
            $body = $subject;
            $message = Yii::createObject([
                'class'=> Messages::className(),
                'user_id' => $location->user_id,
                'type' => Messages::ALERTS,
                'subject' => $subject,
                'email' => $location->user->email,
                'body' => $body,
                'irwinID' => $fire['irwinID'],
                'data' => json_encode($fire),
                'sent_at' => null,
                'seen_at' => null,
                'send_tries' => '0',
            ]);
            if(!$message->save()){
                throw new InvalidParamException($message->errors);
            }
        }
    }

}
