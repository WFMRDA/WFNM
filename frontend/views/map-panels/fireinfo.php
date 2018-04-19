<?php
use yii\i18n\Formatter;
use common\models\helpers\WfnmHelpers;
use yii\helpers\Html;

?>

<div id='fireinfo-container' class='fireinfo-table fireinfo-panel '>

	<div id ='btn-tweet' class='col-xs-12  text-center'>
		<!-- Twitter button -->
	</div>
	<div id ='btn-311' class='col-xs-12 text-center'>
		<!-- Follow Unfollow Btn -->
		<?=WfnmHelpers::getFireMonitoringBtn($irwin);?>
	</div>


	<?=$this->render('@app/views/site/notification',['record'=>$irwin])?>
</div>
