<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\BirthdayPicker\assets\BirthdayPickerAsset;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.col-centered{
    float: none;
    margin: 0 auto;
}
</style>
<div class="site-signup">
    <div class="row">
        <div class="col-lg-6 col-md-8 col-sm-10 col-centered">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="panel-body">
                    <p>Please fill out the following fields to signup:</p>
                    <?php $form = ActiveForm::begin([
                        'id' => 'signup-form',
                        /*'options' => ['class' => 'form-horizontal'],
                        'layout' => 'horizontal',
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
                            'labelOptions' => ['class' => 'col-lg-3 control-label'],
                            'horizontalCssClasses' => [
                                'wrapper' => 'col-sm-9',
                            ],
                        ],*/
                        // 'enableAjaxValidation'   => true,
                        // 'enableClientValidation' => false,
                        // 'validateOnBlur'         => false,
                    ]); ?>

                        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                        <?= $form->field($model, 'email')->widget(\yii\widgets\MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'email'
                                ],
                            ]) ?>

                        <?=  $form->field($model, 'password')->widget(kartik\password\PasswordInput::classname(), [
                            'pluginOptions' => [
                                // 'showMeter' => true,
                                // 'toggleMask' => true
                            ]
                        ]);?>

                        <?php if($model->fillProfile){?>
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

                            <?= $form->field($model, 'state')->textInput(['maxlength' => true]) ?>

                            <?= $form->field($model, 'state')->dropDownList($listData['states'],['prompt'=>'Select State']); ?>

                            <?= $form->field($model, 'zip')->widget(\yii\widgets\MaskedInput::className(), [
                                'mask' => '99999',
                            ]) ?>

                            <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
                                'mask' => '999-999-9999',
                            ]) ?>
                        <?php } ?>

                        <?= $form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::classname(), [
                            'captchaAction' => '/user/captcha',
                        ]) ?>

                        <div class="form-group">
                            <?= Html::submitButton('Signup', ['class' => 'btn btn-primary btn-block', 'name' => 'signup-button']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
