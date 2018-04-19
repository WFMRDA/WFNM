<?php

namespace common\dataMigration;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;

//Old Db
use common\dataMigration\app\User as AppUser;

//New Db
use common\models\user\User;
use ptech\pyrocms\models\user\Profile;
use common\models\user\UserSettings;
use common\models\myLocations\MyLocations;
use common\models\messages\Messages;


class AppMigrate extends Model{

    public function init(){
        parent::init();
    }
    public function getUsersDataProvider(){
        return new ActiveDataProvider([
            'query' => AppUser::find()->with('profile'),
            'pagination' => [ 'pageSize' => 100],
        ]);
    }
    public function getUsersSettingDataProvider(){
        return new ActiveDataProvider([
            'query' => AppUser::find()->with([
                'profile',
                'userSettings',
                'myLocations',
                'messages',
            ]),
            'pagination' => [ 'pageSize' => 100],
        ]);
    }

    public function migrate(){
        $array = array();
        $time = time();
        $errors = array();
        // USERS
        $usersDataProvider = $this->getUsersDataProvider();
        $count = $usersDataProvider->totalCount;
        $pages = (ceil($usersDataProvider->totalCount/100));
        // for ($i = 0; $i < $pages; $i++) {
        for ($i = 0; $i < 1; $i++) {
            $usersDataProvider->pagination->page = (int)$i;
            $usersDataProvider->refresh();
            $models = $usersDataProvider->getModels();
            $keys = $usersDataProvider->getKeys();
            $rows = [];
            foreach (array_values($models) as $index => $user) {
                $key = $keys[$index];
                //Do your Magic Her
                $modelArray = [
                    'username' => ArrayHelper::getValue($user,'username'),
                    'email' => ArrayHelper::getValue($user,'email'),
                    'status' => ArrayHelper::getValue($user,'status'),
                    'role' => ArrayHelper::getValue($user,'role'),
                    'auth_key' => ArrayHelper::getValue($user,'auth_key'),
                    'password_hash' => ArrayHelper::getValue($user,'password_hash'),
                    'confirmation_sent_at' => $time,
                    'confirmed_at' => ArrayHelper::getValue($user,'confirmed_at'),
                    'blocked_at' => ArrayHelper::getValue($user,'blocked_at'),
                    'registration_ip' => ArrayHelper::getValue($user,'registration_ip'),
                    'created_at' => ArrayHelper::getValue($user,'created_at'),
                    'updated_at' => ArrayHelper::getValue($user,'updated_at'),
                ];
                $profileArray = [
                    'first_name' => ArrayHelper::getValue($user,'profile.first_name'),
                    'middle_name' => ArrayHelper::getValue($user,'profile.middle_name'),
                    'last_name' => ArrayHelper::getValue($user,'profile.last_name'),
                    // 'birth_date' =>ArrayHelper::getValue($user,'profile.birth_date'),
                    'birth_month' =>ArrayHelper::getValue($user,'profile.birth_month'),
                    'birth_day' =>ArrayHelper::getValue($user,'profile.birth_day'),
                    'birth_year' =>ArrayHelper::getValue($user,'profile.birth_year'),
                    'gender' => ArrayHelper::getValue($user,'profile.gender'),
                    'alternate_email' => ArrayHelper::getValue($user,'profile.alternate_email'),
                    'website' => ArrayHelper::getValue($user,'profile.website'),
                    'street' => ArrayHelper::getValue($user,'profile.street'),
                    'city' => ArrayHelper::getValue($user,'profile.city'),
                    'state' => ArrayHelper::getValue($user,'profile.state'),
                    'zip' => ArrayHelper::getValue($user,'profile.zip'),
                    'phone' => ArrayHelper::getValue($user,'profile.phone'),
                ];
                $userSettingsArray = [
                    'key' => ArrayHelper::getValue($user,'userSettings.key'),
                    'data' => ArrayHelper::getValue($user,'userSettings.data'),
                ];
                $userSettingsArray = ArrayHelper::getValue($user,'userSettings');
                $myLocationsArray = ArrayHelper::getValue($user,'myLocations');
                $messagesArray = ArrayHelper::getValue($user,'messages');
                // Yii::trace($modelArray,'dev');
                // Yii::trace($userSettings,'dev');
                $userModel = User::find()->where(['email'=>$modelArray['email']])->one();

                // Yii::trace($userModel,'dev');
                //See if User Account Exist
                if($userModel == null){
                    $userModel = new User;
                    $userModel->generateAccessToken();
                    $userModel->generateConfirmationToken();
                    $userModel->load($modelArray,'');
                    if($userModel->save()){
                        //Save New Profile
                        $profile = $userModel->profile;
                        $profile->load($profileArray,'');
                        $profile->save();
                    }else{
                        $errors['userSave'][] = $userModel->errors;
                    }
                }else{
                    $userModel->load($modelArray,'');
                    if($userModel->save()){
                        $profile = $userModel->profile;
                        $profile->load($profileArray,'');
                        $profile->update();
                    }else{
                        $errors['userUpdate'][] = $userModel->errors;
                    }
                }

                if (empty($errors['userUpdate']) && empty($errors['userUpdate'])){
                    //User Settings
                    foreach ($userSettingsArray as $key => $modelObj) {
                        $model = $modelObj->attributes;
                        $model['user_id'] = $userModel->id;
                        $appModel = UserSettings::find()->where(['and',
                            ['user_id' => $model['user_id']],
                            ['key' => $model['key']]
                        ])->one();
                        if($appModel == null){
                            $appModel = new UserSettings;
                            $appModel->load($model,'');
                            Yii::trace($appModel->attributes,'dev');
                            if(!$appModel->save()){
                                $errors['UsersSettingsSave'][] = [
                                    'errors'=>$appModel->errors,
                                    'attribtutes'=>$appModel->attributes
                                ];
                            }
                        }else{
                            $appModel->load($model,'');
                            if(!$appModel->save()){
                                $errors['UsersSettingsUpdate'][] = [
                                    'errors'=>$appModel->errors,
                                    'attribtutes'=>$appModel->attributes
                                ];
                            }
                        }
                    }
                    //MyLocations
                    foreach ($myLocationsArray as $key => $modelObj) {
                        $model = $modelObj->attributes;
                        $model['user_id'] = $userModel->id;
                        $appModel = MyLocations::find()->where(['and',
                            ['user_id' => $model['user_id']],
                            ['place_id' => $model['place_id']]
                        ])->one();
                        if($appModel == null){
                            $appModel = new MyLocations;
                            $appModel->load($model,'');
                            if(!$appModel->save()){
                                $errors['myLocationsSave'][] = [
                                    'errors'=>$appModel->errors,
                                    'attribtutes'=>$appModel->attributes
                                ];
                            }
                        }else{
                            $appModel->load($model,'');
                            if(!$appModel->save()){
                                $errors['myLocationsUpdate'][] = [
                                    'errors'=>$appModel->errors,
                                    'attribtutes'=>$appModel->attributes
                                ];
                            }
                        }
                    }
                    //Messages
                    foreach ($messagesArray as $key => $modelObj) {
                        $model = $modelObj->attributes;
                        $model['user_id'] = $userModel->id;
                        $appModel = Messages::find()->where(['and',
                            ['user_id' => $model['user_id']],
                            ['subject' => $model['subject']]
                        ])->one();
                        if($appModel == null){
                            $appModel = new Messages;
                            $appModel->load($model,'');
                            if(!$appModel->save()){
                                $errors['messagesSave'][] = [
                                    'errors'=>$appModel->errors,
                                    'attribtutes'=>$appModel->attributes
                                ];
                            }
                        }else{
                            $appModel->load($model,'');
                            if(!$appModel->save()){
                                $errors['messagesUpdate'][] = [
                                    'errors'=>$appModel->errors,
                                    'attribtutes'=>$appModel->attributes
                                ];
                            }
                        }
                    }
                }

            }
        }
        //     if (empty($errors['userUpdate']) && empty($errors['userUpdate'])){
        //         //Set Plans
        //         $model = $user['plan'];
        //         $model['user_id'] = $userModel->id;
        //         // echo $model['user_id'];
        //         $appModel = Plan::findOne(['user_id'=>$model['user_id']]);
        //         if($appModel == null){
        //             $appModel = new Plan;
        //             $appModel->load($model,'');
        //             if(!$appModel->save()){
        //                 $errors['PlanSave'][] = [
        //                     'errors'=>$appModel->errors,
        //                     'attribtutes'=>$appModel->attributes
        //                 ];
        //             }
        //         }else{
        //             $appModel->load($model,'');
        //             if(!$appModel->save()){
        //                 $errors['PlanUpdate'][] = [
        //                     'errors'=>$appModel->errors,
        //                     'attribtutes'=>$appModel->attributes
        //                 ];
        //             }
        //         }
        //
        //         // Set Registred Devices
        //         foreach ($user['regDevices'] as $key => $device) {
        //             unset($device['id']);
        //             $device['user_id'] = $userModel->id;
        //             $appModel = RegDevices::find()
        //                 ->andWhere([
        //                     'and',
        //                     ['user_id' => $device['user_id']],
        //                     ['ip' => $device['ip']]
        //                 ])
        //                 ->one();
        //             if($appModel == null){
        //                 $appModel = new RegDevices;
        //                 $appModel->load($device,'');
        //                 if(!$appModel->save()){
        //                     $errors['RegDevicesSave'][] = [
        //                         'errors'=>$appModel->errors,
        //                         'attribtutes'=>$appModel->attributes
        //                     ];
        //                 }
        //             }else{
        //                 $appModel->load($device,'');
        //                 if(!$appModel->save()){
        //                     $errors['RegDevicesUpdate'][] = [
        //                         'errors'=>$appModel->errors,
        //                         'attribtutes'=>$appModel->attributes
        //                     ];
        //                 }
        //             }
        //         }
        //     }
        // }

        return ['data'=>$array,'errors'=>$errors];
    }

}
