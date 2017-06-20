<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\MyFiresWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
$this->title = 'Situation Report';
// \Yii::trace($sitReport,'dev');

$this->registerJs("jQuery.fn.DataTable.ext.pager.numbers_length = 4;");
?>

<div id='sit-report' class='row'>
	<div class='container-fluid'>
		<div class='col-xs-12'>
			<h2 class='header-title'><?=$this->title?></h2>
		</div>
		<div class='col-xs-12 overview'>
			<div class="pl-level-report-box">
				<p class="pl-level-num"> <?=Yii::$app->systemData->getPlLevel()?></p>
				<p class="pl-level-title">P<span class="hidden-xs hidden-sm">reparedness</span> L<span class="hidden-xs">evel</span> </p>
			</div>
			<div class="pl-level-data-box">
				<p class='panel-box-title'>new large incidents<span class="panel-box-text "> <?=ArrayHelper::getValue($sitReport,'NLI.val','')?> </span> </p>
				<p class="panel-box-title">large fires contained<span class="panel-box-text "> <?=ArrayHelper::getValue($sitReport,'LFC.val','')?>  </span> </p>
				<p class="panel-box-title">uncontained large fires<span class="panel-box-text "> <?=ArrayHelper::getValue($sitReport,'ULF.val','')?>  </span> </p>
				<p class="panel-box-title">area command teams committed<span class="panel-box-text "> <?=ArrayHelper::getValue($sitReport,'ACTC.val','')?>  </span> </p>
				<p class="panel-box-title">nimos committed<span class="panel-box-text "> <?=ArrayHelper::getValue($sitReport,'NIMO.val','')?>  </span> </p>
				<p class="panel-box-title">type 1 imts committed<span class="panel-box-text "> <?=ArrayHelper::getValue($sitReport,'IMTS-I.val','')?>  </span> </p>
				<p class="panel-box-title">type 2 imts committed<span class="panel-box-text "> <?=ArrayHelper::getValue($sitReport,'IMTS-II.val','')?>   </span> </p>
				<p class="panel-box-title">initial attack activity<span class="panel-box-text "><?=ArrayHelper::getValue($sitReport,'IAACT.val','')?> </span></p>
			</div>
			<div class='col-xs-12 text-center'>
				<?=Html::a ('View Full Sit Report', $url = 'https://www.nifc.gov/nicc/sitreprt.pdf', $options = ['class'=>'btn btn-default sit-btn' ,'target'=>'_blank'] )?>
			</div>
		</div>
		<div class='col-xs-12'>
			<!-- Nav tabs -->
		  	<ul class="nav nav-tabs" role="tablist">
			    <li role="presentation" class="active"><a href="#emergingFires" aria-controls="emergingFires" role="tab" data-toggle="tab">Emerging Fires</a></li>
			    <li role="presentation"><a href="#newFires" aria-controls="newFires" role="tab" data-toggle="tab">New Fires</a></li>
			</ul>
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="emergingFires">
					<div class='col-xs-12'>
						<h2 class='header-title'>Emerging Fires</h2>
					</div>
					<?=  MyFiresWidget::widget([
					    'dataProvider' => $emergingFireDataProvider,
					    'columns' => [
							'incidentName',
							'dailyAcres'
						],
						'tableOptions'=>[
							'id'=> 'emergingFiresTable2',
						],
						'clientOptions'=>[
		        			"order" => [[ 1, "desc" ]],
							// 'stateSave' => true,
							'dom' => '<"row"r <"col-xs-6"f> <"col-xs-6"l> <"col-xs-12"t><"col-xs-12"i> <"col-xs-12"p> >',
							'pageLength' => 5,
						],
				    ]);?>
				</div>
				<div role="tabpanel" class="tab-pane" id="newFires">
					<div class='col-xs-12'>
						<h2 class='header-title'>New Fires</h2>
					</div>
					<?=  MyFiresWidget::widget([
					    'dataProvider' => $newFireDataProvider,
					    'columns' => [
							'incidentName',
							'dailyAcres'
						],
						'tableOptions'=>[
							'id'=> 'newFiresTable2',
						],
						'clientOptions'=>[
		        			"order" => [[ 1, "desc" ]],
							// 'stateSave' => true,
							'dom' => '<"row"r <"col-xs-6"f> <"col-xs-6"l> <"col-xs-12"t><"col-xs-12"i> <"col-xs-12"p> >',
							'pageLength' => 5,
						],
				    ]);?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- 
<?php //$time?><br>
<?php //$emergingFireDataProvider->count?><br><?php //$newFireDataProvider->count?><br>
<?php //VarDumper::dumpAsString($table,10) ?>-->