<?php

namespace common\modules\User\helpers;

use common\modules\User\helpers\UserHelpers;
use Yii;
use yii\base\Object;

class Permissions extends Object
{
	const ADMIN_ROLE = 'Admin';
	const SUPERADMIN_ROLE = 'SuperUser';
	const STANDARD_ROLE = 'Standard';

	public static function isSuperAdminUser($role = false){
		if(!$role){
			$role = Yii::$app->user->identity->role;
		}
		return  (UserHelpers::getUserRoleId(self::SUPERADMIN_ROLE) == $role);
	}

	public static function isAdminUser($role = false){
		if(!$role){
			$role = Yii::$app->user->identity->role;
		}
		return (UserHelpers::getUserRoleId(self::ADMIN_ROLE)==$role);
	}


	public static  function isMinRequiredRole($minRole ,$role = false){
		if(!$role){
			$role = Yii::$app->user->identity->role;
		}
		return ( $role >= UserHelpers::getUserRoleId($minRole) );
	}

	public static function isSameuser($id){
		return ($id == Yii::$app->user->identity->id);
	}


}
