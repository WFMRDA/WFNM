<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\devices\DeviceList */

$this->title = 'Update Device List: ' . $model->device_id;
$this->params['breadcrumbs'][] = ['label' => 'Device Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->device_id, 'url' => ['view', 'id' => $model->device_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="device-list-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
