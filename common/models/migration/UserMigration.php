<?php

namespace common\models\migration;

use Yii;

use yii\base\Object;
use yii\helpers\Json;
use common\models\user\User;
use common\models\user\Profile;
use common\models\myLocations\MyLocations;

class UserMigration extends Object{


	public function getOldUsers(){
		$json = Json::decode(file_get_contents(Yii::getAlias('@common/data/userData.json')),true);
		return $json;
	}
	public function migrateData(){
		$i = 0;
		$Users = $this->oldUsers;
		$total = count($Users);
		foreach ($Users as $key => $userData) {
			//See If Email Account Already Exists
			if(($user = User::find()->andWhere(['email'=>$userData['email']])->one()) != null){
				$user->delete();
			}
			$user = Yii::createObject([
                'class'=> User::className(),
                'username' => (string)$userData['username'],
	            'email' =>(string) $userData['email'],
	            'auth_key' => (string)$userData['auth_key'],
	            'password_hash' =>(string) $userData['password_hash'],
	            'confirmation_sent_at' => time(),
	            'confirmed_at' => time(),
                'userProfile'=>[
                    'first_name' => (string)$userData['profile']['first_name'],
                    'last_name' => (string)$userData['profile']['last_name'],
	                'phone' => (string)$userData['profile']['phone'],
                ],
            ]);
			$user->generateConfirmationToken();
            $user->generateAuthKey();
            $user->generateAccessToken();
            if($user->save()){
            	$i++;
                foreach ($userData['vulcanMonitoring'] as $locationkey => $location) {
					$loc = Yii::createObject([
		                'class'=> MyLocations::className(),
		                'user_id' => (int)$user->id,
			            'address' =>(string) $location['place_name'],
			            'place_id' =>(string) $location['place_id'],
			            'latitude' =>(string) $location['latitude'],
			            'longitude' =>(string) $location['longitude'],
            		]);
                }
                if(!$loc->save()){
            		Yii::trace($loc->errors,'dev');
            	};
            }else{
            	Yii::trace($user->errors,'dev');
            }
		}
		return ['total'=>$total,'saved'=>$i];
	}
}