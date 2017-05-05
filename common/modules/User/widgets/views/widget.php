<?php

use yii\helpers\Html;
use yii\web\View;

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
	<ul class="eauth-list text-center">
		<?php
		foreach ($services as $name => $service) {
			$class =strtolower($service->title);
			echo '<li class="eauth-service eauth-service-id-' . $service->id . '">';
			echo Html::a('<span class="fa fa-'.$class.'"></span> Sign in with '.$service->title, [$action, 'service' => $name], [
				'class' => 'btn btn-block btn-lg btn-social btn-'.$class,
				'data-eauth-service' => $service->id,
			]);
			echo '</li>';
		}
		?>
	</ul>
</div>
