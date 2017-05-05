<?php

use yii\helpers\Html;
use yii\web\View;
use common\modules\User\models\SocialAccounts;

/** @var $this View */
/** @var $id string */
/** @var $services stdClass[] See EAuth::getServices() */
/** @var $action string */
/** @var $popup bool */
/** @var $assetBundle string Alias to AssetBundle */

Yii::createObject(['class' => $assetBundle])->register($this);

// Open the authorization dilalog in popup window.
if ($popup) {
	$options = [];
	foreach ($services as $name => $service) {
		$options[$service->id] = $service->jsArguments;
	}
	$this->registerJs('$("#' . $id . '").eauth(' . json_encode($options) . ');');
}

?>
<div class="eauth" id="<?php echo $id; ?>">
	<div class="col-xs-12 eauth-list text-center">
		<?php
		foreach ($services as $name => $service) {
			$class = strtolower($service->title);
			echo '<div class=" col-xs-12 eauth-service eauth-service-id-' . $service->id . '">';
			//Check to see if User has account
			if(($account = SocialAccounts::find()->where(['and',['user_id'=>Yii::$app->user->identity->id,'provider'=>$service->id]])->one()) !== null){
				echo Html::a('<span class="fa fa-'.$class.'"></span> Disconnect '.$service->title, ['/user/settings/disconnect','id'=>$account->id], [
					'class' => 'btn btn-lg btn-social btn-'.$class,
					'data'=>[
				        'method' => 'post',
				        'params'=>['id'=>$account->id],
				    ]
				]);
			}else{
				echo Html::a('<span class="fa fa-'.$class.'"></span> Connect '.$service->title, [$action, 'service' => $name], [
					'class' => 'btn btn-lg btn-social btn-'.$class,
					'data-eauth-service' => $service->id,
				]);
			}

			echo '</div>';
		}
		?>
	</div>
</div>
