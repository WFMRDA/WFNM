<?php

namespace common\dataMigration;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use yii\db\Schema;
use yii\helpers\Console;

//Old Db
use common\dataMigration\app\User as AppUser;
use common\dataMigration\app\Profile as AppProfile;
use common\dataMigration\app\MyFires as AppMyFires;
use common\dataMigration\app\MyLocations as AppMyLocations;
use common\dataMigration\app\Messages as AppMessages;

//New Db
use common\models\user\User;
use common\models\user\Profile;
use common\models\myLocations\MyLocations;
use common\models\myFires\MyFires;
use common\models\messages\Messages;


class AppMigrate extends Model{

    public $errors = [];
    public $time;
    public $pages = null;
    public function init(){
        parent::init();
        $this->time = time();
    }

    public function getUsersDataProvider(){
        $dataProvider =  new ActiveDataProvider([
            'query' => AppUser::find(),
            'pagination' => [ 'pageSize' => 100],
        ]);
        $count = $dataProvider->totalCount;
        $pages = (ceil($dataProvider->totalCount/100));
        return [$dataProvider,$count,$pages];
    }
    public function getProfileDataProvider(){
        $dataProvider =  new ActiveDataProvider([
            'query' => AppProfile::find(),
            'pagination' => [ 'pageSize' => 100],
        ]);
        $count = $dataProvider->totalCount;
        $pages = (ceil($dataProvider->totalCount/100));
        return [$dataProvider,$count,$pages];
    }
    public function getMyLocationsDataProvider(){
        $dataProvider =  new ActiveDataProvider([
            'query' => AppMyLocations::find(),
            'pagination' => [ 'pageSize' => 100],
        ]);
        $count = $dataProvider->totalCount;
        $pages = (ceil($dataProvider->totalCount/100));
        return [$dataProvider,$count,$pages];
    }
    public function getMyFiresDataProvider(){
        $dataProvider =  new ActiveDataProvider([
            'query' => AppMyFires::find(),
            'pagination' => [ 'pageSize' => 100],
        ]);
        $count = $dataProvider->totalCount;
        $pages = (ceil($dataProvider->totalCount/100));
        return [$dataProvider,$count,$pages];
    }
    public function getMessagesDataProvider(){
        $dataProvider =  new ActiveDataProvider([
            'query' => AppMessages::find()->where(['>','created_at',Yii::$app->formatter->asTimestamp('-1 month')]),
            'pagination' => [ 'pageSize' => 100],
        ]);
        $count = $dataProvider->totalCount;
        $pages = (ceil($dataProvider->totalCount/100));
        return [$dataProvider,$count,$pages];
    }
    
