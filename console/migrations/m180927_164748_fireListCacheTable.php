<?php

use console\migrations\Migration;

/**
 * Class m180927_164748_fireListCacheTable
 */
class m180927_164748_fireListCacheTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%fireCache}}', [
            'irwinID'               => $this->string() . ' PRIMARY KEY',
            'recordSource' => $this->string(),
            'createdBySystem' => $this->string(),
            'createdOnDateTime' =>  $this->datetime(),
            'modifiedBySystem' => $this->string(),
            'modifiedOnDateTime' =>  $this->datetime(),
            'inConflict' => $this->string(),
            'conflictParentIrwinId' => $this->string(),
            'uniqueFireIdentifier' => $this->string(),
            'fireDiscoveryDateTime' => $this->datetime(),
            'pooProtectingUnit' => $this->string(),
            'localIncidentIdentifier' => $this->string(),
            'dispatchCenterId' => $this->string(),
            'incidentName' => $this->string(),
            'fireCause' => $this->string(),
            'incidentTypeKind' => $this->string(),
            'incidentTypeCategory' => $this->string(),
            'initialLatitude' => $this->decimal(9,6),
            'initialLongitude' => $this->decimal(9,6),
            'discoveryAcres' => $this->decimal(9,1),
            'pooLatitude' =>  $this->decimal(9,6),
            'pooLongitude' => $this->decimal(9,6),
            'pooJurisdictionalUnit' => $this->string(),
            'pooState' => $this->string(),
            'pooCounty' => $this->string(),
            'pooFips' => $this->string(),
            'pooLandownerKind' => $this->string(),
            'pooLandownerCategory' => $this->string(),
            'initialResponseAcres' => $this->decimal(9,1),
            'initialFireStrategy' => $this->string(),
            'firecodeRequested' => $this->string(),
            'abcdMisc' => $this->string(),
            'fireCode' => $this->string(),
            'fsJobCode' => $this->string(),
            'fsOverrideCode' => $this->string(),
            'isComplex' => $this->string(),
            'complexParentIrwinId' => $this->string(),
            'isFSAssisted' => $this->string(),
            'isMultiJurisdictional' => $this->string(),
            'isTrespass' => $this->string(),
            'isReimbursable' => $this->string(),
            'dailyAcres' => $this->decimal(9,1),
            'calculatedAcres' => $this->decimal(9,1),
            'totalIncidentPersonnel' => $this->integer(),
            'fireMgmtComplexity' => $this->string(),
            'incidentCommanderName' => $this->string(),
            'incidentManagementOrganization' => $this->string(),
            'fatalities' => $this->integer(),
            'injuries' => $this->integer(),
            'residencesDestroyed' => $this->integer(),
            'residencesThreatened' => $this->integer(),
            'otherStructuresDestroyed' => $this->integer(),
            'otherStructuresThreatened' => $this->integer(),
            'estimatedCostToDate' => $this->money(15,2),
            'estimatedContainmentDate' => $this->date(),
            'percentContained' => $this->integer(),
            'percentPerimeterToBeContained' => $this->integer(),
            'ics209ReportDateTime' => $this->datetime(),
            'ics209ReportStatus' => $this->string(),
            'containmentDateTime' => $this->datetime(),
            'controlDateTime' => $this->datetime(),
            'fireOutDateTime' => $this->datetime(),
            'finalAcres' => $this->decimal(9,1),
            'gacc' => $this->string(),
            'isActive' => $this->string(),
            'adsPermissionState' => $this->string(),
            'unitIDValidation' => $this->integer(),
            'incidentShortDescription' => $this->string(),
            'significantEvents' => $this->text(),
            'pooPrimaryFuelModel' => $this->string(),
            'weatherConcerns' => $this->text(),
            'projectedIncidentActivity' => $this->text(),
            'plannedActions' => $this->text(),
            'ics209Remarks' => $this->text(),
            'ics209ReportForTimePeriodFrom' => $this->datetime(),
            'ics209ReportForTimePeriodTo' => $this->datetime(),
            'pooCity' => $this->string(),
            'pooIncidentJurisdictionalAgency' => $this->string(),
            'pooLegalDescQtrQtr' => $this->string(),
            'pooLegalDescQtr' => $this->string(),
            'pooLegalDescRange' => $this->string(),
            'pooLegalDescSection' => $this->integer(),
            'pooLegalDescTownship' => $this->string(),
            'pooLegalDescPrincipalMeridian' => $this->string(),
            'fireClassId'           => $this->string(),
            'fireClass'           => $this->string(),
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer()->notNull(),
        ], $this->tableOptions);


        $this->renameColumn ( '{{%fireCache}}', 'isActive', 'isValid' );
        $this->renameColumn ( '{{%fireCache}}', 'pooPrimaryFuelModel', 'primaryFuelModel' );
        $this->renameColumn ( '{{%fireCache}}', 'projectedIncidentActivity', 'projectedIncidentActivity12' );

        $this->addColumn ('{{%fireCache}}', 'FireIgnitionDateTime', $this->datetime() );
        $this->addColumn ('{{%fireCache}}', 'FireCauseGeneral', $this->string() );
        $this->addColumn ('{{%fireCache}}', 'FireCauseSpecific', $this->string() );
        $this->addColumn ('{{%fireCache}}', 'FireCauseInvestigatedIndicator', $this->string() );
        $this->addColumn ('{{%fireCache}}', 'POOJurisdictionalUnitParentUnit', $this->string() );
        $this->addColumn ('{{%fireCache}}', 'InitialResponseDateTime', $this->datetime() );
        $this->addColumn ('{{%fireCache}}', 'InitialFireStrategyMetIndicator', $this->string() );
        $this->addColumn ('{{%fireCache}}', 'FinalStrategyAttainedDateTime', $this->datetime() );
        $this->addColumn ('{{%fireCache}}', 'FireGrowthCessationDateTime', $this->datetime() );
        $this->addColumn ('{{%fireCache}}', 'PredominantFuelModel', $this->string() );
        $this->addColumn ('{{%fireCache}}', 'FinalFireReportApprovedByUnit', $this->string() );
        $this->addColumn ('{{%fireCache}}', 'FinalFireReportApprovedBy', $this->string() );
        $this->addColumn ('{{%fireCache}}', 'FinalFireReportApprovedByTitle', $this->string() );
        $this->addColumn ('{{%fireCache}}', 'FinalFireReportApprovedDate', $this->datetime() );
        $this->addColumn ('{{%fireCache}}', 'FinalFireReportNarrative', $this->text() );
        $this->addColumn ('{{%fireCache}}', 'UnifiedCommand', $this->string() );
        $this->addColumn ('{{%fireCache}}', 'WFDSSDecisionStatus', $this->string() );
        $this->addColumn ('{{%fireCache}}', 'FireBehaviorGeneral', $this->string() );
        $this->addColumn ('{{%fireCache}}', 'FireBehaviorGeneral1', $this->string() );
        $this->addColumn ('{{%fireCache}}', 'FireBehaviorGeneral2', $this->string() );
        $this->addColumn ('{{%fireCache}}', 'FireBehaviorGeneral3', $this->string() );
        $this->addColumn ('{{%fireCache}}', 'FireBehaviorDescription', $this->text() );
        $this->addColumn ('{{%fireCache}}', 'SecondaryFuelModel', $this->string() );
        $this->addColumn ('{{%fireCache}}', 'AdditionalFuelModel', $this->string() );
        $this->addColumn ('{{%fireCache}}', 'SummaryFuelModel', $this->string() );
        $this->addColumn ('{{%fireCache}}', 'FireStrategyMonitorPercent', $this->integer() );
        $this->addColumn ('{{%fireCache}}', 'FireStrategyConfinePercent', $this->integer() );
        $this->addColumn ('{{%fireCache}}', 'FireStrategyPointZonePercent', $this->integer() );
        $this->addColumn ('{{%fireCache}}', 'FireStrategyFullSuppPercent', $this->integer() );
        $this->addColumn ('{{%fireCache}}', 'ProjectedIncidentActivity24', $this->text() );
        $this->addColumn ('{{%fireCache}}', 'ProjectedIncidentActivity48', $this->text() );
        $this->addColumn ('{{%fireCache}}', 'ProjectedIncidentActivity72', $this->text() );
        $this->addColumn ('{{%fireCache}}', 'ProjectedIncidentActivity72Plus', $this->text() );
        $this->addColumn ('{{%fireCache}}', 'FiscallyResponsibleUnit', $this->string() );

        $this->renameColumn ('{{%fireCache}}', 'FireIgnitionDateTime', 'fireIgnitionDateTime');
        $this->renameColumn ('{{%fireCache}}', 'FireCauseGeneral', 'fireCauseGeneral');
        $this->renameColumn ('{{%fireCache}}', 'FireCauseSpecific', 'fireCauseSpecific' );
        $this->renameColumn ('{{%fireCache}}', 'FireCauseInvestigatedIndicator', 'fireCauseInvestigatedIndicator' );
        $this->renameColumn ('{{%fireCache}}', 'POOJurisdictionalUnitParentUnit', 'pooJurisdictionalUnitParentUnit' );
        $this->renameColumn ('{{%fireCache}}', 'InitialResponseDateTime', 'initialResponseDateTime' );
        $this->renameColumn ('{{%fireCache}}', 'InitialFireStrategyMetIndicator', 'initialFireStrategyMetIndicator');
        $this->renameColumn ('{{%fireCache}}', 'FinalStrategyAttainedDateTime', 'finalStrategyAttainedDateTime');
        $this->renameColumn ('{{%fireCache}}', 'FireGrowthCessationDateTime', 'fireGrowthCessationDateTime' );
        $this->renameColumn ('{{%fireCache}}', 'PredominantFuelModel', 'predominantFuelModel');
        $this->renameColumn ('{{%fireCache}}', 'FinalFireReportApprovedByUnit','finalFireReportApprovedByUnit' );
        $this->renameColumn ('{{%fireCache}}', 'FinalFireReportApprovedBy', 'finalFireReportApprovedBy');
        $this->renameColumn ('{{%fireCache}}', 'FinalFireReportApprovedByTitle','finalFireReportApprovedByTitle' );
        $this->renameColumn ('{{%fireCache}}', 'FinalFireReportApprovedDate', 'finalFireReportApprovedDate' );
        $this->renameColumn ('{{%fireCache}}', 'FinalFireReportNarrative','finalFireReportNarrative' );
        $this->renameColumn ('{{%fireCache}}', 'UnifiedCommand', 'unifiedCommand');
        $this->renameColumn ('{{%fireCache}}', 'WFDSSDecisionStatus', 'wfdssDecisionStatus' );
        $this->renameColumn ('{{%fireCache}}', 'FireBehaviorGeneral', 'fireBehaviorGeneral' );
        $this->renameColumn ('{{%fireCache}}', 'FireBehaviorGeneral1', 'fireBehaviorGeneral1');
        $this->renameColumn ('{{%fireCache}}', 'FireBehaviorGeneral2', 'fireBehaviorGeneral2' );
        $this->renameColumn ('{{%fireCache}}', 'FireBehaviorGeneral3', 'fireBehaviorGeneral3' );
        $this->renameColumn ('{{%fireCache}}', 'FireBehaviorDescription', 'fireBehaviorDescription');
        $this->renameColumn ('{{%fireCache}}', 'SecondaryFuelModel', 'secondaryFuelModel' );
        $this->renameColumn ('{{%fireCache}}', 'AdditionalFuelModel','additionalFuelModel' );
        $this->renameColumn ('{{%fireCache}}', 'SummaryFuelModel','summaryFuelModel' );
        $this->renameColumn ('{{%fireCache}}', 'FireStrategyMonitorPercent', 'fireStrategyMonitorPercent' );
        $this->renameColumn ('{{%fireCache}}', 'FireStrategyConfinePercent','fireStrategyConfinePercent' );
        $this->renameColumn ('{{%fireCache}}', 'FireStrategyPointZonePercent','fireStrategyPointZonePercent' );
        $this->renameColumn ('{{%fireCache}}', 'FireStrategyFullSuppPercent', 'fireStrategyFullSuppPercent');
        $this->renameColumn ('{{%fireCache}}', 'ProjectedIncidentActivity24','projectedIncidentActivity24');
        $this->renameColumn ('{{%fireCache}}', 'ProjectedIncidentActivity48','projectedIncidentActivity48');
        $this->renameColumn ('{{%fireCache}}', 'ProjectedIncidentActivity72', 'projectedIncidentActivity72' );
        $this->renameColumn ('{{%fireCache}}', 'ProjectedIncidentActivity72Plus', 'projectedIncidentActivity72Plus');
        $this->renameColumn ('{{%fireCache}}', 'FiscallyResponsibleUnit', 'fiscallyResponsibleUnit' );
        

        $this->addColumn ('{{%fireCache}}', 'mergeParentIrwinId', $this->string());

        $this-> alterColumn ('{{%fireCache}}', 'summaryFuelModel', $this->text() );
        $this-> alterColumn ('{{%fireCache}}', 'additionalFuelModel', $this->text() );
        $this-> alterColumn ('{{%fireCache}}', 'secondaryFuelModel', $this->text() );
        $this-> alterColumn ('{{%fireCache}}', 'fireBehaviorGeneral3', $this->text() );
        $this-> alterColumn ('{{%fireCache}}', 'fireBehaviorGeneral2', $this->text() );
        $this-> alterColumn ('{{%fireCache}}', 'fireBehaviorGeneral1', $this->text() );
        $this-> alterColumn ('{{%fireCache}}', 'fireBehaviorGeneral', $this->text() );

        $this->addColumn ('{{%fireCache}}', 'criticalResourceNeeds', $this->string());
        $this->addColumn ('{{%fireCache}}', 'fireDepartmentID', $this->string());
        $this->addColumn ('{{%fireCache}}', 'hasFatalities', $this->boolean());
        $this->addColumn ('{{%fireCache}}', 'hasInjuries', $this->boolean());
        $this->addColumn ('{{%fireCache}}', 'inFuelTreatment', $this->boolean());
        $this->addColumn ('{{%fireCache}}', 'inNFPORS', $this->boolean());
        $this->addColumn ('{{%fireCache}}', 'isFireCauseInvestigated', $this->boolean());
        $this->addColumn ('{{%fireCache}}', 'isFireCodeRequested', $this->boolean());
        $this->addColumn ('{{%fireCache}}', 'isInitialFireStrategyMet', $this->boolean());
        $this->addColumn ('{{%fireCache}}', 'isQuarantined', $this->boolean());
        $this->addColumn ('{{%fireCache}}', 'isUnifiedCommand', $this->boolean());
        $this->addColumn ('{{%fireCache}}', 'pooDispatchCenterID', $this->string());
        $this->addColumn ('{{%fireCache}}', 'pooJurisdictionalAgency', $this->string());
        $this->addColumn ('{{%fireCache}}', 'pooPredictiveServiceAreaID', $this->string());
        $this->addColumn ('{{%fireCache}}', 'pooProtectingAgency', $this->string());
        $this->addColumn ('{{%fireCache}}', 'predominantFuelGroup', $this->string());


        $this-> alterColumn ('{{%fireCache}}', 'isComplex', $this->boolean() );
        $this-> alterColumn ('{{%fireCache}}', 'isFSAssisted', $this->boolean() );
        $this-> alterColumn ('{{%fireCache}}', 'isMultiJurisdictional', $this->boolean() );
        $this-> alterColumn ('{{%fireCache}}', 'isTrespass', $this->boolean() );
        $this-> alterColumn ('{{%fireCache}}', 'isReimbursable', $this->boolean() );
        $this-> alterColumn ('{{%fireCache}}', 'isValid', $this->boolean() );
        $this-> alterColumn ('{{%fireCache}}', 'isFireCauseInvestigated', $this->boolean() );
        $this-> alterColumn ('{{%fireCache}}', 'isFireCodeRequested', $this->boolean() );
        $this-> alterColumn ('{{%fireCache}}', 'isInitialFireStrategyMet', $this->boolean() );
        $this-> alterColumn ('{{%fireCache}}', 'isQuarantined', $this->boolean() );
        $this-> alterColumn ('{{%fireCache}}', 'isUnifiedCommand', $this->boolean() );
        $this-> alterColumn ('{{%fireCache}}', 'inConflict', $this->boolean() );


        $this->createIndex ('indxFireCache', '{{%fireCache}}', [
            'irwinID',
            'incidentName',
            'pooLatitude',
            'pooLongitude'
        ],$unique = false);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%fireCache}}');
    }
}
