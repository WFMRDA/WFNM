<?php
// use yii\helpers\WfnmHelpers;
use yii\widgets\Menu;
use yii\helpers\Url;
use common\models\helpers\WfnmHelpers;
use common\widgets\PyroMenu;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\widgets\NotificationsWidget;

use frontend\assets\ArcGisAsset;
ArcGisAsset::register($this);

/* @var $this yii\web\View */
$this->title = Yii::$app->name;
$this->render('_disclaimer');
?>
<?= Yii::$app->appSystemData->disclaimer ? $this->render('_disclaimer') : '' ?>

<div id="app">
	<!-- Our component-->
	<typeahead
	 	v-show="!paneActive"
		:source="series"
		placeholder="Search Fire"
		filter-key="incidentName"
		:min-length="3"
		v-cloak
	>
	</typeahead>
	<div class='loader-container' v-show="loading"><div class="sk-wave loader"><div class="sk-rect sk-rect1"></div><div class="sk-rect sk-rect2"></div><div class="sk-rect sk-rect3"></div><div class="sk-rect sk-rect4"></div><div class="sk-rect sk-rect5"></div></div></div>
	<div v-cloak  v-show="!paneActive" class='toolbar-container'>
		<div class='toolbar-overlay'>
			<ul class='list-unstyled toolbar'>
				<li>
					<button data-toggle="tooltip" data-placement="top" title="Layers" @click="activatePane('layers')" class='toolbar-btn layers' :class="{ active: activePane == 'layers' }"><?=Html::img('@web/img/maplayers_btn.png',['class'=>'sidebar-fa-ms-png center'])?></button>
				</li>
				<li>
					<button data-toggle="tooltip" data-placement="top" title="My Fires" @click="activatePane('myFires')" class='toolbar-btn myFires' :class="{ active: activePane == 'myFires' }"><?=Html::img('@web/img/my_fires_btn.png',['class'=>'sidebar-fa-ms-png center'])?></button>
				</li>
				<li>
					<button data-toggle="tooltip" data-placement="top" title="My Locations" @click="activatePane('myLocations')" class='toolbar-btn myLocations' :class="{ active: activePane == 'myLocations' }"><?=Html::img('@web/img/my_locations_btn.png',['class'=>'sidebar-fa-ms-png center'])?></button>
				</li>
				<li>
					<button data-toggle="tooltip" data-placement="top" title="Situational Report" @click="activatePane('sitRep')" class='toolbar-btn sitRep' :class="{ active: activePane == 'sitRep' }"><?=Html::img('@web/img/sitrep_btn.png',['class'=>'sidebar-fa-ms-png center'])?></button>
				</li>
				<li>
					<button data-toggle="tooltip" data-placement="top" title="Alerts" @click="activatePane('alerts')" class='toolbar-btn alerts' :class="{ active: activePane == 'alerts' }"><?=Html::img('@web/img/alerts_btn.png',['class'=>'sidebar-fa-ms-png center'])?></button>
				</li>
				<li>
					<button data-toggle="tooltip" data-placement="top" title="Wildfres Near Me" @click="activatePane('wfnm')" class='toolbar-btn wfnm' :class="{ active: activePane == 'wfnm' }"><?=Html::img('@web/img/wfnm_btn.png',['class'=>'sidebar-fa-ms-png center'])?></button>
				</li>
			</ul>
		</div>
		<div class='basebar'></div>
	</div>
	<div v-cloak  class='legend thumbnail'>
		<div v-show='ercLayer.active'>
			<label>Fire Behavior Potential</label>
			<br>
			<img src='https://www.wfas.net/cgi-bin/mapserv?map=/var/www/html/nfdr/mapfiles/ndfd_geog5.map&SERVICE=WMS&VERSION=1.3.0&SLD_VERSION=1.1.0&REQUEST=GetLegendGraphic&FORMAT=image/jpeg&LAYER=bi0percnew&STYLE='>
		</div>
		<div v-show='biLayer.active'>
			<label>Spatial Preparedness Level</label>
			<br>
			<img src='https://www.wfas.net/cgi-bin/mapserv?map=/var/www/html/nfdr/mapfiles/ndfd_geog5.map&SERVICE=WMS&VERSION=1.3.0&SLD_VERSION=1.1.0&REQUEST=GetLegendGraphic&FORMAT=image/jpeg&LAYER=erc0percnew&STYLE='>
		</div>
		<div v-show='sfwpLayer.active'>
			<label>Severe Fire Weather Potential</label>
			<br>
			<img src='https://www.wfas.net/cgi-bin/mapserv?map=/var/www/html/nfdr/mapfiles/ndfd_geog5.map&SERVICE=WMS&VERSION=1.3.0&SLD_VERSION=1.1.0&REQUEST=GetLegendGraphic&FORMAT=image/jpeg&LAYER=fbxday0&STYLE='>
		</div>
	</div>
	<div v-cloak  class='weather-legend' v-show='weatherLayer.active && !paneActive'>
		<label>{{ radarTime }}</label>
		<br>
		<img src="https://nowcoast.noaa.gov/images/legends/radar.png" alt="legend">
	</div>



	<!-- LAYERS PANE -->
	<transition name="slide-in-left" v-on:after-enter="clearLoading">
		<div v-cloak id='layersPane' v-show="!showIncidentLayers && activePane == 'layers' && !paneActive">
			<div id="legendContainer">
			  	<div id="layerBtns">
					<ul>
						<li v-for="layer in layers" :key='layer.id' :class="{ active: layer.active }">
							<span @click="toggleLayer(layer.id)" class="toggleSpan" data-toggle="tooltip" data-placement="right" :title="layer.name"> {{ layer.name }}</span>
						</li>
					</ul>
				</div>
			  	<div id="close"  @click="activatePane('layers')">
					<div class="text-center">
						<i class="glyphicon glyphicon-menu-left" aria-hidden="true"></i>
						<i class="glyphicon glyphicon-menu-left" aria-hidden="true"></i>
						<i class="glyphicon glyphicon-menu-left" aria-hidden="true"></i>
					</div>
				</div>
			</div>
		</div>
 	</transition>

	<!-- INCIDENTS SELECTION PANE -->
	<transition name="slide-in-left" v-on:after-enter="clearLoading">
			<div v-cloak id='layersIncidentPane' v-show="incidentsLayer.active && activePane == 'layers' && !paneActive">
			<!-- <i class="glyphicon glyphicon-menu-left" @click="toggleLayer(incidentsLayer.id)"  aria-hidden="true"></i> -->
			<button type="button" class="close closePanel btn-block" aria-label="Close" @click="toggleLayer(incidentsLayer.id)">
				<i class="glyphicon glyphicon-menu-left" aria-hidden="true"></i>
				<i class="glyphicon glyphicon-menu-left" aria-hidden="true"></i>
				<i class="glyphicon glyphicon-menu-left" aria-hidden="true"></i>
			</button>

			<!-- <button @click="toggleLayer(incidentsLayer.id)" class="btn btn-default exit button" ><i class="glyphicon glyphicon-menu-left" aria-hidden="true"></i></button> -->

			<div class='legendOptions-container'>
				<div id="maplegendform-firesizelist" class=" col-xs-12" name="fireSize">
					<h3>Fire Size</h3>
					<div class="checkbox"><label><input type="checkbox" name="fireSize[]" value="1" v-model="activeIncidentLayers"> <div class="checkmark"></div> <div class='size-marker sizeClass-1'></div> < 99ac</label></div>
					<div class="checkbox"><label><input type="checkbox" name="fireSize[]" value="2" v-model="activeIncidentLayers"> <div class="checkmark"></div> <div class='size-marker sizeClass-2'></div> 100ac - 999ac</label></div>
					<div class="checkbox"><label><input type="checkbox" name="fireSize[]" value="3" v-model="activeIncidentLayers"> <div class="checkmark"></div> <div class='size-marker sizeClass-3'></div> 1000ac - 9999ac</label></div>
					<div class="checkbox"><label><input type="checkbox" name="fireSize[]" value="4" v-model="activeIncidentLayers"> <div class="checkmark"></div> <div class='size-marker sizeClass-4'></div> 10000ac - 99999ac</label></div>
					<div class="checkbox"><label><input type="checkbox" name="fireSize[]" value="5" v-model="activeIncidentLayers"> <div class="checkmark"></div> <div class='size-marker sizeClass-5'></div> >= 100000 ac</label></div>
					<h3>Fire Status</h3>
					<div class="checkbox"><label><input type="checkbox" name="fireClass[]" value="A" v-model="activeIncidentLayers"> <div class="checkmark"></div> <?=Html::img('@media/map_new_fire.png',['class'=>'legend-icon'])?> NEW</label></div>
					<div class="checkbox"><label><input type="checkbox" name="fireClass[]" value="B" v-model="activeIncidentLayers"> <div class="checkmark"></div> <?=Html::img('@media/map_emerging_fire.png',['class'=>'legend-icon'])?> EMERGING</label></div>
					<div class="checkbox"><label><input type="checkbox" name="fireClass[]" value="E" v-model="activeIncidentLayers"> <div class="checkmark"></div> <?=Html::img('@media/map_active_fire.png',['class'=>'legend-icon'])?> ACTIVE</label></div>
					<div class="checkbox"><label><input type="checkbox" name="fireClass[]" value="C" v-model="activeIncidentLayers"> <div class="checkmark"></div> <?=Html::img('@media/map_contained_fire.png',['class'=>'legend-icon'])?> CONTAINED</label></div>
					<div class="checkbox"><label><input type="checkbox" name="fireClass[]" value="D" v-model="activeIncidentLayers"> <div class="checkmark"></div> <?=Html::img('@media/map_controlled_fire.png',['class'=>'legend-icon'])?> CONTROLLED</label></div>
					<div class="checkbox"><label><input type="checkbox" name="fireClass[]" value="CX" v-model="activeIncidentLayers"> <div class="checkmark"></div> <?=Html::img('@media/map_complex.png',['class'=>'legend-icon'])?> COMPLEXES</label></div>
				</div>
			</div>
		</div>
	</transition>





	<transition name="slide-in-right" v-on:after-enter="panMapToCenter" v-on:after-leave="clearFireMarker"  >
		<div v-cloak ref="fireInfoPanelContainer" id='fireInfo-pane-container' v-show="showFireInfo" class='fireInfo-panel-container col-xs-12 col-sm-8 col-md-7 col-lg-6' :class="{ tickerLive: ticker.length}">
			<button type="button" class="close closePanel" aria-label="Close" @click="showFireInfo = false"><span aria-hidden="true">&times;</span></button>
			<div>
				<h3  v-show="fireInfo.incidentTypeCategory =='WF'" class='title'>{{ fireInfo.incidentName }} Fire <br> <span v-show="fireInfo.complexParentIrwinId != null"><span class='complexMeta'>(Part of <span class='complex-link' @click="getFireInfo(fireInfo.complex,'CX')">{{ incidentInfo.complexIncidentName }} Complex</span>)</span></span>
				</h3>
				<h3 v-show="fireInfo.incidentTypeCategory =='CX'" class='title'>{{ getValue(fireInfo,"incidentName",'').toString().replaceAll("Complex", "") }} Complex</h3>
				<div class='header-info-block text-center'>
					<!-- Fire Status -->
					<div class='info-item'>
						<label>Fire Status</label>
						<h4><img class="table-fire-logo" :src="incidentInfo.fireStatusUrl" alt=""> {{ fireInfo.fireClass }} </h4>
					</div>
					<!-- Fire State -->
					<div class='info-item'>
						<label>Complexity</label>
						<h4>{{ incidentInfo.complexity }}</h4>
					</div>
					<!-- Is Following -->
					<div @click="toggleFireFollow(fireInfo.irwinID)" class='info-item btn toggleFireFollowBtn' :class="incidentInfo.toggleFollowFireClass">
						<div class='status'>
							<span v-show="isFollowing">Unfollow Fire</span>
							<span v-show="!isFollowing">Follow Fire</span>
						</div>
					</div>
				</div>
				<div class='header-PL text-center'>
					<!-- GACC PL Level -->
					<div class='info-item'>
						<label>{{ incidentInfo.gaccName }}</label>
						<a class="link-dark pl-text" :href="incidentInfo.gaccUrl" target="_blank">
							<b>P</b><span class="hidden-xs hidden-sm">reparedness </span><b>L</b><span class="hidden-xs">evel</span> <span :class="incidentInfo.localGaccPlLevel"></span>
						</a>
					</div>
				</div>
				<div class='text-center'>
					<div class="btn-group  " role="group">
						<!-- <div class="btn-group" role="group"> -->
		                    <button :class="{active: activeFireInfoTab == 'index'}"  @click="activeFireInfoTab = 'index'" type="button" class="btn btn-default">General Info</button>
		                <!-- </div> -->
		                <!-- <div class="btn-group" role="group"> -->
		                    <button :class="{active: activeFireInfoTab == 'info'}"  @click="activeFireInfoTab = 'info'" type="button" class="btn btn-default">Incident Info</button>
		                <!-- </div> -->
		                <!-- <div  v-show="fireInfo.incidentTypeCategory =='CX'" class="btn-group" role="group"> -->
		                    <button v-show="fireInfo.incidentTypeCategory =='CX'" :class="{active: activeFireInfoTab == 'childFires'}"  @click="activeFireInfoTab = 'childFires'" type="button" class="btn btn-default">Complex Fires</button>
		                <!-- </div> -->
		                <!-- <div class="btn-group" role="group" > -->
		                    <button :class="{active: activeFireInfoTab == 'comments'}"  @click="activeFireInfoTab = 'comments'" type="button" class="btn btn-default">Comments</button>
		                <!-- </div> -->
		            </div>
				</div>
				<div class='panel panel-default' v-show="activeFireInfoTab == 'index'">
					<div class="panel-heading text-center">
					    <h3 class="panel-title">General Information</h3>
				  	</div>
					<div class='panel-body'>
						<dl class="dl-horizontal meta-info-block">
							<dt>Incident Number:</dt><dd>  {{ fireInfo.uniqueFireIdentifier }}</dd>
							<span v-show="fireInfo.incidentShortDescription != null">
								<dt>Short Description:</dt><dd>{{ fireInfo.incidentShortDescription }} </dd>
							</span>
							<dt>Discovered At:</dt><dd>   {{ formatDateTime(fireInfo.fireDiscoveryDateTime) }}</dd>
							<dt>Last Updated At:</dt><dd>   {{ formatDateTime(fireInfo.modifiedOnDateTime) }}</dd>
							<dt>Last Updated By:</dt><dd>   {{ incidentInfo.modifiedBySystem }}</dd>
							<span v-show="fireInfo.containmentDateTime != null">
								<dt>Containment Date:</dt><dd>   {{ formatDateTime(fireInfo.containmentDateTime) }}</dd>
							</span>
							<span v-show="fireInfo.controlDateTime != null">
								<dt>Control Date:</dt><dd>   {{ formatDateTime(fireInfo.controlDateTime) }}</dd>
							</span>
							<span v-show="fireInfo.fireOutDateTime != null">
								<dt>Fire Out Date:</dt><dd>   {{ formatDateTime(fireInfo.fireOutDateTime) }}</dd>
							</span>
							<dt>Acres:</dt><dd>   {{ incidentInfo.acres }}</dd>

							<span v-show="fireInfo.percentContained != null">
								<dt>Percent Contained:</dt><dd>   {{ fireInfo.percentContained }}</dd>
							</span>
							<dt>Cause:</dt><dd>  {{ incidentInfo.fireCause }}</dd>
							<dt>Location Coordinates:</dt><dd>   {{ incidentInfo.location }}</dd>
							<dt>County/State:</dt><dd> {{ incidentInfo.countyState }}</dd>
							<span v-show="incidentInfo.landOwnership !==''">
								<dt>LandOwnership:</dt><dd> {{ incidentInfo.landOwnership }} </dd>
							</span>
							<span v-show="fireInfo.pooProtectingUnit != null">
								<dt>Protecting Unit:</dt><dd>{{ fireInfo.pooProtectingUnit }} </dd>
							</span>
						</dl>
					</div>
				</div>
				<div class='incident-info panel panel-default' v-show="activeFireInfoTab == 'info'">
					<div class="panel-heading text-center">
						<h3 class="panel-title">Incident Information</h3>
					</div>
					<div class='panel-body'>
						<dl class="dl-horizontal meta-info-block">
							<span v-show="fireInfo.fireBehaviorDescription != null || incidentInfo.fuelModel != '' ">
								<dt>Fuel Model:</dt><dd>  {{capitalizeFirstLetter(fireInfo.fireBehaviorDescription)}} {{ incidentInfo.fuelModel }}</dd>
							</span>
							<span v-show="fireInfo.summaryFuelModel != null || incidentInfo.fireBehavior != '' ">
								<dt>Fire Behavior:</dt><dd> {{capitalizeFirstLetter(fireInfo.summaryFuelModel)}} {{ incidentInfo.fireBehavior }}</dd>
							</span>
							<span v-show="fireInfo.fireBehavior != null">
								<dt>Est. Containment <br>Date:</dt><dd> {{ formatDate(fireInfo.fireBehavior) }} </dd>
							</span>
						</dl>
						<div v-show="fireInfo.incidentShortDescription != null" class='container-fluid'>
							<h5 class="text-center title">Incident Description</h5>
							{{ capitalizeFirstLetter(fireInfo.incidentShortDescription) }}
						</div>
						<div v-show="fireInfo.ics209Remarks != null" class='container-fluid'>
							<h5 class="text-center title">209 Remarks</h5>
							<dl class="dl-horizontal meta-info-block">
								<dt>From:</dt><dd>  {{ formatDateTime(fireInfo.ics209ReportForTimePeriodFrom) }}</dd>
								<dt>To:</dt><dd>  {{ formatDateTime(fireInfo.ics209ReportForTimePeriodTo) }}</dd>
							</dl>
							{{ capitalizeFirstLetter(fireInfo.ics209Remarks) }}
						</div>
						<div v-show="fireInfo.significantEvents != null" class='container-fluid'>
							<h5 class="text-center title">Significant Events</h5>
							{{ capitalizeFirstLetter(fireInfo.significantEvents) }}
						</div>
						<div v-show="fireInfo.weatherConcerns != null" class='container-fluid'>
							<h5 class="text-center title">Weather Concerns</h5>
							{{ capitalizeFirstLetter(fireInfo.weatherConcerns) }}
						</div>
						<div v-show="fireInfo.plannedActions != null" class='container-fluid'>
							<h5 class="text-center title">Planned Actions</h5>
							{{ capitalizeFirstLetter(fireInfo.plannedActions) }}
						</div>
						<div v-show="fireInfo.projectedIncidentActivity24 != null" class='container-fluid'>
							<h5 class="text-center title">Projected Activity (24Hr)</h5>
							{{ capitalizeFirstLetter(fireInfo.projectedIncidentActivity24) }}
						</div>
						<div v-show="fireInfo.projectedIncidentActivity48 != null" class='container-fluid'>
							<h5 class="text-center title">Projected Activity (48Hr)</h5>
							{{ capitalizeFirstLetter(fireInfo.projectedIncidentActivity48) }}
						</div>
						<div v-show="fireInfo.projectedIncidentActivity72 != null" class='container-fluid'>
							<h5 class="text-center title">Projected Activity (72Hr)</h5>
							{{ capitalizeFirstLetter(fireInfo.projectedIncidentActivity72) }}
						</div>
						<div v-show="fireInfo.projectedIncidentActivity72Plus != null" class='container-fluid'>
							<h5 class="text-center title">Projected Activity (72+Hr)</h5>
							{{ capitalizeFirstLetter(fireInfo.projectedIncidentActivity72Plus) }}
						</div>
						<div v-show="fireHasInfo" class='text-center'>
							No Information Has Been Reported About This Incident So Far
						</div>
					</div>
				</div>
				<div class='panel panel-default complexChildFires' v-show="activeFireInfoTab == 'childFires'">
					<div class="panel-heading text-center">
					    <h3 class="panel-title">Complex Fires</h3>
					</div>
					<div class=''>
						<table class="table table-hover table-condensed ">
							<thead>
								<tr>
									<th>Name</th>
									<th>Status</th>
									<th>Acres</th>
									<th>Last Updated</th>
								</tr>
							</thead>
						  	<tr @click="getFireInfo(fire,'WF')" class='childFireRow' v-for="fire in fireInfo.childFires">
								<td><b>{{ fire.incidentName }}</b></td>
								<td><img class="table-fire-logo" :src="getFireIcon(fire.fireClassId)" alt=""> {{ fire.fireClass }} </td>
								<td>{{  formatAcres(fire.dailyAcres) }}</td>
								<td>{{ formatDateTime(fire.modifiedOnDateTime) }}</td>
							</tr>
						</table>
				  	</div>
				</div>
				<div id='commment-tab' class='panel panel-default' v-show="activeFireInfoTab == 'comments'">
					<div class="panel-heading text-center">
					    <h3 class="panel-title">Comments</h3>
				  	</div>
					<div class='panel-body'>
	                    <div
							class="fb-comments"
							:data-href="incidentInfo.commentsUrl"
							data-width="100%"
							data-colorscheme='light'
							data-numposts="10">
						</div>
					</div>
				</div>
			</div>
		</div>
	</transition>




	<transition name="slide-in-right" v-on:after-enter="clearLoading">
		<div v-cloak  v-show="infoPaneActive" class='col-xs-12 col-sm-8 col-md-7 col-lg-6 fireInfo-panel-container' :class="{ tickerLive: ticker.length}">
			<button type="button" class="close closePanel" aria-label="Close" @click="activePane = ''"><span aria-hidden="true">&times;</span></button>





			<!-- My Fires -->
			<div v-show="activePane == 'myFires'" class='myFires'>
				<div class='col-xs-12 text-center'>
					<h3>My Fires</h3>
				</div>
				<div class=''>
					<table class="table table-hover table-condensed ">
						<thead>
							<tr>
								<th>Name</th>
								<th>Status</th>
								<th>Acres</th>
								<th>Last Updated</th>
							</tr>
						</thead>
					  	<tr class='childFireRow' v-for="(fire,index) in myFires" :key="index">
							<td @click="getFireInfo(fire,'WF')"><b>{{ fire.incidentName }}</b></td>
							<td @click="getFireInfo(fire,'WF')"><img class="table-fire-logo" :src="getFireIcon(fire.fireClassId)" alt=""> {{ fire.fireClass }} </td>
							<td @click="getFireInfo(fire,'WF')">{{  formatAcres(fire.dailyAcres) }}</td>
							<td @click="getFireInfo(fire,'WF')">{{ formatDateTime(fire.modifiedOnDateTime) }}</td>
							<td><button @click="unFollowFire(fire.irwinID)" class='btn btn-xs btn-danger'><i class="fa fa-trash" aria-hidden="true"></i></button> </td>
						</tr>
					</table>
			  	</div>
			</div>


			<!-- ALERTS -->
			<div v-show="activePane == 'alerts'" class='alerts'>
				<div class='row'>
					<div class='col-xs-12 text-center'>
						<h3>Alerts</h3>
					</div>
				</div>
				<div class='row'>
					<div id="notifications-panel-div">
						<li @click="gotoAlert(alert)" :class="{ unread : empty(alert.seen_at) , read: !empty(alert.seen_at) }" v-for="(alert,index) in myAlerts" :key="index">
							<p class="notifications ">
								<i class="fa fa-clock-o">{{ alert.timeLapse }}</i>
								<i class="fa fa-exclamation-triangle text-green" ></i> {{ alert.subject }}
				            </p>
						</li>
					</div>
				</div>
			</div>


			<!-- SITUATION REPORT -->
			<div v-show="activePane == 'sitRep'" class='sitRep row'>
				<!-- <div class='col-xs-12'> -->
					<h3 class='title'>Situational Report</h3>
					<div class='overview col-xs-12'>
						<div class="pl-level-report-box">
							<p class="pl-level-num">{{ plLevel }}</p>
							<p class="pl-level-title">P<span class="hidden-xs hidden-sm">reparedness</span> L<span class="hidden-xs">evel</span> </p>
						</div>
						<div class="pl-level-data-box">
							<p class='panel-box-title'>{{  getValue(sitReport,'NLI.label','') }} <span class="panel-box-text "> {{  getValue(sitReport,'NLI.val','') }} </span> </p>
							<p class="panel-box-title">{{  getValue(sitReport,'LFC.label','') }} <span class="panel-box-text "> {{  getValue(sitReport,'LFC.val','') }}  </span> </p>
							<p class="panel-box-title">{{  getValue(sitReport,'ULF.label','') }}<span class="panel-box-text "> {{  getValue(sitReport,'ULF.val','') }}  </span> </p>
							<p class="panel-box-title">{{  getValue(sitReport,'ACTC.label','') }}<span class="panel-box-text "> {{  getValue(sitReport,'ACTC.val','') }}  </span> </p>
							<p class="panel-box-title">{{  getValue(sitReport,'NIMO.label','') }}<span class="panel-box-text "> {{  getValue(sitReport,'NIMO.val','') }}  </span> </p>
							<p class="panel-box-title">{{  getValue(sitReport,'IMTS-I.label','') }}<span class="panel-box-text "> {{  getValue(sitReport,'IMTS-I.val','') }}  </span> </p>
							<p class="panel-box-title">{{  getValue(sitReport,'IMTS-II.label','') }}<span class="panel-box-text "> {{  getValue(sitReport,'IMTS-II.val','') }}   </span> </p>
							<p class="panel-box-title">{{  getValue(sitReport,'IAACT.label','') }}<span class="panel-box-text ">{{  getValue(sitReport,'IAACT.val','') }} </span></p>
						</div>
					</div>
					<div class='col-xs-12 text-center report-btn-container'>
						<a class='btn btn-default sit-btn' href='https://www.nifc.gov/nicc/sitreprt.pdf' target='_blank'>'View Full Sit Report</a>
					</div>
					<div class='col-xs-12'>
						<div class="col-xs-12">
							<div class="legendOptions" name="fireClass">
								<h3>Fire Status</h3>
								<div class='col-xs-12 col-sm-6'>
									<div class="checkbox"><label><input type="checkbox" name="fireClass[]" value="A" v-model="sitReportFilter"> <div class="checkmark"></div> <?=Html::img('@media/map_new_fire.png',['class'=>'legend-icon'])?> NEW</label></div>
									<div class="checkbox"><label><input type="checkbox" name="fireClass[]" value="B" v-model="sitReportFilter"> <div class="checkmark"></div> <?=Html::img('@media/map_emerging_fire.png',['class'=>'legend-icon'])?> EMERGING</label></div>
									<div class="checkbox"><label><input type="checkbox" name="fireClass[]" value="E" v-model="sitReportFilter"> <div class="checkmark"></div> <?=Html::img('@media/map_active_fire.png',['class'=>'legend-icon'])?> ACTIVE</label></div>
								</div>
								<div class='col-xs-12 col-sm-6'>
									<div class="checkbox"><label><input type="checkbox" name="fireClass[]" value="C" v-model="sitReportFilter"> <div class="checkmark"></div> <?=Html::img('@media/map_contained_fire.png',['class'=>'legend-icon'])?> CONTAINED</label></div>
									<div class="checkbox"><label><input type="checkbox" name="fireClass[]" value="D" v-model="sitReportFilter"> <div class="checkmark"></div> <?=Html::img('@media/map_controlled_fire.png',['class'=>'legend-icon'])?> CONTROLLED</label></div>
									<div class="checkbox"><label><input type="checkbox" name="fireClass[]" value="CX" v-model="sitReportFilter"> <div class="checkmark"></div> <?=Html::img('@media/map_complex.png',['class'=>'legend-icon'])?> COMPLEXES</label></div>
								</div>
							</div>
					    	<div class="form-group">
								<select v-model="sitReportType"  class="form-control">
									<option v-for="option in sitReportTypeOptions" v-bind:value="option.val">
			    						{{ option.label }}
			  						</option>
								</select>
							</div>
						</div>
						<div class=''>
							<table id="sitReportTable" class="table table-hover table-condensed ">
								<thead>
									<tr>
										<th>Fire ClassId</th>
										<th>Name</th>
										<th>Status</th>
										<th class="text-capitalize">{{splitOnCapitolLetter(sitReportType) }}</th>
										<th>Last Updated</th>
									</tr>
								</thead>
							  	<tr class='childFireRow' v-for="(fire,index) in fireDb" :key="index">
									<td >{{ fire.fireClassId }}</td>
									<td @click="getFireInfo(fire,'WF')"><b>{{ fire.incidentName }}</b></td>
									<td @click="getFireInfo(fire,'WF')"><img class="table-fire-logo" :src="getFireIcon(fire.fireClassId)" alt=""> {{ fire.fireClass }} </td>
									<td :data-order="empty(fire[sitReportType])?0:fire[sitReportType]" @click="getFireInfo(fire,'WF')">{{  formatSitReportInfo(fire[sitReportType]) }}</td>
									<td @click="getFireInfo(fire,'WF')">{{ formatDateTime(fire.modifiedOnDateTime) }}</td>
								</tr>
							</table>
					  	</div>
						<!-- <ul>
							<li v-for="(fire,index) in sitReportData" :key="index">{{ fire.incidentName }} {{ fire[sitReportType] }} {{ sitReportType }}</li>
						</ul> -->
					</div>
				<!-- </div> -->
			</div>





			<!-- MY LOCATIONS -->
			<div v-show="activePane == 'myLocations'" class='myLocations col-xs-12'>
				<div class='col-xs-12 text-center'>
					<h3>My Locations</h3>
				</div>
				<div class=''>
					<div class="col-xs-11 col-md-10 col-centered">
		                <div class="input-group">
							<input type="text" id="addressInput" class="form-control" name="MyLocationsForm[address]" placeholder="Enter your address" @focus="geolocate()"  aria-required="true" autocomplete="off" >
		                    <span class="input-group-btn">
		                        <button class="btn btn-default" @click='addLocation()' type="button"><i class="fa fa-plus" aria-hidden="true">Add</i></button>
		                    </span>
		                </div>
		            </div>
					<table class="table table-hover table-condensed ">
						<thead>
							<tr>
								<th>Address</th>
								<th>Date Added</th>
							</tr>
						</thead>
						<tr class='childFireRow' v-for="(loc,index) in myLocationList" :key="index" :class="{'text-center': loc.default}">
							<td v-if="loc.default" colspan="3" @click="setLoc(loc)">{{ loc.address }}<small><em>(Current Location)</em></small></td>
							<td v-if="loc.default == undefined" @click="setLoc(loc)">{{ loc.address }}</td>
							<td v-if="loc.default == undefined"@click="setLoc(loc)">{{ formatDateTime(loc.created_at,true) }}</td>
							<td v-if="loc.default == undefined"><button @click="removeLocation(loc)" class='btn btn-xs btn-danger'><i class="fa fa-trash" aria-hidden="true"></i></button> </td>
						</tr>
					</table>
				</div>
			</div>









			<!-- FIRES NEAR ME -->
			<div v-show="activePane == 'wfnm'" class='wfnm row'>
				<div class='col-xs-12 text-center'>
					<h3>Fires Near Me</h3>
					<div class="row">
					  	<div class="col-xs-12">
					    	<div class="form-group">
								<select v-model="userLocation"  class="form-control">
									<option v-for="loc in myLocationList" v-bind:value="loc.address+'*|*'+loc.latitude+'*|*'+loc.longitude">
			    						{{ loc.address }}
			  						</option>
								</select>
							</div>
						</div>
					</div>
					<div class='row'>
						<div class='col-xs-12'>
							<div class='header-PL text-center'>
								<div class='info-item'>
									<label>{{ localGaccPlLevel.name }}</label>
									<a class="link-dark pl-text" :href="localGaccPlLevel.gaccUrl" target="_blank">
										<b>P</b><span class="hidden-xs hidden-sm">reparedness </span><b>L</b><span class="hidden-xs">evel</span> <span :class="localGaccPlLevel.class"></span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class='col-xs-12'>
					<!-- <div class="btn-group btn-group-justified panel-btn-group-justified" role="group">
						<div class="btn-group" role="group">
		                    <button :class="{active: wfnmTab == 'index'}"  @click="wfnmTab = 'index'" type="button" class="btn btn-default">General Info</button>
		                </div>
		                <div class="btn-group" role="group">
		                    <button :class="{active: wfnmTab == 'weather'}"  @click="wfnmTab = 'weather'" type="button" class="btn btn-default">Weather Info</button>
		                </div>
		            </div> -->
					<div class='firesnearme' v-show="wfnmTab == 'index'">
						<div v-show="!firesNearMe.length" class='col-xs-12 text-center'>
							We found no incidents near this location
						</div>
						<div v-show="firesNearMe.length" >
							<div id="wfnmFireClass-firestatuslist" class="legendOptions" name="fireClass">
								<h3>Fire Status</h3>
								<div class='col-xs-12 col-sm-6'>
									<div class="checkbox"><label><input type="checkbox" name="fireClass[]" value="A" v-model="wfnmFilter"> <div class="checkmark"></div> <?=Html::img('@media/map_new_fire.png',['class'=>'legend-icon'])?> NEW</label></div>
									<div class="checkbox"><label><input type="checkbox" name="fireClass[]" value="B" v-model="wfnmFilter"> <div class="checkmark"></div> <?=Html::img('@media/map_emerging_fire.png',['class'=>'legend-icon'])?> EMERGING</label></div>
									<div class="checkbox"><label><input type="checkbox" name="fireClass[]" value="E" v-model="wfnmFilter"> <div class="checkmark"></div> <?=Html::img('@media/map_active_fire.png',['class'=>'legend-icon'])?> ACTIVE</label></div>
								</div>
								<div class='col-xs-12 col-sm-6'>
									<div class="checkbox"><label><input type="checkbox" name="fireClass[]" value="C" v-model="wfnmFilter"> <div class="checkmark"></div> <?=Html::img('@media/map_contained_fire.png',['class'=>'legend-icon'])?> CONTAINED</label></div>
									<div class="checkbox"><label><input type="checkbox" name="fireClass[]" value="D" v-model="wfnmFilter"> <div class="checkmark"></div> <?=Html::img('@media/map_controlled_fire.png',['class'=>'legend-icon'])?> CONTROLLED</label></div>
									<div class="checkbox"><label><input type="checkbox" name="fireClass[]" value="CX" v-model="wfnmFilter"> <div class="checkmark"></div> <?=Html::img('@media/map_complex.png',['class'=>'legend-icon'])?> COMPLEXES</label></div>
								</div>
							</div>
								<table id='firesnearmeTable' class="table table-hover table-condensed ">
									<thead>
										<tr>
											<th>Name</th>
											<th>Di<span>stance</span></th>
											<th>Stat<span>us</span></th>
											<th>Type</th>
											<th>Ac<span>res</span></th>
											<th><span>Last</span> Updated</th>
										</tr>
									</thead>
								  	<tr class='childFireRow' v-for="fire in firesNearMe">
										<td @click="getFireInfo(fire,'WF')"><b>{{ fire.incidentName }}</b></td>
										<td @click="getFireInfo(fire,'WF')">{{ precisionRound(fire.distance,1) }}</td>
										<td @click="getFireInfo(fire,'WF')"><img class="table-fire-logo" :src="getFireIcon(fire.fireClassId)" alt=""> {{ fire.fireClass }} </td>
										<td >{{ fire.fireClassId }}</td>
										<td @click="getFireInfo(fire,'WF')">{{  formatAcres(fire.dailyAcres) }}</td>
										<td @click="getFireInfo(fire,'WF')">{{ formatDateTime(fire.modifiedOnDateTime) }}</td>
									</tr>
								</table>
						</div>
					</div>
					<!-- <div class='panel panel-default' v-show="wfnmTab == 'weather'">
						<div class="panel-heading text-center">
							<h3 class="panel-title">Weather Information</h3>
						</div>
						<div class='panel-body'>
						</div>
					</div> -->
				</div>
			</div>
		</div>
	</transition>
	<!-- <div v-cloak  id='info-ticker' class="marquee" v-show="ticker.length">
		<div>
			<span v-for="item of ticker">{{ item }}</span>
		</div>
	</div> -->
	<div id="map"></div>
</div>
