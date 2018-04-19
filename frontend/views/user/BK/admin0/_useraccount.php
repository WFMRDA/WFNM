<?php

use ptech\pyrocms\models\helpers\UserHelpers;
/*
 * This file is part of the ptech project.
 *
 * (c) ptech project <http://github.com/ptech>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var yii\widgets\ActiveForm  $form
 * @var ptech\pyrocms\models\user\User 	$user
 */
?>

<?= $form->field($user, 'email')->textInput(['readonly' => true,'disabled' => true]) ?>
<?= $form->field($user, 'username')->textInput(['readonly' => true,'disabled' => true]) ?>
<?php 
	if(UserHelpers::isMinRequiredRole($user->role)){
		echo $form->field($user, 'role')->label('User Role')->dropDownList($listData['userRoles'],['prompt'=>'Select User Role']);
	}else{
		$user->tmp_Display = UserHelpers::getRoleName($user->role);
		echo $form->field($user, 'tmp_Display')->label('User Role')->textInput(['readonly' => true,'disabled' => true]);
	}
?>

