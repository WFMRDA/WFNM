<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\devices\DeviceLocations */

$this->title = 'Update Device Locations: ' . $model->device_id;
$this->params['breadcrumbs'][] = ['label' => 'Device Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->device_id, 'url' => ['view', 'id' => $model->device_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="device-locations-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
