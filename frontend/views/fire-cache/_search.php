<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\fireCache\FireCacheSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fire-cache-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'irwinID') ?>

    <?= $form->field($model, 'recordSource') ?>

    <?= $form->field($model, 'createdBySystem') ?>

    <?= $form->field($model, 'createdOnDateTime') ?>

    <?= $form->field($model, 'modifiedBySystem') ?>

    <?php // echo $form->field($model, 'modifiedOnDateTime') ?>

    <?php // echo $form->field($model, 'inConflict') ?>

    <?php // echo $form->field($model, 'conflictParentIrwinId') ?>

    <?php // echo $form->field($model, 'uniqueFireIdentifier') ?>

    <?php // echo $form->field($model, 'fireDiscoveryDateTime') ?>

    <?php // echo $form->field($model, 'pooProtectingUnit') ?>

    <?php // echo $form->field($model, 'localIncidentIdentifier') ?>

    <?php // echo $form->field($model, 'dispatchCenterId') ?>

    <?php // echo $form->field($model, 'incidentName') ?>

    <?php // echo $form->field($model, 'fireCause') ?>

    <?php // echo $form->field($model, 'incidentTypeKind') ?>

    <?php // echo $form->field($model, 'incidentTypeCategory') ?>

    <?php // echo $form->field($model, 'initialLatitude') ?>

    <?php // echo $form->field($model, 'initialLongitude') ?>

    <?php // echo $form->field($model, 'discoveryAcres') ?>

    <?php // echo $form->field($model, 'pooLatitude') ?>

    <?php // echo $form->field($model, 'pooLongitude') ?>

    <?php // echo $form->field($model, 'pooJurisdictionalUnit') ?>

    <?php // echo $form->field($model, 'pooState') ?>

    <?php // echo $form->field($model, 'pooCounty') ?>

    <?php // echo $form->field($model, 'pooFips') ?>

    <?php // echo $form->field($model, 'pooLandownerKind') ?>

    <?php // echo $form->field($model, 'pooLandownerCategory') ?>

    <?php // echo $form->field($model, 'initialResponseAcres') ?>

    <?php // echo $form->field($model, 'initialFireStrategy') ?>

    <?php // echo $form->field($model, 'firecodeRequested') ?>

    <?php // echo $form->field($model, 'abcdMisc') ?>

    <?php // echo $form->field($model, 'fireCode') ?>

    <?php // echo $form->field($model, 'fsJobCode') ?>

    <?php // echo $form->field($model, 'fsOverrideCode') ?>

    <?php // echo $form->field($model, 'isComplex') ?>

    <?php // echo $form->field($model, 'complexParentIrwinId') ?>

    <?php // echo $form->field($model, 'isFSAssisted') ?>

    <?php // echo $form->field($model, 'isMultiJurisdictional') ?>

    <?php // echo $form->field($model, 'isTrespass') ?>

    <?php // echo $form->field($model, 'isReimbursable') ?>

    <?php // echo $form->field($model, 'dailyAcres') ?>

    <?php // echo $form->field($model, 'calculatedAcres') ?>

    <?php // echo $form->field($model, 'totalIncidentPersonnel') ?>

    <?php // echo $form->field($model, 'fireMgmtComplexity') ?>

    <?php // echo $form->field($model, 'incidentCommanderName') ?>

    <?php // echo $form->field($model, 'incidentManagementOrganization') ?>

    <?php // echo $form->field($model, 'fatalities') ?>

    <?php // echo $form->field($model, 'injuries') ?>

    <?php // echo $form->field($model, 'residencesDestroyed') ?>

    <?php // echo $form->field($model, 'residencesThreatened') ?>

    <?php // echo $form->field($model, 'otherStructuresDestroyed') ?>

    <?php // echo $form->field($model, 'otherStructuresThreatened') ?>

    <?php // echo $form->field($model, 'estimatedCostToDate') ?>

    <?php // echo $form->field($model, 'estimatedContainmentDate') ?>

    <?php // echo $form->field($model, 'percentContained') ?>

    <?php // echo $form->field($model, 'percentPerimeterToBeContained') ?>

    <?php // echo $form->field($model, 'ics209ReportDateTime') ?>

    <?php // echo $form->field($model, 'ics209ReportStatus') ?>

    <?php // echo $form->field($model, 'containmentDateTime') ?>

    <?php // echo $form->field($model, 'controlDateTime') ?>

    <?php // echo $form->field($model, 'fireOutDateTime') ?>

    <?php // echo $form->field($model, 'finalAcres') ?>

    <?php // echo $form->field($model, 'gacc') ?>

    <?php // echo $form->field($model, 'isValid') ?>

    <?php // echo $form->field($model, 'adsPermissionState') ?>

    <?php // echo $form->field($model, 'unitIDValidation') ?>

    <?php // echo $form->field($model, 'incidentShortDescription') ?>

    <?php // echo $form->field($model, 'significantEvents') ?>

    <?php // echo $form->field($model, 'primaryFuelModel') ?>

    <?php // echo $form->field($model, 'weatherConcerns') ?>

    <?php // echo $form->field($model, 'projectedIncidentActivity12') ?>

    <?php // echo $form->field($model, 'plannedActions') ?>

    <?php // echo $form->field($model, 'ics209Remarks') ?>

    <?php // echo $form->field($model, 'ics209ReportForTimePeriodFrom') ?>

    <?php // echo $form->field($model, 'ics209ReportForTimePeriodTo') ?>

    <?php // echo $form->field($model, 'pooCity') ?>

    <?php // echo $form->field($model, 'pooIncidentJurisdictionalAgency') ?>

    <?php // echo $form->field($model, 'pooLegalDescQtrQtr') ?>

    <?php // echo $form->field($model, 'pooLegalDescQtr') ?>

    <?php // echo $form->field($model, 'pooLegalDescRange') ?>

    <?php // echo $form->field($model, 'pooLegalDescSection') ?>

    <?php // echo $form->field($model, 'pooLegalDescTownship') ?>

    <?php // echo $form->field($model, 'pooLegalDescPrincipalMeridian') ?>

    <?php // echo $form->field($model, 'fireClassId') ?>

    <?php // echo $form->field($model, 'fireClass') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'fireIgnitionDateTime') ?>

    <?php // echo $form->field($model, 'fireCauseGeneral') ?>

    <?php // echo $form->field($model, 'fireCauseSpecific') ?>

    <?php // echo $form->field($model, 'fireCauseInvestigatedIndicator') ?>

    <?php // echo $form->field($model, 'pooJurisdictionalUnitParentUnit') ?>

    <?php // echo $form->field($model, 'initialResponseDateTime') ?>

    <?php // echo $form->field($model, 'initialFireStrategyMetIndicator') ?>

    <?php // echo $form->field($model, 'finalStrategyAttainedDateTime') ?>

    <?php // echo $form->field($model, 'fireGrowthCessationDateTime') ?>

    <?php // echo $form->field($model, 'predominantFuelModel') ?>

    <?php // echo $form->field($model, 'finalFireReportApprovedByUnit') ?>

    <?php // echo $form->field($model, 'finalFireReportApprovedBy') ?>

    <?php // echo $form->field($model, 'finalFireReportApprovedByTitle') ?>

    <?php // echo $form->field($model, 'finalFireReportApprovedDate') ?>

    <?php // echo $form->field($model, 'finalFireReportNarrative') ?>

    <?php // echo $form->field($model, 'unifiedCommand') ?>

    <?php // echo $form->field($model, 'wfdssDecisionStatus') ?>

    <?php // echo $form->field($model, 'fireBehaviorGeneral') ?>

    <?php // echo $form->field($model, 'fireBehaviorGeneral1') ?>

    <?php // echo $form->field($model, 'fireBehaviorGeneral2') ?>

    <?php // echo $form->field($model, 'fireBehaviorGeneral3') ?>

    <?php // echo $form->field($model, 'fireBehaviorDescription') ?>

    <?php // echo $form->field($model, 'secondaryFuelModel') ?>

    <?php // echo $form->field($model, 'additionalFuelModel') ?>

    <?php // echo $form->field($model, 'summaryFuelModel') ?>

    <?php // echo $form->field($model, 'fireStrategyMonitorPercent') ?>

    <?php // echo $form->field($model, 'fireStrategyConfinePercent') ?>

    <?php // echo $form->field($model, 'fireStrategyPointZonePercent') ?>

    <?php // echo $form->field($model, 'fireStrategyFullSuppPercent') ?>

    <?php // echo $form->field($model, 'projectedIncidentActivity24') ?>

    <?php // echo $form->field($model, 'projectedIncidentActivity48') ?>

    <?php // echo $form->field($model, 'projectedIncidentActivity72') ?>

    <?php // echo $form->field($model, 'projectedIncidentActivity72Plus') ?>

    <?php // echo $form->field($model, 'fiscallyResponsibleUnit') ?>

    <?php // echo $form->field($model, 'mergeParentIrwinId') ?>

    <?php // echo $form->field($model, 'criticalResourceNeeds') ?>

    <?php // echo $form->field($model, 'fireDepartmentID') ?>

    <?php // echo $form->field($model, 'hasFatalities') ?>

    <?php // echo $form->field($model, 'hasInjuries') ?>

    <?php // echo $form->field($model, 'inFuelTreatment') ?>

    <?php // echo $form->field($model, 'inNFPORS') ?>

    <?php // echo $form->field($model, 'isFireCauseInvestigated') ?>

    <?php // echo $form->field($model, 'isFireCodeRequested') ?>

    <?php // echo $form->field($model, 'isInitialFireStrategyMet') ?>

    <?php // echo $form->field($model, 'isQuarantined') ?>

    <?php // echo $form->field($model, 'isUnifiedCommand') ?>

    <?php // echo $form->field($model, 'pooDispatchCenterID') ?>

    <?php // echo $form->field($model, 'pooJurisdictionalAgency') ?>

    <?php // echo $form->field($model, 'pooPredictiveServiceAreaID') ?>

    <?php // echo $form->field($model, 'pooProtectingAgency') ?>

    <?php // echo $form->field($model, 'predominantFuelGroup') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
