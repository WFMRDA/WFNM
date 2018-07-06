<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use common\models\helpers\WfnmHelpers;
/*
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 */
 $this->registerJs(
     "$(document).ready(function() {
    autocomplete_init();
    $(document).on('click','.clear-profile-address', function(e){
        e.preventDefault();
        $('#autocomplete').val('');
        $('#defaultlocation-address').val('');
        $('#defaultlocation-place_id').val('');
        $('#defaultlocation-latitude').val('');
        $('#defaultlocation-longitude').val('');
    });
});",
    \yii\web\View::POS_READY
);
$this->title = 'Preferences';
$this->params['breadcrumbs'][] = $this->title;
?>
<?=ptech\pyrocms\widgets\Alert::widget();?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('_menu') ?>
    </div>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode($this->title) ?>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'profile-form',
                    'options' => ['class' => 'form-horizontal'],
                    'layout' => 'horizontal',
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
                        'labelOptions' => ['class' => 'col-lg-3 control-label'],
                        'horizontalCssClasses' => [
                            'wrapper' => 'col-sm-9',
                        ],
                    ],
            		'validationUrl'=>['/settings/profile-alert-ajax-validate'],
                    'enableAjaxValidation'   => true,
                    'enableClientValidation' => false,
                    'validateOnBlur'         => false,
                ]); ?>
                    <div class='row'>
                        <div class='col-xs-12 text-center'>
                            <h3> Default Location
                            <?=Html::a('Clear Default',['clear-location'],['class'=>'btn btn-danger btn-xs  pull-right']) ?></h3>
                            <small>This field is used to set a default location for initial map load. This address is not monitored for fires. If you wish this address to be monitored for fires please add it to your My Locations.</small>
                                <?= $form->field($defaultLocation, 'address')->input('text',
                                    [
                                        'id' => 'autocomplete',
                                        'onFocus' => 'geolocate();',
                                        'placeholder' => 'Search for place',

                                    ]) ?>
                                <?= Html::activeHiddenInput($defaultLocation, 'place_id') ?>
                                <?= Html::activeHiddenInput($defaultLocation, 'address') ?>
                                <?= Html::activeHiddenInput($defaultLocation, 'latitude') ?>
                                <?= Html::activeHiddenInput($defaultLocation, 'longitude') ?>

                            </div>
                        </div>

                    <div class='row text-center'>

                        <small class="text-center">Use these settings to configure how you want system notifications and alerts to be created.</small>
                        <label class="col-xs-12 text-center">Alert Preferences</label>
                        <small class="col-xs-12 text-center">Choose how far out from your locations you want us to search</small>
                    </div>

                    <?php /*\yii\bootstrap\Alert::widget([
                        'body' => $alertBody,
                        'options' => ['class'=> 'alert-danger alert fade in'],
                    ])*/ ?>

                    <?= $form->field($model, 'alert_dist')
                    ->textInput(['type' => 'number', 'min'=>"1", 'max'=>"100"])
                    ->label('Alert Search Radius') ?>

                    <!-- <h1 class='text-center legend-title'> Fire Class Alert Preferences</h1> -->
                    <div class="form-group">
                        <div class="col-lg-12">
                            <?= Html::submitButton( 'Update', ['class' => 'btn btn-block btn-success']) ?>
                        </div>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>


<script>
/*
jQuery(document).ready(function() {
    autocomplete_init();
    jQuery(document).on('click','.clear-profile-address', function(e){
        e.preventDefault();
        jQuery('#autocomplete').val('');
        jQuery('#defaultlocation-place_name').val('');
        jQuery('#defaultlocation-place_id').val('');
        jQuery('#defaultlocation-latitude').val('');
        jQuery('#defaultlocation-longitude').val('');
    });
});*/

// <-------------------------GOOGLE FUNCTIONS-------------------------------------->
// This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

var placeSearch, autocomplete,geocoder;
var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'short_name',
    country: 'long_name',
    postal_code: 'short_name'
};


function autocomplete_init() {
    console.log('loaded');
    // Create the autocomplete object, restricting the search
    // to geographical location types.
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {HTMLInputElement} */(document.getElementById('autocomplete')),
        {   types: ['geocode'],
            componentRestrictions: { country: "us" }
        });
    // When the user selects an address from the dropdown,
    // populate the address fields in the form.
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        //fillInLatLongAddress();
        console.log($('#autocomplete').val());
        getGoogleLocInfo($('#autocomplete').val());
    });
}


function getGoogleLocInfo(address){
     geocoder = new google.maps.Geocoder();
    //var address = $('#autocomplete').val();
    geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            str = results[0].geometry.location.toString();
            str = str.replace(/\(|\)/g,'');
            gpscoords = str.split(",");
            address = results[0].formatted_address;
            address = address.replace(', USA','');
            place_id = results[0].place_id;
            latitude = gpscoords[0];
            longitude = gpscoords[1];
            $('#defaultlocation-address').val(address);
            $('#defaultlocation-place_id').val(place_id);
            $('#defaultlocation-latitude').val(latitude);
            $('#defaultlocation-longitude').val(longitude);
        }
    });
}

// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var geolocation = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      var circle = new google.maps.Circle({
        center: geolocation,
        radius: position.coords.accuracy
      });
      autocomplete.setBounds(circle.getBounds());
    });
  }
}
</script>
