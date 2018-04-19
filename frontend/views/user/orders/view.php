<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\ListView;
use ptech\pyrocms\models\helpers\CartHelpers;

/* @var $this yii\web\View */
/* @var $model app\models\Orders */

$this->title = 'Order No. ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="orders-view">
    <div class="row">
        <div class='container-fluid'>
            <div class="col-xs-12  col-md-3">
                <?= $this->render('@pyrocms/views/user/settings/_menu') ?>
            </div>
            <div class="col-xs-12 col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?= Html::encode($this->title) ?>
                    </div>
                    <div class="panel-body">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'id',
                                // 'user_id',
                                // 'confirmation_id',
                                [
                                    'label' => 'confirmation_id',
                                    'format'=>'html',
                                    'value' =>  CartHelpers::getConfirmationStatus($model,'confirmation_id',false),
                                    // 'value' =>  (!empty($model->cancelled_at))?'Order Cancelled':(empty($model->confirmation_id))?'Pending':$model->confirmation_id,


                                ],
                                [
                                    'label' => 'confirmed_at',
                                    'format'=>'html',
                                    'value' => CartHelpers::getConfirmationStatus($model,'confirmed_at',true),
                                ],
                                [
                                    'label' => 'Pick Up Time',
                                    'value' => (is_null($model->pickup_at))?'As Soon As Possible':\Yii::$app->formatter->asDatetime($model->pickup_at),
                                ],
                                // 'confirmed_at',
                                // 'pickup_at',
                                // 'cancelled_at',
                                'costs:currency',
                                'tax:currency',
                                'total_costs:currency',
                                'created_at:datetime',
                                // 'updated_at',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
            <div class='col-xs-12'>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?= Html::encode($this->title) ?> Information
                    </div>
                    <div class="panel-body">
                        <?=ListView::widget([
                            'dataProvider' => $dataProvider,
                            'layout' => "<div class='order -items'>{items}</div><div>{pager}</div>",
                            'itemView' => '_orderSummaryGridItem',
                            'summary' => 'Showing <b>{begin, number}-{end, number}</b> of <b>{totalCount, number}</b> {totalCount, plural, one{item} other{items}}.',
                            'pager' => [
                                'options' => [
                                    'class' => 'pagination col-xs-12'
                                ]
                            ],
                            'itemOptions'=>[
                                'tag' => false,
                            ],
                            'options' =>[
                                'class'=>'col-xs-12',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
