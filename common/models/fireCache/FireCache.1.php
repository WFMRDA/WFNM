<?php

namespace common\models\fireCache;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "fireCache".
 *
 * @property string $irwinID
 * @property string $recordSource
 * @property string $createdBySystem
 * @property string $createdOnDateTime
 * @property string $modifiedBySystem
 * @property string $modifiedOnDateTime
 * @property int $inConflict
 * @property string $conflictParentIrwinId
 * @property string $uniqueFireIdentifier
 * @property string $fireDiscoveryDateTime
 * @property string $pooProtectingUnit
 * @property string $localIncidentIdentifier
 * @property string $dispatchCenterId
 * @property string $incidentName
 * @property string $fireCause
 * @property string $incidentTypeKind
 * @property string $incidentTypeCategory
 * @property string $initialLatitude
 * @property string $initialLongitude
 * @property string $discoveryAcres
 * @property string $pooLatitude
 * @property string $pooLongitude
 * @property string $pooJurisdictionalUnit
 * @property string $pooState
 * @property string $pooCounty
 * @property string $pooFips
 * @property string $pooLandownerKind
 * @property string $pooLandownerCategory
 * @property string $initialResponseAcres
 * @property string $initialFireStrategy
 * @property string $firecodeRequested
 * @property string $abcdMisc
 * @property string $fireCode
 * @property string $fsJobCode
 * @property string $fsOverrideCode
 * @property int $isComplex
 * @property string $complexParentIrwinId
 * @property int $isFSAssisted
 * @property int $isMultiJurisdictional
 * @property int $isTrespass
 * @property int $isReimbursable
 * @property string $dailyAcres
 * @property string $calculatedAcres
 * @property int $totalIncidentPersonnel
 * @property string $fireMgmtComplexity
 * @property string $incidentCommanderName
 * @property string $incidentManagementOrganization
 * @property int $fatalities
 * @property int $injuries
 * @property int $residencesDestroyed
 * @property int $residencesThreatened
 * @property int $otherStructuresDestroyed
 * @property int $otherStructuresThreatened
 * @property string $estimatedCostToDate
 * @property string $estimatedContainmentDate
 * @property int $percentContained
 * @property int $percentPerimeterToBeContained
 * @property string $ics209ReportDateTime
 * @property string $ics209ReportStatus
 * @property string $containmentDateTime
 * @property string $controlDateTime
 * @property string $fireOutDateTime
 * @property string $finalAcres
 * @property string $gacc
 * @property int $isValid
 * @property string $adsPermissionState
 * @property int $unitIDValidation
 * @property string $incidentShortDescription
 * @property string $significantEvents
 * @property string $primaryFuelModel
 * @property string $weatherConcerns
 * @property string $projectedIncidentActivity12
 * @property string $plannedActions
 * @property string $ics209Remarks
 * @property string $ics209ReportForTimePeriodFrom
 * @property string $ics209ReportForTimePeriodTo
 * @property string $pooCity
 * @property string $pooIncidentJurisdictionalAgency
 * @property string $pooLegalDescQtrQtr
 * @property string $pooLegalDescQtr
 * @property string $pooLegalDescRange
 * @property int $pooLegalDescSection
 * @property string $pooLegalDescTownship
 * @property string $pooLegalDescPrincipalMeridian
 * @property string $fireClassId
 * @property int $created_at
 * @property int $updated_at
 * @property string $fireIgnitionDateTime
 * @property string $fireCauseGeneral
 * @property string $fireCauseSpecific
 * @property string $fireCauseInvestigatedIndicator
 * @property string $pooJurisdictionalUnitParentUnit
 * @property string $initialResponseDateTime
 * @property string $initialFireStrategyMetIndicator
 * @property string $finalStrategyAttainedDateTime
 * @property string $fireGrowthCessationDateTime
 * @property string $predominantFuelModel
 * @property string $finalFireReportApprovedByUnit
 * @property string $finalFireReportApprovedBy
 * @property string $finalFireReportApprovedByTitle
 * @property string $finalFireReportApprovedDate
 * @property string $finalFireReportNarrative
 * @property string $unifiedCommand
 * @property string $wfdssDecisionStatus
 * @property string $fireBehaviorGeneral
 * @property string $fireBehaviorGeneral1
 * @property string $fireBehaviorGeneral2
 * @property string $fireBehaviorGeneral3
 * @property string $fireBehaviorDescription
 * @property string $secondaryFuelModel
 * @property string $additionalFuelModel
 * @property string $summaryFuelModel
 * @property int $fireStrategyMonitorPercent
 * @property int $fireStrategyConfinePercent
 * @property int $fireStrategyPointZonePercent
 * @property int $fireStrategyFullSuppPercent
 * @property string $projectedIncidentActivity24
 * @property string $projectedIncidentActivity48
 * @property string $projectedIncidentActivity72
 * @property string $projectedIncidentActivity72Plus
 * @property string $fiscallyResponsibleUnit
 * @property string $mergeParentIrwinId
 * @property string $criticalResourceNeeds
 * @property string $fireDepartmentID
 * @property int $hasFatalities
 * @property int $hasInjuries
 * @property int $inFuelTreatment
 * @property int $inNFPORS
 * @property int $isFireCauseInvestigated
 * @property int $isFireCodeRequested
 * @property int $isInitialFireStrategyMet
 * @property int $isQuarantined
 * @property int $isUnifiedCommand
 * @property string $pooDispatchCenterID
 * @property string $pooJurisdictionalAgency
 * @property string $pooPredictiveServiceAreaID
 * @property string $pooProtectingAgency
 * @property string $predominantFuelGroup
 */
