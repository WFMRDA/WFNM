<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\BirthdayPicker\assets\BirthdayPickerAsset;


/* @var $this yii\web\View */
/* @var $model ptech\pyrocms\models\formGenerator\Forms */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'User', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php \ptech\pyrocms\widgets\WidgetGrid::begin()?>
<h1><?= Html::encode($this->title) ?></h1>
<div class="site-signup">
    <div class="row">
        <div class="col-centered">
            <div class="panel panel-default">
                <div class="panel-body">
                    <p>Please fill out the following fields to Register a User:</p>
                    <?php $form = ActiveForm::begin([
                        'id' => 'signup-form',
                    ]); ?>
                    <div class="form-group">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    </div>
                        <?= $form->field($model, 'status')->dropDownList( $listData['status'],[
                            'class'=>'form-control selectpicker',
                            'prompt' => 'Select Status'
                        ])->label('Account Status <small>(required)</small>');?>
                        <?= $form->field($model, 'role')->dropDownList( $listData['role'],[
                            'class'=>'form-control selectpicker',
                            'prompt' => 'Select Status'
                        ])->label('User Role <small>(required)</small>');?>

                        <?= $form->field($model, 'email')->widget(\yii\widgets\MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'email'
                                ],
                            ])->label('Email <small>(required)</small>') ?>

                            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true])->label('First name <small>(required)</small>') ?>

                            <?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>

                            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true])->label('Last Name <small>(required)</small>') ?>

                            <?= $form->field($model, 'username')->textInput(['autofocus' => true])?>
                            
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
                        <div class="form-group">
                            <?= Html::submitButton('Create User', ['class' => 'btn btn-primary btn-block', 'name' => 'signup-button']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php \ptech\pyrocms\widgets\WidgetGrid::end()?>