    public function migrate(){
        $this->migrateUsers();
        $this->migrateProfile();
        // $this->migrateMyLocations();
        // $this->migrateFires();
        // $this->migrateMessages();
        return ['errors'=>$this->errors];
    }
    protected function migrateMessages(){
        // Messages
        Yii::$app->db->transaction(function($db) {
            $db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();
            $db->createCommand()->alterColumn ( Messages::tableName(), 'id', Schema::TYPE_INTEGER . ' NOT NULL')->execute();
        });
        list($dataProvider,$count,$pages) = $this->getMessagesDataProvider();
        $pages = ($this->pages == null)?$pages : $this->pages;
        Console::output('Starting Messages Migration. Total Pages = '. $pages);
        Console::startProgress(0, $pages);
        for ($i = 0; $i <= $pages; $i++) {
            $dataProvider->pagination->page = (int)$i;
            $dataProvider->refresh();
            $models = array_values($dataProvider->getModels());
            $keys = $dataProvider->getKeys();
            $rows = [];
            foreach ($models as $index => $model) {
                $key = $keys[$index];
                $obj = Messages::findOne($model->id);
                if($obj == null){
                    $obj = new Messages(['id'=>$model->id]);
                }
                $obj->load($model->attributes,'');
                if(!$obj->save()){
                    $this->errors['MessagesSave'][] = [
                        'errors' => $obj->errors,
                        'attribtutes' => $obj->attributes
                    ];
                    Console::output(VarDumper::dumpAsString($obj->errors,10,false));
                }
            }
            Console::updateProgress($i, $pages);
        }
        Console::endProgress();

        Yii::$app->db->transaction(function($db) {
           $db->createCommand()->alterColumn ( Messages::tableName(), 'id', Schema::TYPE_INTEGER . ' NOT NULL AUTO_INCREMENT')->execute();
           $db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();
        });

    }
    protected function migrateFires(){
        // MyFires
        Yii::$app->db->transaction(function($db) {
            $db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();
            $db->createCommand()->alterColumn ( MyFires::tableName(), 'id', Schema::TYPE_INTEGER . ' NOT NULL')->execute();
        });
        list($dataProvider,$count,$pages) = $this->getMyFiresDataProvider();
        $pages = ($this->pages == null)?$pages: $this->pages;
        Console::output('Starting My Fires Migration. Total Pages = '. $pages);
        Console::startProgress(0, $pages);
        for ($i = 0; $i <= $pages; $i++) {
            $dataProvider->pagination->page = (int)$i;
            $dataProvider->refresh();
            $models = array_values($dataProvider->getModels());
            $keys = $dataProvider->getKeys();
            $rows = [];
            foreach ($models as $index => $model) {
                $key = $keys[$index];
                $obj = MyFires::findOne($model->id);
                if($obj == null){
                    $obj = new MyFires(['id'=>$model->id]);
                }
                $obj->load($model->attributes,'');
                if(!$obj->save()){
                    $this->errors['MyFiresSave'][] = [
                        'errors' => $obj->errors,
                        'attribtutes' => $obj->attributes
                    ];
                    Console::output(VarDumper::dumpAsString($obj->errors,10,false));
                }
            }
            Console::updateProgress($i, $pages);
        }
        Console::endProgress();

        Yii::$app->db->transaction(function($db) {
           $db->createCommand()->alterColumn ( MyFires::tableName(), 'id', Schema::TYPE_INTEGER . ' NOT NULL AUTO_INCREMENT')->execute();
           $db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();
        });

    }
    protected function migrateMyLocations(){
        // MyLocations
        Yii::$app->db->transaction(function($db) {
            $db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();
            $db->createCommand()->alterColumn ( MyLocations::tableName(), 'id', Schema::TYPE_INTEGER . ' NOT NULL')->execute();
        });
        list($dataProvider,$count,$pages) = $this->getMyLocationsDataProvider();
        $pages = ($this->pages == null)?$pages: $this->pages;
        Console::output('Starting My Locations Migration. Total Pages = '. $pages);
        Console::startProgress(0, $pages);
        for ($i = 0; $i <= $pages; $i++) {
            $dataProvider->pagination->page = (int)$i;
            $dataProvider->refresh();
            $models = array_values($dataProvider->getModels());
            $keys = $dataProvider->getKeys();
            $rows = [];
            foreach ($models as $index => $model) {
                $key = $keys[$index];
                $obj = MyLocations::findOne($model->id);
                if($obj == null){
                    $obj = new MyLocations(['id'=>$model->id]);
                }
                $obj->load($model->attributes,'');
                if(!$obj->save()){
                    $this->errors['MyLocationsSave'][] = [
                        'errors' => $obj->errors,
                        'attribtutes' => $obj->attributes
                    ];
                    Console::output(VarDumper::dumpAsString($obj->errors,10,false));
                }
            }
            Console::updateProgress($i, $pages);
        }
        Console::endProgress();

        Yii::$app->db->transaction(function($db) {
           $db->createCommand()->alterColumn ( MyLocations::tableName(), 'id', Schema::TYPE_INTEGER . ' NOT NULL AUTO_INCREMENT')->execute();
           $db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();
        });

    }
    protected function migrateUsers(){
        // USERS
        Yii::$app->db->transaction(function($db) {
            $db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();
            $db->createCommand()->alterColumn ( User::tableName(), 'id', Schema::TYPE_INTEGER . ' NOT NULL')->execute();
        });
        list($dataProvider,$count,$pages) = $this->getUsersDataProvider();
        $pages = ($this->pages == null)?$pages: $this->pages;
        Console::output('Starting User Migration. Total Pages = '. $pages);
        Console::startProgress(0, $pages);
        for ($i = 0; $i <= $pages; $i++) {
            $dataProvider->pagination->page = (int)$i;
            $dataProvider->refresh();
            $models = array_values($dataProvider->getModels());
            $keys = $dataProvider->getKeys();
            $rows = [];
            foreach ($models as $index => $model) {
                $key = $keys[$index];
                $obj = User::findOne($model->id);
                if($obj == null){
                    $obj = new User(['id'=>$model->id]);
                }
                $obj->load($model->attributes,'');
                if(!$obj->save()){
                    $this->errors['UserSave'][] = [
                        'errors' => $obj->errors,
                        'attribtutes' => $obj->attributes
                    ];
                    Console::output(VarDumper::dumpAsString($obj->errors,10,false));
                }
            }
            Console::updateProgress($i, $pages);
        }
        Console::endProgress();

        Yii::$app->db->transaction(function($db) {
           $db->createCommand()->alterColumn ( User::tableName(), 'id', Schema::TYPE_INTEGER . ' NOT NULL AUTO_INCREMENT')->execute();
           $db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();
        });

    }

    protected function migrateProfile(){
        // Profile
        Yii::$app->db->transaction(function($db) {
            $db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();
        });

        list($dataProvider,$count,$pages) = $this->getProfileDataProvider();
        $pages = ($this->pages == null)?$pages: $this->pages;
        Console::output('Starting Profile Migration. Total Pages = '. $pages);
        Console::startProgress(0, $pages);
        for ($i = 0; $i <= $pages; $i++) {
            $dataProvider->pagination->page = (int)$i;
            $dataProvider->refresh();
            $models = array_values($dataProvider->getModels());
            $keys = $dataProvider->getKeys();
            $rows = [];
            foreach ($models as $index => $model) {
                $key = $keys[$index];
                $obj = Profile::findOne($model->user_id);
                if($obj == null){
                    $obj = new Profile(['user_id'=>$model->user_id]);
                }
                $obj->load($model->attributes,'');
                $obj->alert_dist = 25;
                if(!$obj->save()){
                    $this->errors['ProfileSave'][] = [
                        'errors' => $obj->errors,
                        'attribtutes' => $obj->attributes
                    ];
                    Console::output(VarDumper::dumpAsString($obj->errors,10,false));
                }
            }
            Console::updateProgress($i, $pages);
        }
        Console::endProgress();

        Yii::$app->db->transaction(function($db) {
           $db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();
        });

    }
}
