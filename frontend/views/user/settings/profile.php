<?php

/*
 * This file is part of the ptech project.
 *
 * (c) ptech project <http://github.com/ptech>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use ptech\pyrocms\models\user\Profile;


/*
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var ptech\pyrocms\models\user\Profile $model
 */

$this->title = 'Profile settings';
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
                    'id' => 'profile-form',
                    'options' => ['class' => 'form-horizontal'],
                    'layout' => 'horizontal',
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
                        'labelOptions' => ['class' => 'col-lg-3 control-label'],
                        'horizontalCssClasses' => [
                            'wrapper' => 'col-sm-9',
                        ],
                    ],
                    'enableAjaxValidation'   => true,
                    'enableClientValidation' => false,
                    'validateOnBlur'         => false,
                ]); ?>

                <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

                 <?=\ptech\pyrocms\widgets\BirthdayPicker::widget( [
                        'model' => $model,
                        'attribute' => 'birth_date',
                        'listData' => $listData,
                        'form' => $form
                    ]) ?>


                <?=$form->field($model, 'gender')->label('Gender')->radioList($listData['genders'])?>

                <?= $form->field($model, 'alternate_email')->widget(\yii\widgets\MaskedInput::className(), [
                    'clientOptions' => [
                        'alias' =>  'email'
                    ],
                ]) ?>

                <?= $form->field($model, 'website')->widget(\yii\widgets\MaskedInput::className(), [
                     'clientOptions' => [
                        'alias' =>  'url',
                    ],
                ]) ?>

                <?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'state')->dropDownList($listData['states'],['prompt'=>'Select State']); ?>

                <?= $form->field($model, 'zip')->widget(\yii\widgets\MaskedInput::className(), [
                    'mask' => '99999',
                ]) ?>

                <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
                    'mask' => '999-999-9999',
                ]) ?>

                <?= $form->field($model, 'bio')->textArea(['rows'=>4])?>

                <?=$form->field($model, 'email_prefs')->label('Email Preferences')->radioList([
                        Profile::ALL_EMAILS => 'I Wish To Receive System Emails',
                        Profile::NO_EMAILS  => 'Do Not Send Me Emails',
                    ])->label('Email Preferences')
                ?>

                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-9">
                        <?= Html::submitButton('Update', ['class' => 'btn btn-block btn-success']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
