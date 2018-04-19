<?php

/*
 * This file is part of the ptech project.
 *
 * (c) ptech project <http://github.com/ptech>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use ptech\pyrocms\widgets\Connect;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

ptech\pyrocms\assets\ConnectAsset::register($this);

$this->title = 'Sign in';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.col-centered{
    float: none;
    margin: 0 auto;
}
</style>

<?=ptech\pyrocms\widgets\Alert::widget();?>

<div class="row">
    <div class="col-lg-6 col-md-8 col-sm-10 col-centered">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username', ['inputOptions' => ['autofocus' => true, 'class' => 'form-control', 'tabindex' => '1']]) ?>

                <?= $form->field($model, 'password', ['inputOptions' => ['class' => 'form-control', 'tabindex' => '2']])->passwordInput()->label('Password' . ($module->enablePasswordRecovery && !$admin ? ' (' . Html::a( 'Forgot password?', ['/user/password-reset'], ['tabindex' => '5']) . ')' : '')) ?>

                <?= $form->field($model, 'rememberMe')->checkbox(['tabindex' => '4']) ?>

                <?= Html::submitButton('Sign in', ['class' => 'btn btn-primary btn-block', 'tabindex' => '3']) ?>

                <?php ActiveForm::end(); ?>
            </div>
            <div class='panel-footer'>
                <?php if (!$admin): ?>
                    <p class="text-center">
                        <?= Html::a('Didn\'t receive confirmation message?', ['/user/resend-confirmation']) ?>
                    </p>
                <?php endif ?>
                <?php if ($module->enableRegistration && !$admin): ?>
                    <p class="text-center">
                        <?= Html::a('Don\'t have an account? Sign up!', ['/user/register']) ?>
                    </p>
                <?php endif ?>
            </div>
        </div>
        <div class="col-xs-12">
            <hr>
            <?= !$admin ? \ptech\pyrocms\widgets\Eauth::widget(['action' => '/user/security/login']) : ''; ?>
        </div>

    </div>
</div>
