<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\fireCache\FireCache */

$this->title = 'Update Fire Cache: ' . $model->irwinID;
$this->params['breadcrumbs'][] = ['label' => 'Fire Caches', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->irwinID, 'url' => ['view', 'id' => $model->irwinID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fire-cache-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
