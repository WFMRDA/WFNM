<?php

namespace common\modules\User\helpers;

use Yii;
use common\modules\User\models\Gender;
use yii\helpers\ArrayHelper;
use common\modules\User\models\Status;
use common\modules\User\models\Role;
use common\modules\User\models\User;
use yii\base\Object;

class UserHelpers extends Object{
	/**
    * get list of Genders for dropdown
    */
	public static function getGenderList(){
        $droptions = Gender::find()->asArray()->all();
        return ArrayHelper::map($droptions, 'id', 'name');
    }

	public static function getStatusList(){
        $droptions = Status::find()->asArray()->all();
        return ArrayHelper::map($droptions, 'id', 'name');
    }

    public static function getActiveList(){
        $droptions = [
            ['id'=>true,'name'=>'Active'],
            ['id'=>false,'name'=>'Inactive']
        ];
        return ArrayHelper::map($droptions, 'id', 'name');
    }
    public static function getUserIdFromEmail($email){
        $query =  User::find()
            ->where(['email' => $email])
            ->one();
        return $query->id;
    }

    public static function getStatusId($name){
    	$query = Status::find()->where(['name'=>$name])->asArray()->one();
       	return (!empty($query['id']))?$query['id']:null;
    }
    public static function getStatusName($id){
       	$query = Status::find()->where(['id'=>$id])->asArray()->one();
       	return (!empty($query['name']))?$query['name']:null;
         // \Yii::trace ($query, 'dev' );
    }

    public static function getRoleId($name){
        $query = Role::find()->where(['name'=>$name])->asArray()->one();
        return (!empty($query['id']))?$query['id']:null;
    }

    public static function getRoleName($id){
        $query = Role::find()->where(['id'=>$id])->asArray()->one();
        return (!empty($query['name']))?$query['name']:null;
    }

    public static function getUserRoleId($id){
        $query = User::find()->where(['id'=>$id])->asArray()->one();
        return (!empty($query['role']))?$query['role']:null;
    }
    public static function createProfileName($user)
    {
        $firstName = (!empty($user['profile']['first_name']))?$user['profile']['first_name']:'';
        $lastName = (!empty($user['profile']['last_name']))?$user['profile']['last_name']:'';
        return $firstName . ' ' . $lastName;
    }
    public static function getUserRolesList($id = false){
        if(!$id){
            //If User Didn't pass the user ID, Get it from the Current Logged in user
            $role_id = Yii::$app->user->identity->role;
        }
        $droptions = Role::find()
            ->where(['<=', 'id', $role_id])
            ->asArray()->all();
        return ArrayHelper::map($droptions, 'id', 'name');
    }

    public static function getFullRolesList(){
        $droptions = Role::find()
            ->asArray()->all();
        return ArrayHelper::map($droptions, 'id', 'name');
    }

    public static function get_states_list(){

        $array = [
            ['abbr' => 'AL', 'name' =>'ALABAMA'],
            ['abbr' => 'AK', 'name' =>'ALASKA'],
            ['abbr' => 'AS', 'name' =>'AMERICAN SAMOA'],
            ['abbr' => 'AZ', 'name' =>'ARIZONA'],
            ['abbr' => 'AR', 'name' =>'ARKANSAS'],
            ['abbr' => 'CA', 'name' =>'CALIFORNIA'],
            ['abbr' => 'CO', 'name' =>'COLORADO'],
            ['abbr' => 'CT', 'name' =>'CONNECTICUT'],
            ['abbr' => 'DE', 'name' =>'DELAWARE'],
            ['abbr' => 'DC', 'name' =>'DISTRICT OF COLUMBIA'],
            ['abbr' => 'FM', 'name' =>'FEDERATED STATES OF MICRONESIA'],
            ['abbr' => 'FL', 'name' =>'FLORIDA'],
            ['abbr' => 'GA', 'name' =>'GEORGIA'],
            ['abbr' => 'GU', 'name' =>'GUAM GU'],
            ['abbr' => 'HI', 'name' =>'HAWAII'],
            ['abbr' => 'ID', 'name' =>'IDAHO'],
            ['abbr' => 'IL', 'name' =>'ILLINOIS'],
            ['abbr' => 'IN', 'name' =>'INDIANA'],
            ['abbr' => 'IA', 'name' =>'IOWA'],
            ['abbr' => 'KS', 'name' =>'KANSAS'],
            ['abbr' => 'KY', 'name' =>'KENTUCKY'],
            ['abbr' => 'LA', 'name' =>'LOUISIANA'],
            ['abbr' => 'ME', 'name' =>'MAINE'],
            ['abbr' => 'MH', 'name' =>'MARSHALL ISLANDS'],
            ['abbr' => 'MD', 'name' =>'MARYLAND'],
            ['abbr' => 'MA', 'name' =>'MASSACHUSETTS'],
            ['abbr' => 'MI', 'name' =>'MICHIGAN'],
            ['abbr' => 'MN', 'name' =>'MINNESOTA'],
            ['abbr' => 'MS', 'name' =>'MISSISSIPPI'],
            ['abbr' => 'MO', 'name' =>'MISSOURI'],
            ['abbr' => 'MT', 'name' =>'MONTANA'],
            ['abbr' => 'NE', 'name' =>'NEBRASKA'],
            ['abbr' => 'NV', 'name' =>'NEVADA'],
            ['abbr' => 'NH', 'name' =>'NEW HAMPSHIRE'],
            ['abbr' => 'NJ', 'name' =>'NEW JERSEY'],
            ['abbr' => 'NM', 'name' =>'NEW MEXICO'],
            ['abbr' => 'NY', 'name' =>'NEW YORK'],
            ['abbr' => 'NC', 'name' =>'NORTH CAROLINA'],
            ['abbr' => 'ND', 'name' =>'NORTH DAKOTA'],
            ['abbr' => 'MP', 'name' =>'NORTHERN MARIANA ISLANDS'],
            ['abbr' => 'OH', 'name' =>'OHIO'],
            ['abbr' => 'OK', 'name' =>'OKLAHOMA'],
            ['abbr' => 'OR', 'name' =>'OREGON'],
            ['abbr' => 'PW', 'name' =>'PALAU'],
            ['abbr' => 'PA', 'name' =>'PENNSYLVANIA'],
            ['abbr' => 'PR', 'name' =>'PUERTO RICO'],
            ['abbr' => 'RI', 'name' =>'RHODE ISLAND'],
            ['abbr' => 'SC', 'name' =>'SOUTH CAROLINA'],
            ['abbr' => 'SD', 'name' =>'SOUTH DAKOTA'],
            ['abbr' => 'TN', 'name' =>'TENNESSEE'],
            ['abbr' => 'TX', 'name' =>'TEXAS'],
            ['abbr' => 'UT', 'name' =>'UTAH'],
            ['abbr' => 'VT', 'name' =>'VERMONT'],
            ['abbr' => 'VI', 'name' =>'VIRGIN ISLANDS'],
            ['abbr' => 'VA', 'name' =>'VIRGINIA'],
            ['abbr' => 'WA', 'name' =>'WASHINGTON'],
            ['abbr' => 'WV', 'name' =>'WEST VIRGINIA'],
            ['abbr' => 'WI', 'name' =>'WISCONSIN'],
            ['abbr' => 'WY', 'name' =>'WYOMING'],
            ['abbr' => 'AE', 'name' =>'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST'],
            ['abbr' => 'AA', 'name' =>'ARMED FORCES AMERICA (EXCEPT CANADA)'],
            ['abbr' => 'AP', 'name' =>'ARMED FORCES PACIFIC'],
        ];

        return $array;
    }//End Get States List

}



?>
