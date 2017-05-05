<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\cms\SysVariables */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sys-variables-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'enableFlashMessages')->radioList([true => 'True', false => 'False'])?>

    <?= $form->field($model, 'enableRegistration')->radioList([true => 'True', false => 'False'])?>

    <?= $form->field($model, 'enableGeneratingPassword')->radioList([true => 'True', false => 'False'])?>

    <?= $form->field($model, 'enableConfirmation')->radioList([true => 'True', false => 'False'])?>

    <?= $form->field($model, 'enableUnconfirmedLogin')->radioList([true => 'True', false => 'False'])?>

    <?= $form->field($model, 'enablePasswordRecovery')->radioList([true => 'True', false => 'False'])?>

    <?= $form->field($model, 'emailChangeStrategy',[
            'template' => '{label} <div class="no-wrap col-sm-12">{input}{error}{hint}</div>'
        ])->radioList([
        0 => "Email is changed right after user enter's new email address", 
        1 => "Email is changed after user clicks confirmation link sent to his new email address.",
        2 => "Email is changed after user clicks both confirmation links sent to his old and new email addresses"])?>

    <?= $form->field($model, 'rememberFor')->label('The time you want the user will be remembered without asking for credentials. <small>*Calculated in Seconds</small>')->input('number') ?>

    <?= $form->field($model, 'confirmWithin')->label('The time before a confirmation token becomes invalid.<small>*Calculated in Seconds</small>')->input('number') ?>

    <?= $form->field($model, 'recoverWithin')->label('The time before a recovery token becomes invalid  <small>*Calculated in Seconds</small>')->input('number') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
