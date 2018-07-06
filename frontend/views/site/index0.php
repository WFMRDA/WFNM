<?php
// use yii\helpers\WfnmHelpers;
use yii\widgets\Menu;
use yii\helpers\Url;
use common\models\helpers\WfnmHelpers;
use common\widgets\PyroMenu;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use common\widgets\Bloodhound;
use common\widgets\TypeAhead;

use frontend\assets\ArcGisAsset;
ArcGisAsset::register($this);

$engine = new Bloodhound([
	'name' => 'incidents',
	'clientOptions' => [
		'datumTokenizer' => new \yii\web\JsExpression("BasilLena.tokenizers.obj.whitespace('value')"),
		'queryTokenizer' => new \yii\web\JsExpression("BasilLena.tokenizers.whitespace"),
		'remote' => [
			'url' => Url::to(['/map-rest/fire-list']) . '?incident=%QUERY',
			'wildcard' => '%QUERY',
			'rateLimitWait' => 500,
		]
	]
]);
/* @var $this yii\web\View */
$this->title = Yii::$app->name;
?>
<script>
	window.dojoConfig = {
		packages: [
			{
				name: "vue",
				location: location.pathname.replace(/\/[^/]+$/,'')+'/node_modules/vue/dist',
				main:"vue"
			}
		]
	};
</script>
<?php // Yii::$app->appSystemData->disclaimer?$this->render('_disclaimer'):''?>
<div id="app">
  {{ message }}
