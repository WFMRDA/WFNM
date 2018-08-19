<?php

namespace common\models\helpers;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\myFires\MyFires;
use common\models\system\System;
use common\models\messages\Messages;
use common\models\myLocations\MyLocations;
use common\models\popup\PopTable;


class WfnmHelpers extends YiiHelpers
{

    /**
     * @var int Seconds till next 5 min clock interval which the fire cache needs to be reset.
     */
    protected static $_nextRefreshTime;

     /**
     *  Sets Next Time Refresh Variable for till the next 5 min clock interval which the fire cache needs to be reset.
     */
    protected static function setNextRefreshTime(){
        $now = time();
        $duration = (int) (ceil($now/(300))* 300) - $now;
        if ($duration === 0) {
            $duration = 300;
        }
        self::$_nextRefreshTime = $duration;
    }

    /**
     * Gets Next Time Refresh Variable for till the next 5 min clock interval which the fire cache needs to be reset.
     * @return int Seconds till next 5 min clock interval which the fire cache needs to be reset.
     */
    public static function getNextRefreshTime(){
        if(!isset(self::$_nextRefreshTime)){
            self::setNextRefreshTime();
        }
        return self::$_nextRefreshTime;
    }
    /**
     * Finds the MyFires model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MyFires the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public static function getMyFires()
    {
        $models = MyFires::findAll(['user_id'=>Yii::$app->user->identity->id]);
        $mapData = Yii::createObject(Yii::$app->params['mapData']);
        return $mapData->buildFireList($models);
    }

    public static function findMyAlerts($offset = 0){
        $badge = 0;
        $unreadTotal = 0;
        $model = PopTable::find()
           ->andWhere(['and',
                ['user_id' => Yii::$app->user->identity->id],
                ['type' => PopTable::NOTIFICATIONS]
            ])->one();

        if($model != null){

            $lastViewed = (  $model->seen_at == null)? 0: $model->seen_at;
        }else {
            $lastViewed = 0;
        }

        $que =   Messages::find()
            ->andWhere(['user_id' => Yii::$app->user->identity->id])
            ->orderBy([
                // 'created_at' => SORT_ASC,
                'created_at' => SORT_DESC,
            ])->limit(100)->offset($offset)->asArray()->all();
        foreach ($que as &$model) {
            $val['timeLapse'] =  WfnmHelpers::humanTiming($model['created_at']);
            if($model['created_at'] > $lastViewed){
                $badge++;
            }
            if($model['seen_at']  == null || $model['seen_at'] == 0){
                $unreadTotal++;
            }
        }
        return [
            'dataSet'=>$que,
            'badge' => $badge,
            'unreadTotal' => $unreadTotal,
        ];
    }

    public static function findMyLocations()
    {
        return  MyLocations::findAll(['user_id'=>Yii::$app->user->identity->id]);
    }

    public static function getSitReportData(){

        $mapData = Yii::createObject(Yii::$app->params['mapData']);
        // Yii::trace($mapData->wfnmData,'dev');
        return ['sitreport'=>$mapData->sitReportInfo,'fireDb'=>$mapData->wfnmData];
    }

    public static function getMapFires(){
        $mapData = Yii::createObject(Yii::$app->params['mapData']);
        return ['wfnm' => $mapData->getWfnmGeoJsonLayer(),'layers'=>Yii::$app->appSystemData->mapLayers];
    }

    public static function getFireData(){
        $mapData = Yii::createObject(Yii::$app->params['mapData']);
        $data = [
            'geoJson' => $mapData->getWfnmGeoJsonLayer(),
            'layers' => Yii::$app->appSystemData->mapLayers,
            'sitReport' => $mapData->sitReportInfo,
            'fullFireDb' => $mapData->wfnmData
        ];
        return $data;
    }


    public static function inString($needle,$haystack){
        return (($needle != null) && (stripos($haystack,$needle) !== FALSE))?true:false;
    }

    public static function getClassImg($class){
        $str = strtolower($class);
        switch ($str) {
            case 'new':
                $img = self::img('@media/map_new_fire.png',['class'=>'table-fire-logo']);
                break;
            case 'emerging':
                $img = self::img('@media/map_emerging_fire.png',['class'=>'table-fire-logo']);
                break;
            case 'contained':
                $img = self::img('@media/map_contained_fire.png',['class'=>'table-fire-logo']);
                break;
             case 'controlled':
                $img = self::img('@media/map_controlled_fire.png',['class'=>'table-fire-logo']);
                break;
             case 'active':
                $img = self::img('@media/map_active_fire.png',['class'=>'table-fire-logo']);
                break;
            case 'out':
                $img = self::img('@media/map_out_fire.png',['class'=>'table-fire-logo']);
                break;
            default:
                $img ='';
                break;
        }
        return $img;
    }

    public static function getPrepLevel($loc){
        $mapData = Yii::createObject(Yii::$app->params['mapData']);
        //Get Key
        $library = [
            'NICC' => 'NIC',
            'AKCC' => 'ACC',
            'EACC' => 'EACC',
            'GBCC' => 'GBC',
            'ONCC' => 'ONCC',
            'NRCC' => 'NRC',
            'NWCC' => 'NWCC',
            'RMCC' => 'RMC',
            'SACC' => 'SAC',
            'OSCC' => 'OSCC',
            'SWCC' => 'SWC',
        ];
        return $mapData->getPrepardnessLevel(ArrayHelper::getValue($library,$loc));
    }

    public static function getFireInfo($fid){
        $mapData = Yii::createObject(Yii::$app->params['mapData']);
        return $mapData->getFireInfo($fid);
    }
    /*
        If you want to get difference between arrays recursively, try this function:
        http://stackoverflow.com/a/33993236
    */
    public static function arrayDiffRecursive($firstArray, $secondArray, $reverseKey = false)
    {
        $oldKey = 'old';
        $newKey = 'new';
        if ($reverseKey) {
            $oldKey = 'new';
            $newKey = 'old';
        }
        $difference = [];
        foreach ($firstArray as $firstKey => $firstValue) {
            if (is_array($firstValue)) {
                if (!array_key_exists($firstKey, $secondArray) || !is_array($secondArray[$firstKey])) {
                    $difference[$oldKey][$firstKey] = $firstValue;
                    $difference[$newKey][$firstKey] = '';
                } else {
                    $newDiff = arrayDiffRecursive($firstValue, $secondArray[$firstKey], $reverseKey);
                    if (!empty($newDiff)) {
                        $difference[$oldKey][$firstKey] = $newDiff[$oldKey];
                        $difference[$newKey][$firstKey] = $newDiff[$newKey];
                    }
                }
            } else {
                if (!array_key_exists($firstKey, $secondArray) || $secondArray[$firstKey] != $firstValue) {
                    $difference[$oldKey][$firstKey] = $firstValue;
                    $difference[$newKey][$firstKey] = $secondArray[$firstKey];
                }
            }
        }
        return $difference;
    }



