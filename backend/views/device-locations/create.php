<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\devices\DeviceLocations */

$this->title = 'Create Device Locations';
$this->params['breadcrumbs'][] = ['label' => 'Device Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-locations-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
