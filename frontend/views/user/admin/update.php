<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Update User: ' . $model->fullName;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<<< User Home', ['index'], ['class' => 'btn btn-success btn-sm']) ?>
        <?=Html::a('Reset Password', ['reset-password', 'id' => $model->id] ,
        [
            'class' => 'btn btn-sm btn-warning',
            'title' => Yii::t('app', 'Reset'),
            'data-confirm'=>'Are you sure you want to reset '. $model->username . ' password?',

        ]);?>

        <?=($model->id == Yii::$app->user->getId())?'': Html::a('Delete', ['Deactivate', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-sm pull-right',
            'data' => [
                'confirm' => 'Are you sure you want to delete '. $model->username . ' ?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= $this->render('_form', [
        'listData' => $listData,
        'model' => $model,
    ]) ?>

</div>
