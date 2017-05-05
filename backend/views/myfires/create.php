<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\myFires\MyFires */

$this->title = 'Create My Fires';
$this->params['breadcrumbs'][] = ['label' => 'My Fires', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="my-fires-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
