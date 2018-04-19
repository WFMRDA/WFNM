<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\myLocations\MyLocations */

$this->title = 'Create My Locations';
$this->params['breadcrumbs'][] = ['label' => 'My Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="my-locations-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
