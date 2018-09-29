<?php

namespace common\models\fireCache;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\fireCache\FireCache;

/**
 * FireCacheSearch represents the model behind the search form of `common\models\fireCache\FireCache`.
 */
class FireCacheSearch extends FireCache
{
    public $q;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['irwinID', 'recordSource', 'createdBySystem', 'createdOnDateTime', 'modifiedBySystem', 'modifiedOnDateTime', 'conflictParentIrwinId', 'uniqueFireIdentifier', 'fireDiscoveryDateTime', 'pooProtectingUnit', 'localIncidentIdentifier', 'dispatchCenterId', 'incidentName', 'fireCause', 'incidentTypeKind', 'incidentTypeCategory', 'pooJurisdictionalUnit', 'pooState', 'pooCounty', 'pooFips', 'pooLandownerKind', 'pooLandownerCategory', 'initialFireStrategy', 'firecodeRequested', 'abcdMisc', 'fireCode', 'fsJobCode', 'fsOverrideCode', 'complexParentIrwinId', 'fireMgmtComplexity', 'incidentCommanderName', 'incidentManagementOrganization', 'estimatedContainmentDate', 'ics209ReportDateTime', 'ics209ReportStatus', 'containmentDateTime', 'controlDateTime', 'fireOutDateTime', 'gacc', 'adsPermissionState', 'incidentShortDescription', 'significantEvents', 'primaryFuelModel', 'weatherConcerns', 'projectedIncidentActivity12', 'plannedActions', 'ics209Remarks', 'ics209ReportForTimePeriodFrom', 'ics209ReportForTimePeriodTo', 'pooCity', 'pooIncidentJurisdictionalAgency', 'pooLegalDescQtrQtr', 'pooLegalDescQtr', 'pooLegalDescRange', 'pooLegalDescTownship', 'pooLegalDescPrincipalMeridian', 'fireIgnitionDateTime', 'fireCauseGeneral', 'fireCauseSpecific', 'fireCauseInvestigatedIndicator', 'pooJurisdictionalUnitParentUnit', 'initialResponseDateTime', 'initialFireStrategyMetIndicator', 'finalStrategyAttainedDateTime', 'fireGrowthCessationDateTime', 'predominantFuelModel', 'finalFireReportApprovedByUnit', 'finalFireReportApprovedBy', 'finalFireReportApprovedByTitle', 'finalFireReportApprovedDate', 'finalFireReportNarrative', 'unifiedCommand', 'wfdssDecisionStatus', 'fireBehaviorGeneral', 'fireBehaviorGeneral1', 'fireBehaviorGeneral2', 'fireBehaviorGeneral3', 'fireBehaviorDescription', 'secondaryFuelModel', 'additionalFuelModel', 'summaryFuelModel', 'projectedIncidentActivity24', 'projectedIncidentActivity48', 'projectedIncidentActivity72', 'projectedIncidentActivity72Plus', 'fiscallyResponsibleUnit', 'mergeParentIrwinId', 'criticalResourceNeeds', 'fireDepartmentID', 'pooDispatchCenterID', 'pooJurisdictionalAgency', 'pooPredictiveServiceAreaID', 'pooProtectingAgency', 'predominantFuelGroup','q'], 'safe'],
            [['inConflict', 'isComplex', 'isFSAssisted', 'isMultiJurisdictional', 'isTrespass', 'isReimbursable', 'totalIncidentPersonnel', 'fatalities', 'injuries', 'residencesDestroyed', 'residencesThreatened', 'otherStructuresDestroyed', 'otherStructuresThreatened', 'percentContained', 'percentPerimeterToBeContained', 'isValid', 'unitIDValidation', 'pooLegalDescSection', 'created_at', 'updated_at', 'fireStrategyMonitorPercent', 'fireStrategyConfinePercent', 'fireStrategyPointZonePercent', 'fireStrategyFullSuppPercent', 'hasFatalities', 'hasInjuries', 'inFuelTreatment', 'inNFPORS', 'isFireCauseInvestigated', 'isFireCodeRequested', 'isInitialFireStrategyMet', 'isQuarantined', 'isUnifiedCommand'], 'integer'],
            [['initialLatitude', 'initialLongitude', 'discoveryAcres', 'pooLatitude', 'pooLongitude', 'initialResponseAcres', 'dailyAcres', 'calculatedAcres', 'estimatedCostToDate', 'finalAcres'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = FireCache::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'createdOnDateTime' => $this->createdOnDateTime,
            'modifiedOnDateTime' => $this->modifiedOnDateTime,
            'inConflict' => $this->inConflict,
            'fireDiscoveryDateTime' => $this->fireDiscoveryDateTime,
            'initialLatitude' => $this->initialLatitude,
            'initialLongitude' => $this->initialLongitude,
            'discoveryAcres' => $this->discoveryAcres,
            'pooLatitude' => $this->pooLatitude,
            'pooLongitude' => $this->pooLongitude,
            'initialResponseAcres' => $this->initialResponseAcres,
            'isComplex' => $this->isComplex,
            'isFSAssisted' => $this->isFSAssisted,
            'isMultiJurisdictional' => $this->isMultiJurisdictional,
            'isTrespass' => $this->isTrespass,
            'isReimbursable' => $this->isReimbursable,
            'dailyAcres' => $this->dailyAcres,
            'calculatedAcres' => $this->calculatedAcres,
            'totalIncidentPersonnel' => $this->totalIncidentPersonnel,
            'fatalities' => $this->fatalities,
            'injuries' => $this->injuries,
            'residencesDestroyed' => $this->residencesDestroyed,
            'residencesThreatened' => $this->residencesThreatened,
            'otherStructuresDestroyed' => $this->otherStructuresDestroyed,
            'otherStructuresThreatened' => $this->otherStructuresThreatened,
            'estimatedCostToDate' => $this->estimatedCostToDate,
            'estimatedContainmentDate' => $this->estimatedContainmentDate,
            'percentContained' => $this->percentContained,
            'percentPerimeterToBeContained' => $this->percentPerimeterToBeContained,
            'ics209ReportDateTime' => $this->ics209ReportDateTime,
            'containmentDateTime' => $this->containmentDateTime,
            'controlDateTime' => $this->controlDateTime,
            'fireOutDateTime' => $this->fireOutDateTime,
            'finalAcres' => $this->finalAcres,
            'isValid' => $this->isValid,
            'unitIDValidation' => $this->unitIDValidation,
            'ics209ReportForTimePeriodFrom' => $this->ics209ReportForTimePeriodFrom,
            'ics209ReportForTimePeriodTo' => $this->ics209ReportForTimePeriodTo,
            'pooLegalDescSection' => $this->pooLegalDescSection,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'fireIgnitionDateTime' => $this->fireIgnitionDateTime,
            'initialResponseDateTime' => $this->initialResponseDateTime,
            'finalStrategyAttainedDateTime' => $this->finalStrategyAttainedDateTime,
            'fireGrowthCessationDateTime' => $this->fireGrowthCessationDateTime,
            'finalFireReportApprovedDate' => $this->finalFireReportApprovedDate,
            'fireStrategyMonitorPercent' => $this->fireStrategyMonitorPercent,
            'fireStrategyConfinePercent' => $this->fireStrategyConfinePercent,
            'fireStrategyPointZonePercent' => $this->fireStrategyPointZonePercent,
            'fireStrategyFullSuppPercent' => $this->fireStrategyFullSuppPercent,
            'hasFatalities' => $this->hasFatalities,
            'hasInjuries' => $this->hasInjuries,
            'inFuelTreatment' => $this->inFuelTreatment,
            'inNFPORS' => $this->inNFPORS,
            'isFireCauseInvestigated' => $this->isFireCauseInvestigated,
            'isFireCodeRequested' => $this->isFireCodeRequested,
            'isInitialFireStrategyMet' => $this->isInitialFireStrategyMet,
            'isQuarantined' => $this->isQuarantined,
            'isUnifiedCommand' => $this->isUnifiedCommand,
        ]);

        $query->andFilterWhere(['like', 'irwinID', $this->irwinID])
            ->andFilterWhere(['like', 'recordSource', $this->recordSource])
            ->andFilterWhere(['like', 'createdBySystem', $this->createdBySystem])
            ->andFilterWhere(['like', 'modifiedBySystem', $this->modifiedBySystem])
            ->andFilterWhere(['like', 'conflictParentIrwinId', $this->conflictParentIrwinId])
            ->andFilterWhere(['like', 'uniqueFireIdentifier', $this->uniqueFireIdentifier])
            ->andFilterWhere(['like', 'pooProtectingUnit', $this->pooProtectingUnit])
            ->andFilterWhere(['like', 'localIncidentIdentifier', $this->localIncidentIdentifier])
            ->andFilterWhere(['like', 'dispatchCenterId', $this->dispatchCenterId])
            ->andFilterWhere(['like', 'incidentName', $this->incidentName])
            ->andFilterWhere(['like', 'fireCause', $this->fireCause])
            ->andFilterWhere(['like', 'incidentTypeKind', $this->incidentTypeKind])
            ->andFilterWhere(['like', 'incidentTypeCategory', $this->incidentTypeCategory])
            ->andFilterWhere(['like', 'pooJurisdictionalUnit', $this->pooJurisdictionalUnit])
            ->andFilterWhere(['like', 'pooState', $this->pooState])
            ->andFilterWhere(['like', 'pooCounty', $this->pooCounty])
            ->andFilterWhere(['like', 'pooFips', $this->pooFips])
            ->andFilterWhere(['like', 'pooLandownerKind', $this->pooLandownerKind])
            ->andFilterWhere(['like', 'pooLandownerCategory', $this->pooLandownerCategory])
            ->andFilterWhere(['like', 'initialFireStrategy', $this->initialFireStrategy])
            ->andFilterWhere(['like', 'firecodeRequested', $this->firecodeRequested])
            ->andFilterWhere(['like', 'abcdMisc', $this->abcdMisc])
            ->andFilterWhere(['like', 'fireCode', $this->fireCode])
            ->andFilterWhere(['like', 'fsJobCode', $this->fsJobCode])
            ->andFilterWhere(['like', 'fsOverrideCode', $this->fsOverrideCode])
            ->andFilterWhere(['like', 'complexParentIrwinId', $this->complexParentIrwinId])
            ->andFilterWhere(['like', 'fireMgmtComplexity', $this->fireMgmtComplexity])
            ->andFilterWhere(['like', 'incidentCommanderName', $this->incidentCommanderName])
            ->andFilterWhere(['like', 'incidentManagementOrganization', $this->incidentManagementOrganization])
            ->andFilterWhere(['like', 'ics209ReportStatus', $this->ics209ReportStatus])
            ->andFilterWhere(['like', 'gacc', $this->gacc])
            ->andFilterWhere(['like', 'adsPermissionState', $this->adsPermissionState])
            ->andFilterWhere(['like', 'incidentShortDescription', $this->incidentShortDescription])
            ->andFilterWhere(['like', 'significantEvents', $this->significantEvents])
            ->andFilterWhere(['like', 'primaryFuelModel', $this->primaryFuelModel])
            ->andFilterWhere(['like', 'weatherConcerns', $this->weatherConcerns])
            ->andFilterWhere(['like', 'projectedIncidentActivity12', $this->projectedIncidentActivity12])
            ->andFilterWhere(['like', 'plannedActions', $this->plannedActions])
            ->andFilterWhere(['like', 'ics209Remarks', $this->ics209Remarks])
            ->andFilterWhere(['like', 'pooCity', $this->pooCity])
            ->andFilterWhere(['like', 'pooIncidentJurisdictionalAgency', $this->pooIncidentJurisdictionalAgency])
            ->andFilterWhere(['like', 'pooLegalDescQtrQtr', $this->pooLegalDescQtrQtr])
            ->andFilterWhere(['like', 'pooLegalDescQtr', $this->pooLegalDescQtr])
            ->andFilterWhere(['like', 'pooLegalDescRange', $this->pooLegalDescRange])
            ->andFilterWhere(['like', 'pooLegalDescTownship', $this->pooLegalDescTownship])
            ->andFilterWhere(['like', 'pooLegalDescPrincipalMeridian', $this->pooLegalDescPrincipalMeridian])
            ->andFilterWhere(['like', 'fireCauseGeneral', $this->fireCauseGeneral])
            ->andFilterWhere(['like', 'fireCauseSpecific', $this->fireCauseSpecific])
            ->andFilterWhere(['like', 'fireCauseInvestigatedIndicator', $this->fireCauseInvestigatedIndicator])
            ->andFilterWhere(['like', 'pooJurisdictionalUnitParentUnit', $this->pooJurisdictionalUnitParentUnit])
            ->andFilterWhere(['like', 'initialFireStrategyMetIndicator', $this->initialFireStrategyMetIndicator])
            ->andFilterWhere(['like', 'predominantFuelModel', $this->predominantFuelModel])
            ->andFilterWhere(['like', 'finalFireReportApprovedByUnit', $this->finalFireReportApprovedByUnit])
            ->andFilterWhere(['like', 'finalFireReportApprovedBy', $this->finalFireReportApprovedBy])
            ->andFilterWhere(['like', 'finalFireReportApprovedByTitle', $this->finalFireReportApprovedByTitle])
            ->andFilterWhere(['like', 'finalFireReportNarrative', $this->finalFireReportNarrative])
            ->andFilterWhere(['like', 'unifiedCommand', $this->unifiedCommand])
            ->andFilterWhere(['like', 'wfdssDecisionStatus', $this->wfdssDecisionStatus])
            ->andFilterWhere(['like', 'fireBehaviorGeneral', $this->fireBehaviorGeneral])
            ->andFilterWhere(['like', 'fireBehaviorGeneral1', $this->fireBehaviorGeneral1])
            ->andFilterWhere(['like', 'fireBehaviorGeneral2', $this->fireBehaviorGeneral2])
            ->andFilterWhere(['like', 'fireBehaviorGeneral3', $this->fireBehaviorGeneral3])
            ->andFilterWhere(['like', 'fireBehaviorDescription', $this->fireBehaviorDescription])
            ->andFilterWhere(['like', 'secondaryFuelModel', $this->secondaryFuelModel])
            ->andFilterWhere(['like', 'additionalFuelModel', $this->additionalFuelModel])
            ->andFilterWhere(['like', 'summaryFuelModel', $this->summaryFuelModel])
            ->andFilterWhere(['like', 'projectedIncidentActivity24', $this->projectedIncidentActivity24])
            ->andFilterWhere(['like', 'projectedIncidentActivity48', $this->projectedIncidentActivity48])
            ->andFilterWhere(['like', 'projectedIncidentActivity72', $this->projectedIncidentActivity72])
            ->andFilterWhere(['like', 'projectedIncidentActivity72Plus', $this->projectedIncidentActivity72Plus])
            ->andFilterWhere(['like', 'fiscallyResponsibleUnit', $this->fiscallyResponsibleUnit])
            ->andFilterWhere(['like', 'mergeParentIrwinId', $this->mergeParentIrwinId])
            ->andFilterWhere(['like', 'criticalResourceNeeds', $this->criticalResourceNeeds])
            ->andFilterWhere(['like', 'fireDepartmentID', $this->fireDepartmentID])
            ->andFilterWhere(['like', 'pooDispatchCenterID', $this->pooDispatchCenterID])
            ->andFilterWhere(['like', 'pooJurisdictionalAgency', $this->pooJurisdictionalAgency])
            ->andFilterWhere(['like', 'pooPredictiveServiceAreaID', $this->pooPredictiveServiceAreaID])
            ->andFilterWhere(['like', 'pooProtectingAgency', $this->pooProtectingAgency])
            ->andFilterWhere(['like', 'predominantFuelGroup', $this->predominantFuelGroup]);

        return $dataProvider;
    }


    public function searchRest($params){
        $query = FireCache::getDb()->cache(function ($db) {
            return FireCache::find();
        });
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params,'');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'incidentName', $this->q]);
        return $dataProvider;
    }
}
