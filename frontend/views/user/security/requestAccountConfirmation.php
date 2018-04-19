<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Resend Confirmation';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
.col-centered{
    float: none;
    margin: 0 auto;
}
</style>

<div class="site-request-password-reset">

    <div class="row">
        <div class="col-lg-6 col-md-8 col-sm-10 col-centered">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="panel-body">

                    <p>Please fill out your email. A link to reset password will be sent there.</p>
                    <?php $form = ActiveForm::begin(['id' => 'request-confirmation-reset-form']); ?>

                        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                        <?= $form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::classname(), [
                            'captchaAction' => '/user/captcha',
                        ]) ?>

                        <div class="form-group">
                            <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
        </div>
        </div>
    </div>
</div>
