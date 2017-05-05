<?php

/*
 * This file is part of the ptech project.
 *
 * (c) ptech project <http://github.com/ptech>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use common\modules\User\widgets\Connect;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


common\modules\User\assets\ConnectAsset::register($this);

/**
 * @var yii\web\View                   $this
 * @var common\modules\User\models\LoginForm $model
 * @var common\modules\User\Module           $module
 */

$this->title = 'Sign in';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id'                     => 'login-form',
                    'enableAjaxValidation'   => true,
                    // 'enableClientValidation' => false,
                    // 'validateOnBlur'         => false,
                    // 'validateOnType'         => false,
                    // 'validateOnChange'       => false,
                ]) ?>

                <?= $form->field($model, 'login', ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']]) ?>

                <?= $form->field($model, 'password', ['inputOptions' => ['class' => 'form-control', 'tabindex' => '2']])->passwordInput()->label('Password' . ($module->enablePasswordRecovery ? ' (' . Html::a( 'Forgot password?', ['recovery/request'], ['tabindex' => '5']) . ')' : '')) ?>

                <?= $form->field($model, 'rememberMe')->checkbox(['tabindex' => '4']) ?>

                <?= Html::submitButton('Sign in', ['class' => 'btn btn-primary btn-block', 'tabindex' => '3']) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <?php if ($module->enableConfirmation): ?>
            <p class="text-center">
                <?= Html::a('Didn\'t receive confirmation message?', ['registration/resend']) ?>
            </p>
        <?php endif ?>
        <?php if ($module->enableRegistration): ?>
            <p class="text-center">
                <?= Html::a('Don\'t have an account? Sign up!', ['registration/register']) ?>
            </p>
        <?php endif ?>
    </div>
</div>
