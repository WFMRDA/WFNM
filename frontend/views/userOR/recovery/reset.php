<?php

/*
 * This file is part of the ptech project.
 *
 * (c) ptech project <http://github.com/ptech>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/*
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var common\modules\User\models\RecoveryForm $model
 */

$this->title = 'Reset your password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please choose your new password:</p>

    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id'                     => 'password-recovery-form',
                ]); ?>

                <?= $form->field($model, 'password')->widget(kartik\password\PasswordInput::classname(), [
                    'pluginOptions' => [
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
                    'captchaAction' => ['/site/captcha']
                ]) ?>

                <?= Html::submitButton('Finish', ['class' => 'btn btn-success btn-block']) ?><br>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

