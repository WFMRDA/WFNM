<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\helpers\WfnmHelpers;
use common\widgets\MyFiresWidget;
use yii\data\ArrayDataProvider;

$this->title = 'My Fires';
$this->registerJs("jQuery.fn.DataTable.ext.pager.numbers_length = 4;");
?>
<div id='myFireContainer' class='row'>
	<div class='container-fluid'>
		<div class='col-xs-12 text-center'>
			<h2 class='header-title'><?=$this->title?></h2>
		</div>
		<div class='col-xs-12'>
            <?=  MyFiresWidget::widget([
                'dataProvider' =>  new ArrayDataProvider([
		            'allModels' => $models,
		            'pagination' => false,
		        ]),
                'columns' => [
                    'name',
                    'created_at:date'
                ],
                'tableOptions'=>[
                    'id'=> 'myFiresTable',
                ],
                'clientOptions'=>[
                    "order" => [[ 1, "desc" ]],
                    'stateSave' => true,
                    'dom' => '<"row"r <"col-xs-12"f> <"col-xs-12"l> <"col-xs-12"t><"col-xs-12"i> <"col-xs-12"p> >',
                    'pageLength' => 5,
                ],
            ]);?>
		</div>
	</div>
</div>
<script>
	jQuery('.dropdown-toggle').dropdown();
</script>
