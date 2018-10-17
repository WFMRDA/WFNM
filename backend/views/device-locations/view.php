<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\devices\DeviceLocations */

$this->title = $model->device_id;
$this->params['breadcrumbs'][] = ['label' => 'Device Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-locations-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->device_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->device_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'device_id',
            'latitude',
            'longitude',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
