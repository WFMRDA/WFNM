<?php

namespace common\modules\User\models;


use Yii;
// use yii\base\Object;
use yii\base\Object;
use common\modules\User\models\SocialAccounts;

class Social extends Object {

	public function getUserFbId($userId = false){
		//If User Didn't pass the user ID, Get it from the Current Logged in user
		if(!$userId){
			$userId = Yii::$app->user->identity->id;
		}
		$fb = SocialAccount::find()
			->select('client_id')
			->where(['user_id' => $userId , 'provider' => 'facebook'])
			->asArray()
			->one();
		$fb_Id = $fb['client_id'];
		return (!empty($fb_Id))?$fb_Id:false;
	}

	public function getUserFbUrl($id){
        if($fbID = $this->getUserFbId($id)){
            $url =  '//graph.facebook.com/' . $fbID . '/picture?type=large';
        }else{
        	$url = false;
        }
        return $url;
	}

	public function getUserGoogleUrl($id){

		if($google = $this->findGoogleModel($id)){
			$data = json_decode($google['data'],true);
			$str = $data['image']['url'];
         	$str = explode('?', $str);
         	$url = $str[0];
        }else{
        	$url = false;
        }
        return $url;
	}

	public function findGoogleModel($userId = false){
		//If User Didn't pass the user ID, Get it from the Current Logged in user
		if(!$userId){
			$userId = Yii::$app->user->identity->id;
		}

		if(!$account = SocialAccount::find()
			->where(['user_id' => $userId , 'provider' => 'google'])
			->exists()){
			return false;
		}

		$account = SocialAccount::find()
			->where(['user_id' => $userId , 'provider' => 'google'])
			->one();

		return $account;
	}


    public function getAvatarUrl($id = false){
        if(!$id){
            //If User Didn't pass the user ID, Get it from the Current Logged in user
            $id = Yii::$app->user->identity->id;
        }
        //See if User Has Facebook Connected
        $fb = $this->getUserFbUrl($id);

        //See if User Has Twitter Connected
        $yh = $this->getUserYhUrl($id);

        //See if Use Has Google
        $gp = $this->getUserGoogleUrl($id);

        //Get User Avatar
        if($fb){
            //Use Facebook Avatar if Available
            $url = $fb;
        }else if($yh) {
            //Use Yahoo Avatar if Available
            $url = $yh;
        }else if($gp) {
            //Use Google Plus Avatar if Available
            $url = $gp;
        }else{
            //Use Default Avatar
            $url = Yii::$app->urlManagerFrontEnd->createUrl(['img/default-user.png']);
        }
        return $url;
    }
}
