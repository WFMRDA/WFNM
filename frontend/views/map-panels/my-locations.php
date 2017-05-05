<?php
use common\models\helpers\WfnmHelpers;
use yii\bootstrap\ActiveForm;
use yii\web\JsExpression;
use \yii\helpers\Url;
use common\widgets\MyLocationsWidget;
use yii\data\ArrayDataProvider;

?>
    <?php $form = ActiveForm::begin([
        // 'layout' => 'horizontal',
        'options' => ['class'=>'text-center']
    ]) ?>
    <?= $form->field($model, 'address', [
	    'inputTemplate' =>
        '<div class="row">
            <div class="col-xs-11 col-md-10 col-centered">
                <div class="input-group">
                    {input}
                    <span class="input-group-btn">
                        <button class="btn btn-default add-location" type="button"><i class="fa fa-plus" aria-hidden="true">Add</i></button>
                    </span>
                </div><!-- /input-group -->
            </div><!-- /.col-lg-6 -->
        </div><!-- /.row -->',
	])
    ->label('Search')
    ->textInput([
        'maxlength' => true,
        'placeholder'=>'Enter your address',
        'id'=>'addressInput',
        'onFocus'=>"geolocate()"
    ]) ?>

    <?php ActiveForm::end(); ?>
    <div id="myLocationsTable-Container" class="col-xs-11 col-md-10 col-centered">
        <div class='row'>
            <div class="btn-group pull-right" role="group" aria-label="Edit List">
                <button type="button" class="btn btn-default myLocationsEdit"><i class="fa fa-pencil" aria-hidden="true">Edit</i></button>
                <button type="button" class="btn btn-default myLocationsCancelEdit"><i class="fa fa-ban" aria-hidden="true">Done</i></button>
            </div>
        </div>
        <div id='myLocationsTable-container'>
            <?=  $this->render('_mylocationstable',['models'=>$models])?>
        </div>
    </div>
    <script>
        initAutocomplete();

    </script>
