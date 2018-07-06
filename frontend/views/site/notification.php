<?php

use common\models\helpers\WfnmHelpers;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\myFires\MyFires */

$this->title = $record['incidentName'] . ' Fire';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='col-xs-12'>
    <?=WfnmHelpers::tag(Yii::$app->request->isAjax?'h3':'h1', WfnmHelpers::encode($this->title),['class'=>'text-center'])?>
    <h4> Fire Status: <?=WfnmHelpers::getClassImg($record['fireClass'])?> <?=$record['fireClass']?>  </h4>
</div>
<div class="my-fires-view">
    <div class='col-xs-12'>
        <?=(!Yii::$app->request->isAjax)?'<p>'.WfnmHelpers::a('<<< Go to Map',['site/index'],['class'=>'btn btn-xs btn-success']).'</p>':'' ?>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">General Info</a></li>
            <li role="presentation"><a href="#incident" aria-controls="incident" role="tab" data-toggle="tab">Incident Info</a></li>
            <li role="presentation"><a href="#comments" aria-controls="comments" role="tab" data-toggle="tab">Comments</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="general">
                <div class='text-center'>
                    <h3>General Info</h3>
                    <h5 class='inline link-dark'><?=$record['gacc']?></h5>
                    <?=WfnmHelpers::a('<b>P</b><span class="hidden-xs hidden-sm">reparedness </span><b>L</b><span class="hidden-xs">evel</span> <span class=" pl-sprite pl'.WfnmHelpers::getPrepLevel($record['gacc']).'"></span>','https://gacc.nifc.gov/'.strtolower($record['gacc']),['class'=>'link-dark pl-text', 'target'=>'_blank'])?>
                </div>
                <?= DetailView::widget([
                    'model' => $record,
                    'template' => '<tr><th{captionOptions}>{label}:</th><td{contentOptions}>{value}</td></tr>',
                    'attributes' => [
                        'incidentName',
                        'incidentShortDescription',
                        [
                            'attribute'=>'uniqueFireIdentifier',
                            'label'=> 'Incident Number',
                        ],
                        [
                            'label'=>'Location',
                            'format'=>'raw',
                            'value'=>function ($model, $widget){
                                return round($model['pooLatitude'],2).' , '.round($model['pooLongitude'],2);
                            },
                        ],
                        [
                            'attribute'=>'dailyAcres',
                            'format'=>'decimal',
                            'label'=> 'Acres',
                        ],
                        [
                            'attribute'=>'fireDiscoveryDateTime',
                            'label'=>'Start Time',
                            'format'=>'datetime',
                        ],
                        [
                            'attribute'=>'fireCause',
                            'label'=>'Cause',
                        ],
                        'gacc',
                        [
                            'attribute'=>'fireMgmtComplexity',
                            'label'=>'Complexity',
                        ],
                        [
                            'attribute'=>'createdBySystem',
                            'label'=>'Created By',
                        ],
                        [
                            'attribute'=>'modifiedBySystem',
                            'label'=>'Last Updated By',
                        ],
                    ],
                ])?>

            </div>
            <div role="tabpanel" class="tab-pane" id="incident">
                <div class='text-center'>
                    <h3>Incident Info</h3>
                </div>
                <?= DetailView::widget([
                    'model' => $record,
                    'template' => '<tr><th{captionOptions}>{label}:</th><td{contentOptions}>{value}</td></tr>',
                    'attributes' => [
                        'percentContained',
                        [
                            'attribute'=>'estimatedContainmentDate',
                            'format'=>'datetime',
                        ],
                        'residencesThreatened',
                        'residencesDestroyed',
                        'otherStructuresThreatened',
                        'otherStructuresDestroyed',
                        'totalIncidentPersonnel',
                        [
                            'attribute'=>'primaryFuelModel',
                            'label'=>'Primary Fuel/Terrain',
                        ],
                        [
                            'attribute'=>'pooIncidentJurisdictionalAgency',
                            'label'=>'Jurisdictional Unit',
                        ],
                        [
                            'attribute'=>'isMultiJurisdictional',
                            'label'=>'MultiJurisdictional?',
                        ],
                        [
                            'attribute'=>'complexParentIrwinId',
                            'label'=> 'Complex Name',
                            'value'=>function ($model, $widget){
                                return (isset($model['complexParentIrwinId']))? ArrayHelper::getValue(WfnmHelpers::getFireInfo($model['complexParentIrwinId']),'incidentName',''):'';
                            },
                            'visible'=> isset($record['complexParentIrwinId']),
                        ],
                        [
                            'attribute'=>'containmentDateTime',
                            'format'=>'datetime',
                            'visible'=> isset($record['containmentDateTime']),
                        ],
                        [
                            'attribute'=>'controlDateTime',
                            'format'=>'datetime',
                            'visible'=> isset($record['controlDateTime']),
                        ],
                        [
                            'attribute'=>'fireOutDateTime',
                            'format'=>'datetime',
                            'visible'=> isset($record['fireOutDateTime']),
                        ],
                        'significantEvents',
                        'weatherConcerns',
                        'ics209Remarks',
                        'plannedActions',
                    ],
                ])?>
            </div>
            <div role="tabpanel" class="tab-pane" id="comments">
                <div class='col-xs-12'>
                    <h3 class='text-center'>Comments</h3>
                    <div class="fb-comments" data-href="http://www.wildfiresnearme.wfmrda.com/wfnm/index#<?=$record['uniqueFireIdentifier']?>" data-width="100%" data-colorscheme='light' data-numposts="10"></div>
                </div>
            </div>
        </div>
    </div>
</div>
