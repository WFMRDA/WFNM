<?php

use common\modules\User\widgets\Connect;
use yii\helpers\Html;

ptech\pyrocms\assets\ConnectAsset::register($this);
/*
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 */

$this->title = 'Networks';
$this->params['breadcrumbs'][] = $this->title;
?>
<?=ptech\pyrocms\widgets\Alert::widget();?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('_menu') ?>
    </div>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode($this->title) ?>
            </div>
            <div class="panel-body">
                <div class="alert alert-info">
                    <p>You can connect multiple accounts to be able to log in using them.</p>
                </div>
                <?= \ptech\pyrocms\widgets\EauthConnect::widget(['action' => '/settings/connect']); ?>
            </div>
        </div>
    </div>
</div>
