<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\cms\SysVariables */

$this->title = 'Create Sys Variables';
$this->params['breadcrumbs'][] = ['label' => 'Sys Variables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sys-variables-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
