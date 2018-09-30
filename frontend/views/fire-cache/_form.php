<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\fireCache\FireCache */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fire-cache-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'irwinID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'recordSource')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'createdBySystem')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'createdOnDateTime')->textInput() ?>

    <?= $form->field($model, 'modifiedBySystem')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'modifiedOnDateTime')->textInput() ?>

    <?= $form->field($model, 'inConflict')->textInput() ?>

    <?= $form->field($model, 'conflictParentIrwinId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'uniqueFireIdentifier')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fireDiscoveryDateTime')->textInput() ?>

    <?= $form->field($model, 'pooProtectingUnit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'localIncidentIdentifier')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dispatchCenterId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'incidentName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fireCause')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'incidentTypeKind')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'incidentTypeCategory')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'initialLatitude')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'initialLongitude')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'discoveryAcres')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pooLatitude')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pooLongitude')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pooJurisdictionalUnit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pooState')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pooCounty')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pooFips')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pooLandownerKind')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pooLandownerCategory')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'initialResponseAcres')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'initialFireStrategy')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'firecodeRequested')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'abcdMisc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fireCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fsJobCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fsOverrideCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isComplex')->textInput() ?>

    <?= $form->field($model, 'complexParentIrwinId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isFSAssisted')->textInput() ?>

    <?= $form->field($model, 'isMultiJurisdictional')->textInput() ?>

    <?= $form->field($model, 'isTrespass')->textInput() ?>

    <?= $form->field($model, 'isReimbursable')->textInput() ?>

    <?= $form->field($model, 'dailyAcres')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'calculatedAcres')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'totalIncidentPersonnel')->textInput() ?>

    <?= $form->field($model, 'fireMgmtComplexity')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'incidentCommanderName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'incidentManagementOrganization')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fatalities')->textInput() ?>

    <?= $form->field($model, 'injuries')->textInput() ?>

    <?= $form->field($model, 'residencesDestroyed')->textInput() ?>

    <?= $form->field($model, 'residencesThreatened')->textInput() ?>

    <?= $form->field($model, 'otherStructuresDestroyed')->textInput() ?>

    <?= $form->field($model, 'otherStructuresThreatened')->textInput() ?>

    <?= $form->field($model, 'estimatedCostToDate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'estimatedContainmentDate')->textInput() ?>

    <?= $form->field($model, 'percentContained')->textInput() ?>

    <?= $form->field($model, 'percentPerimeterToBeContained')->textInput() ?>

    <?= $form->field($model, 'ics209ReportDateTime')->textInput() ?>

    <?= $form->field($model, 'ics209ReportStatus')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'containmentDateTime')->textInput() ?>

    <?= $form->field($model, 'controlDateTime')->textInput() ?>

    <?= $form->field($model, 'fireOutDateTime')->textInput() ?>

    <?= $form->field($model, 'finalAcres')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gacc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isValid')->textInput() ?>

    <?= $form->field($model, 'adsPermissionState')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'unitIDValidation')->textInput() ?>

    <?= $form->field($model, 'incidentShortDescription')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'significantEvents')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'primaryFuelModel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'weatherConcerns')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'projectedIncidentActivity12')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'plannedActions')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ics209Remarks')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ics209ReportForTimePeriodFrom')->textInput() ?>

    <?= $form->field($model, 'ics209ReportForTimePeriodTo')->textInput() ?>

    <?= $form->field($model, 'pooCity')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pooIncidentJurisdictionalAgency')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pooLegalDescQtrQtr')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pooLegalDescQtr')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pooLegalDescRange')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pooLegalDescSection')->textInput() ?>

    <?= $form->field($model, 'pooLegalDescTownship')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pooLegalDescPrincipalMeridian')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fireClassId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fireClass')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'fireIgnitionDateTime')->textInput() ?>

    <?= $form->field($model, 'fireCauseGeneral')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fireCauseSpecific')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fireCauseInvestigatedIndicator')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pooJurisdictionalUnitParentUnit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'initialResponseDateTime')->textInput() ?>

    <?= $form->field($model, 'initialFireStrategyMetIndicator')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'finalStrategyAttainedDateTime')->textInput() ?>

    <?= $form->field($model, 'fireGrowthCessationDateTime')->textInput() ?>

    <?= $form->field($model, 'predominantFuelModel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'finalFireReportApprovedByUnit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'finalFireReportApprovedBy')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'finalFireReportApprovedByTitle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'finalFireReportApprovedDate')->textInput() ?>

    <?= $form->field($model, 'finalFireReportNarrative')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'unifiedCommand')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wfdssDecisionStatus')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fireBehaviorGeneral')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'fireBehaviorGeneral1')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'fireBehaviorGeneral2')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'fireBehaviorGeneral3')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'fireBehaviorDescription')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'secondaryFuelModel')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'additionalFuelModel')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'summaryFuelModel')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'fireStrategyMonitorPercent')->textInput() ?>

    <?= $form->field($model, 'fireStrategyConfinePercent')->textInput() ?>

    <?= $form->field($model, 'fireStrategyPointZonePercent')->textInput() ?>

    <?= $form->field($model, 'fireStrategyFullSuppPercent')->textInput() ?>

    <?= $form->field($model, 'projectedIncidentActivity24')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'projectedIncidentActivity48')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'projectedIncidentActivity72')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'projectedIncidentActivity72Plus')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'fiscallyResponsibleUnit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mergeParentIrwinId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'criticalResourceNeeds')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fireDepartmentID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hasFatalities')->textInput() ?>

    <?= $form->field($model, 'hasInjuries')->textInput() ?>

    <?= $form->field($model, 'inFuelTreatment')->textInput() ?>

    <?= $form->field($model, 'inNFPORS')->textInput() ?>

    <?= $form->field($model, 'isFireCauseInvestigated')->textInput() ?>

    <?= $form->field($model, 'isFireCodeRequested')->textInput() ?>

    <?= $form->field($model, 'isInitialFireStrategyMet')->textInput() ?>

    <?= $form->field($model, 'isQuarantined')->textInput() ?>

    <?= $form->field($model, 'isUnifiedCommand')->textInput() ?>

    <?= $form->field($model, 'pooDispatchCenterID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pooJurisdictionalAgency')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pooPredictiveServiceAreaID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pooProtectingAgency')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'predominantFuelGroup')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
