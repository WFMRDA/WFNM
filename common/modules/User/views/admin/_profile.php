<?php

/*
 * This file is part of the ptech project
 *
 * (c) ptech project <http://github.com/ptech>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
common\modules\User\assets\BirthdayPicker\assets\BirthdayPickerAsset::register($this);
/**
 * @var yii\web\View 					$this
 * @var common\modules\User\models\User 		$user
 * @var common\modules\User\models\Profile 	$profile
 */

?>

<?php $this->beginContent('@ptech/user/views/admin/update.php', ['user' => $user]) ?>

    
    <?php $form = ActiveForm::begin([
        'id' => 'profile-form',
        'options' => ['class' => 'form-horizontal'],
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
            'labelOptions' => ['class' => 'col-lg-3 control-label'],
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-9',
            ],
        ],
        'enableAjaxValidation'   => true,
        'enableClientValidation' => false,
        'validateOnBlur'         => false,
    ]); ?>

    <?= $form->field($profile, 'first_name')->textInput(['readonly' => true]) ?>

    <?= $form->field($profile, 'middle_name')->textInput(['readonly' => true]) ?>

    <?= $form->field($profile, 'last_name')->textInput(['readonly' => true]) ?>

    <?= $form->field($profile, 'birth_date')->textInput(['readonly' => true]) ?>

    <?=$form->field($profile, 'gender')->textInput(['readonly' => true]) ?>

    <?= $form->field($profile, 'alternate_email')->textInput(['readonly' => true]) ?>

    <?= $form->field($profile, 'website')->textInput(['readonly' => true]) ?>

    <?= $form->field($profile, 'street')->textInput(['readonly' => true]) ?>

    <?= $form->field($profile, 'city')->textInput(['readonly' => true]) ?>

    <?= $form->field($profile, 'state')->textInput(['readonly' => true]) ?>

    <?= $form->field($profile, 'zip')->textInput(['readonly' => true]) ?>

    <?= $form->field($profile, 'phone')->textInput(['readonly' => true]) ?>

    <?= $form->field($profile, 'bio')->textInput(['readonly' => true]) ?>

<?php ActiveForm::end(); ?>

<?php $this->endContent() ?>
