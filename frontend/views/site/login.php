<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5 col-md-8 col-sm-10 col-xs-12">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div style="color:#999;margin:1em 0">
                    If you forgot your password you can <?= Html::a('Reset It', ['site/request-password-reset']) ?>.
                </div>
                <div style="color:#999;margin:1em 0">
                    Didn't get Confirmation Notice? <?= Html::a('Resend Confirmation', ['site/resend-confirmation']) ?>.
                </div>
                <div style="color:#999;margin:1em 0">
                    <?= Html::a('Sign Up', ['site/signup']) ?>.
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-xs-12">
            <hr>
            <h3 class='text-center'>-OR-</h3>
            <?php echo \common\modules\User\widgets\Eauth::widget(['action' => 'site/login']); ?>
        </div>
    </div>
</div>