class FireCache extends \yii\db\ActiveRecord
{
        /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fireCache';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['irwinID'], 'required'],
            [['createdOnDateTime', 'modifiedOnDateTime', 'fireDiscoveryDateTime', 'estimatedContainmentDate', 'ics209ReportDateTime', 'containmentDateTime', 'controlDateTime', 'fireOutDateTime', 'ics209ReportForTimePeriodFrom', 'ics209ReportForTimePeriodTo', 'fireIgnitionDateTime', 'initialResponseDateTime', 'finalStrategyAttainedDateTime', 'fireGrowthCessationDateTime', 'finalFireReportApprovedDate'], 'safe'],
            [['inConflict', 'isComplex', 'isFSAssisted', 'isMultiJurisdictional', 'isTrespass', 'isReimbursable', 'totalIncidentPersonnel', 'fatalities', 'injuries', 'residencesDestroyed', 'residencesThreatened', 'otherStructuresDestroyed', 'otherStructuresThreatened', 'percentContained', 'percentPerimeterToBeContained', 'isValid', 'unitIDValidation', 'pooLegalDescSection', 'created_at', 'updated_at', 'fireStrategyMonitorPercent', 'fireStrategyConfinePercent', 'fireStrategyPointZonePercent', 'fireStrategyFullSuppPercent', 'hasFatalities', 'hasInjuries', 'inFuelTreatment', 'inNFPORS', 'isFireCauseInvestigated', 'isFireCodeRequested', 'isInitialFireStrategyMet', 'isQuarantined', 'isUnifiedCommand'], 'integer'],
            [['initialLatitude', 'initialLongitude', 'discoveryAcres', 'pooLatitude', 'pooLongitude', 'initialResponseAcres', 'dailyAcres', 'calculatedAcres', 'estimatedCostToDate', 'finalAcres'], 'number'],
            [['significantEvents', 'weatherConcerns', 'projectedIncidentActivity12', 'plannedActions', 'ics209Remarks', 'finalFireReportNarrative', 'fireBehaviorGeneral', 'fireBehaviorGeneral1', 'fireBehaviorGeneral2', 'fireBehaviorGeneral3', 'fireBehaviorDescription', 'secondaryFuelModel', 'additionalFuelModel', 'summaryFuelModel', 'projectedIncidentActivity24', 'projectedIncidentActivity48', 'projectedIncidentActivity72', 'projectedIncidentActivity72Plus'], 'string'],
            [['irwinID', 'recordSource', 'createdBySystem', 'modifiedBySystem', 'conflictParentIrwinId', 'uniqueFireIdentifier', 'pooProtectingUnit', 'localIncidentIdentifier', 'dispatchCenterId', 'incidentName', 'fireCause', 'incidentTypeKind', 'incidentTypeCategory', 'pooJurisdictionalUnit', 'pooState', 'pooCounty', 'pooFips', 'pooLandownerKind', 'pooLandownerCategory', 'initialFireStrategy', 'firecodeRequested', 'abcdMisc', 'fireCode', 'fsJobCode', 'fsOverrideCode', 'complexParentIrwinId', 'fireMgmtComplexity', 'incidentCommanderName', 'incidentManagementOrganization', 'ics209ReportStatus', 'gacc', 'adsPermissionState', 'incidentShortDescription', 'primaryFuelModel', 'pooCity', 'pooIncidentJurisdictionalAgency', 'pooLegalDescQtrQtr', 'pooLegalDescQtr', 'pooLegalDescRange', 'pooLegalDescTownship', 'pooLegalDescPrincipalMeridian', 'fireClassId', 'fireCauseGeneral', 'fireCauseSpecific', 'fireCauseInvestigatedIndicator', 'pooJurisdictionalUnitParentUnit', 'initialFireStrategyMetIndicator', 'predominantFuelModel', 'finalFireReportApprovedByUnit', 'finalFireReportApprovedBy', 'finalFireReportApprovedByTitle', 'unifiedCommand', 'wfdssDecisionStatus', 'fiscallyResponsibleUnit', 'mergeParentIrwinId', 'criticalResourceNeeds', 'fireDepartmentID', 'pooDispatchCenterID', 'pooJurisdictionalAgency', 'pooPredictiveServiceAreaID', 'pooProtectingAgency', 'predominantFuelGroup'], 'string', 'max' => 255],
            [['irwinID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'irwinID' => 'Irwin ID',
            'recordSource' => 'Record Source',
            'createdBySystem' => 'Created By System',
            'createdOnDateTime' => 'Created On Date Time',
            'modifiedBySystem' => 'Modified By System',
            'modifiedOnDateTime' => 'Modified On Date Time',
            'inConflict' => 'In Conflict',
            'conflictParentIrwinId' => 'Conflict Parent Irwin ID',
            'uniqueFireIdentifier' => 'Unique Fire Identifier',
            'fireDiscoveryDateTime' => 'Fire Discovery Date Time',
            'pooProtectingUnit' => 'Poo Protecting Unit',
            'localIncidentIdentifier' => 'Local Incident Identifier',
            'dispatchCenterId' => 'Dispatch Center ID',
            'incidentName' => 'Incident Name',
            'fireCause' => 'Fire Cause',
            'incidentTypeKind' => 'Incident Type Kind',
            'incidentTypeCategory' => 'Incident Type Category',
            'initialLatitude' => 'Initial Latitude',
            'initialLongitude' => 'Initial Longitude',
            'discoveryAcres' => 'Discovery Acres',
            'pooLatitude' => 'Poo Latitude',
            'pooLongitude' => 'Poo Longitude',
            'pooJurisdictionalUnit' => 'Poo Jurisdictional Unit',
            'pooState' => 'Poo State',
            'pooCounty' => 'Poo County',
            'pooFips' => 'Poo Fips',
            'pooLandownerKind' => 'Poo Landowner Kind',
            'pooLandownerCategory' => 'Poo Landowner Category',
            'initialResponseAcres' => 'Initial Response Acres',
            'initialFireStrategy' => 'Initial Fire Strategy',
            'firecodeRequested' => 'Firecode Requested',
            'abcdMisc' => 'Abcd Misc',
            'fireCode' => 'Fire Code',
            'fsJobCode' => 'Fs Job Code',
            'fsOverrideCode' => 'Fs Override Code',
            'isComplex' => 'Is Complex',
            'complexParentIrwinId' => 'Complex Parent Irwin ID',
            'isFSAssisted' => 'Is Fsassisted',
            'isMultiJurisdictional' => 'Is Multi Jurisdictional',
            'isTrespass' => 'Is Trespass',
            'isReimbursable' => 'Is Reimbursable',
            'dailyAcres' => 'Daily Acres',
            'calculatedAcres' => 'Calculated Acres',
            'totalIncidentPersonnel' => 'Total Incident Personnel',
            'fireMgmtComplexity' => 'Fire Mgmt Complexity',
            'incidentCommanderName' => 'Incident Commander Name',
            'incidentManagementOrganization' => 'Incident Management Organization',
            'fatalities' => 'Fatalities',
            'injuries' => 'Injuries',
            'residencesDestroyed' => 'Residences Destroyed',
            'residencesThreatened' => 'Residences Threatened',
            'otherStructuresDestroyed' => 'Other Structures Destroyed',
            'otherStructuresThreatened' => 'Other Structures Threatened',
            'estimatedCostToDate' => 'Estimated Cost To Date',
            'estimatedContainmentDate' => 'Estimated Containment Date',
            'percentContained' => 'Percent Contained',
            'percentPerimeterToBeContained' => 'Percent Perimeter To Be Contained',
            'ics209ReportDateTime' => 'Ics209 Report Date Time',
            'ics209ReportStatus' => 'Ics209 Report Status',
            'containmentDateTime' => 'Containment Date Time',
            'controlDateTime' => 'Control Date Time',
            'fireOutDateTime' => 'Fire Out Date Time',
            'finalAcres' => 'Final Acres',
            'gacc' => 'Gacc',
            'isValid' => 'Is Valid',
            'adsPermissionState' => 'Ads Permission State',
            'unitIDValidation' => 'Unit Idvalidation',
            'incidentShortDescription' => 'Incident Short Description',
            'significantEvents' => 'Significant Events',
            'primaryFuelModel' => 'Primary Fuel Model',
            'weatherConcerns' => 'Weather Concerns',
            'projectedIncidentActivity12' => 'Projected Incident Activity12',
            'plannedActions' => 'Planned Actions',
            'ics209Remarks' => 'Ics209 Remarks',
            'ics209ReportForTimePeriodFrom' => 'Ics209 Report For Time Period From',
            'ics209ReportForTimePeriodTo' => 'Ics209 Report For Time Period To',
            'pooCity' => 'Poo City',
            'pooIncidentJurisdictionalAgency' => 'Poo Incident Jurisdictional Agency',
            'pooLegalDescQtrQtr' => 'Poo Legal Desc Qtr Qtr',
            'pooLegalDescQtr' => 'Poo Legal Desc Qtr',
            'pooLegalDescRange' => 'Poo Legal Desc Range',
            'pooLegalDescSection' => 'Poo Legal Desc Section',
            'pooLegalDescTownship' => 'Poo Legal Desc Township',
            'pooLegalDescPrincipalMeridian' => 'Poo Legal Desc Principal Meridian',
            'fireClassId' => 'Fire Class ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'fireIgnitionDateTime' => 'Fire Ignition Date Time',
            'fireCauseGeneral' => 'Fire Cause General',
            'fireCauseSpecific' => 'Fire Cause Specific',
            'fireCauseInvestigatedIndicator' => 'Fire Cause Investigated Indicator',
            'pooJurisdictionalUnitParentUnit' => 'Poo Jurisdictional Unit Parent Unit',
            'initialResponseDateTime' => 'Initial Response Date Time',
            'initialFireStrategyMetIndicator' => 'Initial Fire Strategy Met Indicator',
            'finalStrategyAttainedDateTime' => 'Final Strategy Attained Date Time',
            'fireGrowthCessationDateTime' => 'Fire Growth Cessation Date Time',
            'predominantFuelModel' => 'Predominant Fuel Model',
            'finalFireReportApprovedByUnit' => 'Final Fire Report Approved By Unit',
            'finalFireReportApprovedBy' => 'Final Fire Report Approved By',
            'finalFireReportApprovedByTitle' => 'Final Fire Report Approved By Title',
            'finalFireReportApprovedDate' => 'Final Fire Report Approved Date',
            'finalFireReportNarrative' => 'Final Fire Report Narrative',
            'unifiedCommand' => 'Unified Command',
            'wfdssDecisionStatus' => 'Wfdss Decision Status',
            'fireBehaviorGeneral' => 'Fire Behavior General',
            'fireBehaviorGeneral1' => 'Fire Behavior General1',
            'fireBehaviorGeneral2' => 'Fire Behavior General2',
            'fireBehaviorGeneral3' => 'Fire Behavior General3',
            'fireBehaviorDescription' => 'Fire Behavior Description',
            'secondaryFuelModel' => 'Secondary Fuel Model',
            'additionalFuelModel' => 'Additional Fuel Model',
            'summaryFuelModel' => 'Summary Fuel Model',
            'fireStrategyMonitorPercent' => 'Fire Strategy Monitor Percent',
            'fireStrategyConfinePercent' => 'Fire Strategy Confine Percent',
            'fireStrategyPointZonePercent' => 'Fire Strategy Point Zone Percent',
            'fireStrategyFullSuppPercent' => 'Fire Strategy Full Supp Percent',
            'projectedIncidentActivity24' => 'Projected Incident Activity24',
            'projectedIncidentActivity48' => 'Projected Incident Activity48',
            'projectedIncidentActivity72' => 'Projected Incident Activity72',
            'projectedIncidentActivity72Plus' => 'Projected Incident Activity72 Plus',
            'fiscallyResponsibleUnit' => 'Fiscally Responsible Unit',
            'mergeParentIrwinId' => 'Merge Parent Irwin ID',
            'criticalResourceNeeds' => 'Critical Resource Needs',
            'fireDepartmentID' => 'Fire Department ID',
            'hasFatalities' => 'Has Fatalities',
            'hasInjuries' => 'Has Injuries',
            'inFuelTreatment' => 'In Fuel Treatment',
            'inNFPORS' => 'In Nfpors',
            'isFireCauseInvestigated' => 'Is Fire Cause Investigated',
            'isFireCodeRequested' => 'Is Fire Code Requested',
            'isInitialFireStrategyMet' => 'Is Initial Fire Strategy Met',
            'isQuarantined' => 'Is Quarantined',
            'isUnifiedCommand' => 'Is Unified Command',
            'pooDispatchCenterID' => 'Poo Dispatch Center ID',
            'pooJurisdictionalAgency' => 'Poo Jurisdictional Agency',
            'pooPredictiveServiceAreaID' => 'Poo Predictive Service Area ID',
            'pooProtectingAgency' => 'Poo Protecting Agency',
            'predominantFuelGroup' => 'Predominant Fuel Group',
        ];
    }
}
