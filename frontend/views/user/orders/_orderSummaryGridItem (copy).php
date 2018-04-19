<?php
use yii\helpers\Html;
use ptech\pyrocms\models\helpers\CmsHtmlHelpers;
use ptech\pyrocms\models\helpers\CartHelpers;
/* @var $this yii\web\View */
//$model, $key, $index, $widget
$data = $model;
$mainItem = \Yii::createObject([
    'class'       	=> '\ptech\pyrocms\models\shoppingCart\Cart',
]);
$mainItem->load($model,'');
// \Yii::trace($mainItem->option,'dev');
$optPrice = (empty($mainItem->option->price))?0:$mainItem->option->price;
?>
<div class='row cart-item' >
	<div class='col-xs-12'>
		<div class='cart-info-container col-xs-12 col-md-6'>
			<div class='cart-name'>
				<?=Html::a($model['name'],['/menu/product','prid'=> $model['major_item_id']],['class'=>'title'])?> <small class="mod-cart-price"> <?=CartHelpers::getPrice($model['price'] )?> </small>
			</div>
			<div class='cart-addon'>
				<?php if(!empty($model['addons'])){?>
					<ul>
					<?php if(!empty($mainItem->option->name)){?>
						<li>Option: <?=$mainItem->option->name?></li>
					<?php } ?>
					<?php foreach ($model['addons'] as $key => $addon) { ?>
						<li> <?=$addon['name']?> <small class="mod-cart-price">Addl Price <?=CartHelpers::getPrice($addon['price'])?> </small></li>
					<?php } ?>
					</ul>
				<?php }?>
			</div>
		</div>
		<div class='cart-updates col-xs-12 col-md-6'>
			<?=$this->render('_update',[
			    'mainItem' => $mainItem,
			    'label' => 'Quantity',
			])?>
		</div>
		<div class='cart-delete col-xs-12'>
			<?=Html::a ('Delete' ,null,['id' =>'cart-delete-submit',
				'data'=>[
					'id'=>$mainItem->major_item_id,
					'key' => $mainItem->key,
				],
				'class'=> 'delete-item small-txt'
			])?>
		</div>
	</div>
</div>
