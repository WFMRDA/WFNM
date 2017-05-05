<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\cms\SysVariablesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sys-variables-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'enableFlashMessages') ?>

    <?= $form->field($model, 'enableRegistration') ?>

    <?= $form->field($model, 'enableGeneratingPassword') ?>

    <?= $form->field($model, 'enableConfirmation') ?>

    <?php // echo $form->field($model, 'enableUnconfirmedLogin') ?>

    <?php // echo $form->field($model, 'enablePasswordRecovery') ?>

    <?php // echo $form->field($model, 'emailChangeStrategy') ?>

    <?php // echo $form->field($model, 'rememberFor') ?>

    <?php // echo $form->field($model, 'confirmWithin') ?>

    <?php // echo $form->field($model, 'recoverWithin') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
