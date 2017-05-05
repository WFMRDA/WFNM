<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\MyFiresWidget;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use common\models\helpers\WfnmHelpers;
use yii\bootstrap\Dropdown;
$this->title = 'Fires Near Me';

/*$mapdata EMERGING = 'emerging';
$mapdata NEW0 = 'new';
$mapdata CONTROLLED = 'controlled';
$mapdata CONTAINED = 'contained';
$mapdata ACTIVE = 'active';
$mapdata OUT = 'out';*/

$this->registerJs("jQuery.fn.DataTable.ext.pager.numbers_length = 4;");
?>

<div id='sit-report' class='row'>
	<div class='container-fluid'>
		<div class='col-xs-12'>
			<h2 class='header-title'><?=$this->title?></h2>
		</div>
		<div class='col-xs-12 overview'>
			<div class="dropdown">
			  	<button class="btn btn-default dropdown-toggle col-xs-12" type="button" id="myLocationsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			    	<?=($address == null)?'Location Unknown':$address?>
			    	<span class="caret"></span>
			  	</button>
			  	<ul class="dropdown-menu col-xs-12" aria-labelledby="myLocationsDropdown">
					<?php /*($address == null)?'':WfnmHelpers::tag('li',WfnmHelpers::a($address,null,[
						'class'=>'mylocation-list-selection',
						'data'=>[
							'address' => $address,
							'latitude' => $mapData->userData['latitude'],
							'longitude' => $mapData->userData['longitude'],
						]
					]))*/?>
					<?php foreach ($myLocations as $key => $location) {
						echo WfnmHelpers::tag('li',WfnmHelpers::a($location->address,null,[
							'class'=>'mylocation-list-selection',
							'data'=>[
					            'address' => $location->address,
					            'place_id' => $location->place_id,
					            'latitude' => $location->latitude,
					            'longitude' => $location->longitude,
					        ]
						]));
					} ?>
				    <li role="separator" class="divider"></li>
		            <div class="btn-group col-xs-12" role="group" aria-label="Edit List">
						<?=WfnmHelpers::a('<i class="fa fa-pencil" aria-hidden="true">Edit Locations</i>',['/map-rest/my-locations'],['class'=> 'legend-btn btn btn-default myLocationsEdit btn-block'])?>
		            </div>
			  	</ul>
			</div>
		</div>
		<div class='col-xs-12'>
			<!-- Nav tabs -->
		  	<ul class="nav nav-tabs" role="tablist">
			    <li role="presentation" class="active"><a href="#emergingFires" aria-controls="emergingFires" role="tab" data-toggle="tab">Emerging Fires</a></li>
			    <li role="presentation"><a href="#newFires" aria-controls="newFires" role="tab" data-toggle="tab">New Fires</a></li>
				<li role="presentation"><a href="#activeFires" aria-controls="activeFires" role="tab" data-toggle="tab">Active Fires</a></li>
				<li role="presentation"><a href="#controlledFires" aria-controls="controlledFires" role="tab" data-toggle="tab">Controlled Fires</a></li>
				<li role="presentation"><a href="#containedFires" aria-controls="containedFires" role="tab" data-toggle="tab">Contained Fires</a></li>
			</ul>
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="emergingFires">
					<div class='col-xs-12'>
						<h2 class='header-title'>Emerging Fires</h2>
					</div>
					<?=  MyFiresWidget::widget([
					    'dataProvider' => new ArrayDataProvider([
				            'allModels' => ArrayHelper::getValue($models,$mapData::EMERGING),
				            'pagination' => false,
				        ]),
					    'columns' => [
							'incidentName',
							'dailyAcres',
							[
							    'label' => 'Dist(mi)',
							    'attribute' =>'distance',
							    'format'=>['decimal',2]
							]
						],
						'tableOptions'=>[
							'id'=> 'emergingMyFiresTable',
						],
						'clientOptions'=>[
		        			"order" => [[ 2, "asc" ]],
							'stateSave' => true,
							'dom' => '<"row"r <"col-xs-12"f> <"col-xs-12"l> <"col-xs-12"t><"col-xs-12"i> <"col-xs-12"p> >',
							'pageLength' => 5,
						],
				    ]);?>
				</div>
				<div role="tabpanel" class="tab-pane" id="newFires">
					<div class='col-xs-12'>
						<h2 class='header-title'>New Fires</h2>
					</div>
					<?=  MyFiresWidget::widget([
						'dataProvider' => new ArrayDataProvider([
				            'allModels' => ArrayHelper::getValue($models,$mapData::NEW0),
				            'pagination' => false,
				        ]),
					    'columns' => [
							'incidentName',
							'dailyAcres',
							[
							    'label' => 'Dist(mi)',
							    'attribute' =>'distance',
							    'format'=>['decimal',2]
							]
						],
						'tableOptions'=>[
							'id'=> 'newFiresTable',
						],
						'clientOptions'=>[
		        			"order" => [[ 2, "asc" ]],
							'stateSave' => true,
							'dom' => '<"row"r <"col-xs-12"f> <"col-xs-12"l> <"col-xs-12"t><"col-xs-12"i> <"col-xs-12"p> >',
							'pageLength' => 5,
						],
				    ]);?>
				</div>
				<div role="tabpanel" class="tab-pane" id="activeFires">
					<div class='col-xs-12'>
						<h2 class='header-title'>Active Fires</h2>
					</div>
					<?=  MyFiresWidget::widget([
						'dataProvider' => new ArrayDataProvider([
				            'allModels' => ArrayHelper::getValue($models,$mapData::ACTIVE),
				            'pagination' => false,
				        ]),
					    'columns' => [
							'incidentName',
							'dailyAcres',
							[
							    'label' => 'Dist(mi)',
							    'attribute' =>'distance',
							    'format'=>['decimal',2]
							]
						],
						'tableOptions'=>[
							'id'=> 'newMyFiresTable',
						],
						'clientOptions'=>[
		        			"order" => [[ 2, "asc" ]],
							'stateSave' => true,
							'dom' => '<"row"r <"col-xs-12"f> <"col-xs-12"l> <"col-xs-12"t><"col-xs-12"i> <"col-xs-12"p> >',
							'pageLength' => 5,
						],
				    ]);?>
				</div>

				<div role="tabpanel" class="tab-pane" id="controlledFires">
					<div class='col-xs-12'>
						<h2 class='header-title'>Controlled Fires</h2>
					</div>
					<?=  MyFiresWidget::widget([
						'dataProvider' => new ArrayDataProvider([
				            'allModels' => ArrayHelper::getValue($models,$mapData::CONTROLLED),
				            'pagination' => false,
				        ]),
					    'columns' => [
							'incidentName',
							'dailyAcres',
							[
							    'label' => 'Dist(mi)',
							    'attribute' =>'distance',
							    'format'=>['decimal',2]
							]
						],
						'tableOptions'=>[
							'id'=> 'controlledMyFiresTable',
						],
						'clientOptions'=>[
		        			"order" => [[ 2, "asc" ]],
							'stateSave' => true,
							'dom' => '<"row"r <"col-xs-12"f> <"col-xs-12"l> <"col-xs-12"t><"col-xs-12"i> <"col-xs-12"p> >',
							'pageLength' => 5,
						],
				    ]);?>
				</div>

				<div role="tabpanel" class="tab-pane" id="containedFires">
					<div class='col-xs-12'>
						<h2 class='header-title'>Contained Fires</h2>
					</div>
					<?=  MyFiresWidget::widget([
						'dataProvider' => new ArrayDataProvider([
				            'allModels' => ArrayHelper::getValue($models,$mapData::CONTAINED),
				            'pagination' => false,
				        ]),
					    'columns' => [
							'incidentName',
							'dailyAcres',
							[
							    'label' => 'Dist(mi)',
							    'attribute' =>'distance',
							    'format'=>['decimal',2]
							]
						],
						'tableOptions'=>[
							'id'=> 'containedMyFiresTable',
						],
						'clientOptions'=>[
		        			"order" => [[ 2, "asc" ]],
							'stateSave' => true,
							'dom' => '<"row"r <"col-xs-12"f> <"col-xs-12"l> <"col-xs-12"t><"col-xs-12"i> <"col-xs-12"p> >',
							'pageLength' => 5,
						],
				    ]);?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	jQuery('.dropdown-toggle').dropdown();
</script>
