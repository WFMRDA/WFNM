<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\myFires\MyFires */

$this->title = 'Update My Fires: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'My Fires', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="my-fires-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