    public static function getFireMonitoringBtn($irwin){
        // Yii::trace($irwin,'dev');
        $myfire = MyFires::find()->where(['and',['user_id'=> Yii::$app->user->identity->id,'irwinID'=>$irwin['irwinID']]])->one();
        if($myfire == null){
            $html = self::a('Follow Fire',null,['class'=>'btn btn-success follow-fire','data'=>['id'=>$irwin['irwinID']]]);
        }else{
            $html = self::a('Unfollow Fire',null,['class'=>'btn btn-danger unfollow-fire','data'=>['id'=>$irwin['irwinID']]]);
        }
        return $html;
    }

    public static function isUserFollowing($userId,$fireId){
        return MyFires::find()->where(['and',['user_id'=> $userId,'irwinID'=>$fireId]])->exists();
    }

    public static function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = \DateTime::createFromFormat($format, $date);

        return ($d && $d->format($format) == $date) &&  ($d <= new \DateTime() && $d >= new \DateTime('-1 week')) ;
    }

    // Function for basic field validation (present and neither empty nor only white space
    public static function isEmpty($question){
        return (!isset($question) || trim($question)==='');
    }

    public static function getAlertsLine($model){
        // Yii::trace($model->attributes,'dev');
        $baseData = json_decode($model->data,true);
        $firename = ArrayHelper::getValue($baseData,'incidentName','') . ' Fire';
        $timing = self::humanTiming($model->created_at) ;
        $message = $model->body;
        $fireUrl = Yii::$app->urlManager->createAbsoluteUrl(['site','fid'=>$model->irwinID]);
        return '<li class="fire-line-desc"><p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;display:inline;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">'.$firename.' :</p><img src="http://media.wildfiresnearme.wfmrda.com/img/mini_timer.png" style="-ms-interpolation-mode:bicubic;clear:both;display:inline;max-width:100%;outline:0;text-decoration:none;width:auto"><p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;display:inline;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">'.$timing.' ago. '.$message.'</p><table class="button small expanded" style="Margin:0 0 16px 0;border-collapse:collapse;border-spacing:0;margin:0 0 16px 0;padding:0;text-align:left;vertical-align:top;width:100%!important"><tr style="padding:0;text-align:left;vertical-align:top"><td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word"><table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%"><tr style="padding:0;text-align:left;vertical-align:top"><td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;background:#2199e8;border:2px solid #2199e8;border-collapse:collapse!important;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:5px 10px 5px 10px;text-align:left;vertical-align:top;word-wrap:break-word"><center data-parsed="" style="min-width:none!important;width:100%"><a href="'.$fireUrl.'" target="_blank" align="center" class="float-center" style="Margin:0;border:0 solid #2199e8;border-radius:3px;color:#fefefe;display:inline-block;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:700;line-height:1.3;margin:0;padding:5px 10px 5px 10px;padding-left:0;padding-right:0;text-align:center;text-decoration:none;width:100%">See '.$firename.' Alert</a></center></td></tr></table></td><td class="expander" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0!important;text-align:left;vertical-align:top;visibility:hidden;width:0;word-wrap:break-word"></td></tr></table></li>';
    }
    public static function getUpdatesLine($model){
        // Yii::trace($model->attributes,'dev');
        $baseData = ArrayHelper::getValue(json_decode($model->data,true),'baseData',[]);
        $firename = ArrayHelper::getValue($baseData,'incidentName','') . ' Fire';
        $timing = self::humanTiming($model->created_at) ;
        $message = $model->body;
        $updateUrl = Yii::$app->urlManager->createAbsoluteUrl(['site','fid'=>$model->irwinID]);
        return '<li class="fire-line-desc"><p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;display:inline;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">'.$firename.' :</p><img src="http://media.wildfiresnearme.wfmrda.com/img/mini_timer.png" style="-ms-interpolation-mode:bicubic;clear:both;display:inline;max-width:100%;outline:0;text-decoration:none;width:auto"><p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;display:inline;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">'.$timing.' ago. '.$message.'</p><table class="button small expanded" style="Margin:0 0 16px 0;border-collapse:collapse;border-spacing:0;margin:0 0 16px 0;padding:0;text-align:left;vertical-align:top;width:100%!important"><tr style="padding:0;text-align:left;vertical-align:top"><td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word"><table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%"><tr style="padding:0;text-align:left;vertical-align:top"><td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;background:#2199e8;border:2px solid #2199e8;border-collapse:collapse!important;color:#fefefe;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:5px 10px 5px 10px;text-align:left;vertical-align:top;word-wrap:break-word"><center data-parsed="" style="min-width:none!important;width:100%"><a href="'.$updateUrl.'" target="_blank" align="center" class="float-center" style="Margin:0;border:0 solid #2199e8;border-radius:3px;color:#fefefe;display:inline-block;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:700;line-height:1.3;margin:0;padding:5px 10px 5px 10px;padding-left:0;padding-right:0;text-align:center;text-decoration:none;width:100%">See '.$firename.' Update</a></center></td></tr></table></td><td class="expander" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0!important;text-align:left;vertical-align:top;visibility:hidden;width:0;word-wrap:break-word"></td></tr></table></li>';
    }
}
