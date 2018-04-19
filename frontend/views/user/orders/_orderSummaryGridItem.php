<?php
use yii\helpers\Html;
use ptech\pyrocms\models\helpers\CmsHtmlHelpers;
use ptech\pyrocms\models\helpers\CartHelpers;
/* @var $this yii\web\View */
//$model, $key, $index, $widget
// \Yii::trace($data->orderModifiers,'dev');
$data = $model;
$header ='<b>'.$data->name . ' ' . CartHelpers::getPrice($data->price).'</b>';
$html = Html::tag('div',$header,['class'=>'grid-name col-xs-12']);

// $html ='';
foreach ($data->orderModifiers as $key => $addon) {
    $lines[] =' + '. $addon->name . ' <small class="mod-cart-price">Addl Price '. CartHelpers::getPrice($addon->price).'</small>';
}
if(isset($lines)){
    $list = Html::ul($lines,['encode'=>false]);
    $html .= Html::tag('div',$list,['class'=>'col-xs-12']);
}
$html .= Html::tag('div',$data->comments ,['class'=>'col-xs-12 visible-xs-block']);

// $html;
?>
<div class='row order-item' >
	<div class='col-xs-12'>
		<div class='order-info-container col-xs-12 col-md-6'>
			<div class='order-name'>
				<b><?=$model->name . ' ' . CartHelpers::getPrice($model->price)?></b>
			</div>
			<div class='order-addons'>
				<?php if(!empty($data->orderModifiers) || !empty($data->option)  ){?>
					<ul>
					<?php if(!empty($model->option)){?>
						<li>Option: <?=$model->option?></li>
					<?php } ?>
					<?php foreach ($data->orderModifiers as $key => $addon) { ?>
						<li> + <?=$addon->name?><small class="mod-cart-price">Addl Price <?=CartHelpers::getPrice($addon->price)?></small></li>
					<?php } ?>
					</ul>
				<?php }?>
			</div>
		</div>
		<div class='cart-summary-updates col-xs-12 col-md-6'>
			<h4>Quantity <?=$model['quantity']?></h4>
		</div>
		<div class='order-comments col-xs-12'>
			<?=$model->comments?>
		</div>
	</div>
</div>
