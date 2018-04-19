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
use yii\widgets\ActiveForm;

/*
 * @var $this  yii\web\View
 * @var $form  yii\widgets\ActiveForm
 * @var $model ptech\pyrocms\models\user\SettingsForm
 */

$this->title = 'Account settings';
$this->params['breadcrumbs'][] = $this->title;
?>
<?=ptech\pyrocms\widgets\Alert::widget();?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('_menu') ?>
    </div>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode($this->title) ?>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id'          => 'account-form',
                    'options'     => ['class' => 'form-horizontal'],
                    'fieldConfig' => [
                        'template'     => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
                        'labelOptions' => ['class' => 'col-lg-3 control-label'],
                    ],
                    'enableAjaxValidation'   => true,
                    'enableClientValidation' => false,
                ]); ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'current_password')->passwordInput() ?>

                <hr />

                <?= $form->field($model, 'new_password')->widget(kartik\password\PasswordInput::classname(), [
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

                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-9">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-block btn-success']) ?><br>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
