<?php

/*
 * This file is part of the ptech project.
 *
 * (c) ptech project <http://github.com/ptech>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var yii\widgets\ActiveForm 		$form
 * @var common\modules\User\models\User 	$user
 */
?>

<?= $form->field($user, 'email')->textInput(['maxlength' => 255]) ?>
<?= $form->field($user, 'username')->textInput(['maxlength' => 255]) ?>
<?= $form->field($user, 'firstName')->textInput(['maxlength' => 255]) ?>
<?= $form->field($user, 'lastName')->textInput(['maxlength' => 255]) ?>
<?= $form->field($user, 'role')->dropDownList($listData['userRoles'],['prompt'=>'Select User Role']); ?>
<?= $form->field($user, 'password')->passwordInput() ?>