</div>
<div id='default-map-container' class="default-map-index">
	<div id="map" class="baseMap">
		<div id='fire-search-form-container' class='col-xs-12'>
			<?php $form = ActiveForm::begin([
				// 'action' => ['/site/fire-search'],
				'method' => 'get',
				'options' => [
					'class'=>"col-xs-12 col-sm-8 col-lg-4 col-centered" ,
					'role'=>"search",
					'id' =>'firesearch-form',
				],
			]); ?>

			<?= $form->field($searchModel, 'incidentName')->widget(
			    TypeAhead::className(),
			    [
					'options' => ['placeholder' => 'Search Fire Name','class'=>'form-rounded form-control'],
					'engines' => [ $engine ],
					'clientOptions' => [
						'hint' => true,
			            'highlight' => true,
			            'minLength' => 3,
						'limit' => 10,
			        ],
			        'clientEvents' => [
						"typeahead:select" => "function(ev, suggestion) {  jQuery.getFireInfo(suggestion['id']); }",
			        ],
					'dataSets' => [
			            [
			                'name' => 'incident',
			                'displayKey' => 'value',
			                'source' => $engine->getAdapterScript()
			            ]
			        ]
			    ]
			)->label(false)?>
			<?php ActiveForm::end(); ?>
		</div>
		<div id='legendToogle'>
			<?=WfnmHelpers::a('<i class="fa fa-question-circle-o"></i> Layers',null,['id'=>'legendToggle','class'=>'btn btn-sm btn-info'])?>
		</div>
		<div id='zoomDiv'></div>
		<div id='legendDiv' class='<?=Yii::$app->appSystemData->legendHelpToggle?>'></div>
		<div id='locateDiv'></div>
	</div>
	<div id='menu-toggle' class="checkboxOne" <?=Yii::$app->appSystemData->toggleBtn?>>
			<input type="checkbox" value="1" id="checkboxOneInput" name="" />
			<label for="checkboxOneInput">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"> </span>
				<span class="icon-bar"> </span>
				<span class="icon-bar"> </span>
			</label>
		</div>
	<div id="legend-bar">
		<div id = "legend-bar-container">
			<div id ='mapLegend' class='col-xs-12'>
				<h1 class='text-center legend-title'> Layers <?=WfnmHelpers::a('<i class="fa fa-times-circle-o large"></i>',null,['id'=>'legendClose','class'=>'close'] )?></h1>
				<!-- DISPLAY FORM  'action'=> '[map/adjust-map'] -->
				<?php $form = ActiveForm::begin(['id' => 'mapLayerForm','action'=> ['/system-rest/store-settings']]); ?>
						<div class='col-xs-6'>
							<?= $form->field($layersModel, 'fireSizeList')->checkboxList (Yii::$app->appSystemData->fireSizes, $options = ['class'=>'legendOptions','name'=>'fireSize'])?>
						</div>
						<div class='col-xs-6'>
							<?= $form->field($layersModel, 'fireStatusList')->checkboxList (Yii::$app->appSystemData->fireClasses, $options = ['class'=>'legendOptions','name'=>'fireClass'])?>
						</div>
						<div class='col-xs-6'>
							<?= $form->field($layersModel, 'addtlLayers')->checkboxList (Yii::$app->appSystemData->addlMapLayers, $options = ['class'=>'legendOptions','name'=>'addtlLayers'])?>
						</div>
						<?= WfnmHelpers::submitButton('Adjust Map', [ 'id' => 'map-layer-submit-btn','class' => 'btn btn-block btn-success']) ?>
				<?php ActiveForm::end(); ?>
				<!-- END DISPLAY FORM -->
			</div>
				<?=PyroMenu::widget([
					'options'=>[
						'id'=> 'map-btn-container',
					],
					'encodeLabels'=> false,
					'items' => [
						[
							'label' => WfnmHelpers::img ('@media/maplayers_btn.png', $options = ['class'=>'sidebar-fa-ms-png center '] ) ,
							'url' => null,
							'options'=>[
								// 'id'=>'layers',
								'rel'=>'layers',
								'class'=> 'desaturate'
							],
							'linkOptions'=>[
								'class'=> 'layers-btn'
							],
						],
						[
							'label' => WfnmHelpers::img ('@media/my_fires_btn.png', $options = ['class'=>'sidebar-fa-ms-png center '] ) ,
							'url' => ['/map-rest/my-fires'],
							'options'=>[
								// 'id'=>'layers',
								'rel'=>'layers',
								'class'=> 'desaturate'
							],
							'linkOptions'=>[
								'class'=> 'legend-btn'
							],
						],
						[
							'label' => WfnmHelpers::img ('@media/my_locations_btn.png', $options = ['class'=>'sidebar-fa-ms-png center '] ) ,
							'url' => ['/map-rest/my-locations'],
							'options'=>[
								// 'id'=>'myLocations',
								'rel'=>'layers',
								'class'=> 'desaturate'
							],
							'linkOptions'=>[
								'class'=> 'legend-btn'
							],
						],
						[
							'label' => WfnmHelpers::img ('@media/sitrep_btn.png', $options = ['class'=>'sidebar-fa-ms-png center '] ),
							'url' => ['map-rest/sit-rep'],
							'options'=>[
								// 'id'=>'layers',
								'rel'=>'layers',
								'class'=> 'desaturate'
							],
							'linkOptions'=>[
								'class'=> 'legend-btn'
							],
						],
						[
							'label' => WfnmHelpers::img ('@media/alerts_btn.png', $options = ['class'=>'sidebar-fa-ms-png center '] ) ,
							'url' =>  ['map-rest/alerts'],
							'options'=>[
								// 'id'=>'layers',
								'rel'=>'layers',
								'class'=> 'desaturate'
							],
							'linkOptions'=>[
								'class'=> 'legend-btn'
							],
						],
						[
							'label' => WfnmHelpers::img ('@media/wfnm_btn.png', $options = ['class'=>'sidebar-fa-ms-png center '] )  ,
							'url' =>  ['map-rest/fires-near-me'],
							'options'=>[
								'rel'=>'layers',
								'class'=> 'desaturate'
							],
							'linkOptions'=>[
								'id'=>'firesnearme',
								'class'=> 'wfnm-btn'
							],
						],
					],
				])?>
		</div>

	</div>
	<div id='info-panel-container'>
		<button type="button" class="close" id='closePanel' data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<div id='info-panel'></div>
	</div>
</div>
