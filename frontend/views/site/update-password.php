<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Update password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please choose your new password:</p>

    <div class="row">
        <div class="col-lg-5 col-md-8 col-sm-10 col-xs-12">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                <?=  $form->field($model, 'password')->widget(kartik\password\PasswordInput::classname(), [
                    'pluginOptions' => [
                        // 'showMeter' => true,
                        // 'toggleMask' => true
                    ]
                ]);?>
                 <?= $form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::classname(), [
                    // configure additional widget properties here
                ]) ?>
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
