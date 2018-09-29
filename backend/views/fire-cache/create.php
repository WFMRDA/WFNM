<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\fireCache\FireCache */

$this->title = 'Create Fire Cache';
$this->params['breadcrumbs'][] = ['label' => 'Fire Caches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fire-cache-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
