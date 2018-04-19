<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->widget(\yii\widgets\MaskedInput::className(), [
            'clientOptions' => [
                'alias' =>  'email'
            ],
        ]) ?>


    <?= $form->field($model, 'status')->dropDownList( $listData['status'],[
        'class'=>'form-control selectpicker',
        'prompt' => 'Select Status'
    ]);?>
    <?= $form->field($model, 'role')->dropDownList( $listData['role'],[
        'class'=>'form-control selectpicker',
        'prompt' => 'Select Status'
    ]);?>



    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
