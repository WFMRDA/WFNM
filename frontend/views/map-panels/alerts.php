<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\NotificationsWidget;
$this->title = 'Alerts & Notifications';
?>
<div class='text-center panel-title'>
	<h3><?= Html::encode($this->title) ?></h3>
</div>
<?=NotificationsWidget::widget([
	'dataProvider' => Yii::$app->systemData->userMessages,
	'options'=>[
		'type' => NotificationsWidget::PANEL,
		'id'=>'notifications_panel',
	],
]);?>
