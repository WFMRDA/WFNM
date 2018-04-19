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
    <div class="row">
        <div class="col-lg-5 col-md-8 col-sm-10 col-xs-12 col-centered">
            <h1><?= Html::encode($this->title) ?></h1>

            <p>Please choose your new password:</p>

            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                <?= $form->field($model, 'password')->widget(kartik\password\PasswordInput::classname(), [
                    'pluginOptions' => [
                        // 'showMeter' => true,
                        // 'toggleMask' => true
                        'verdictTitles' => [
                            0 => 'Not Set',
                            1 => 'Very Poor',
                            2 => 'Poor',
                            3 => 'Fair',
                            4 => 'Good',
                            5 => 'Excellent'
                        ],
                        'verdictClasses' => [
                            0 => 'text-muted',
                            1 => 'text-danger',
                            2 => 'text-warning',
                            3 => 'text-info',
                            4 => 'text-primary',
                            5 => 'text-success'
                        ],
                        'size'=>'md'
                    ]
                ]);?>
                <?= $form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::classname(), [
                    // configure additional widget properties here
                    'captchaAction' => ['/captcha']
                ]) ?>
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
