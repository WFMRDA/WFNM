<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\devices\DeviceList */

$this->title = 'Create Device List';
$this->params['breadcrumbs'][] = ['label' => 'Device Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-list-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
