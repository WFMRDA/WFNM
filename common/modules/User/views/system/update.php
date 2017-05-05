<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\cms\SysVariables */

$this->title = 'System Settings';
$this->params['breadcrumbs'][] = 'System Settings';
?>
<div class="sys-variables-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
