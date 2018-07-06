<?php
/**
 * @link https://wfmrda.nwcg.gov/
 * @copyright Copyright (c) 2017 Wildland Fire Management Research Development & Applications
 * @license https://wfmrda.nwcg.gov/developerlicense
 */

namespace common\models\sitReport;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

/**
 * SitReportData provides data about the National Sit Report in a usable format
 * @author Reginald Goolsby <Rjgoolsby@fs.fed.us>
 * @since 1.0
 */
class SitReportData extends Model
{
    public static function getGaccPrepLevel($key){
        $key = strtoupper($key);
        $query = Prepardnesslevels::findOne($key);
        return ArrayHelper::getValue($query,'gacc_pl',''); //(isset($query->gacc_pl))? $query->gacc_pl :'';
        //return $query->gacc_pl;
    }

    public static function getAllPrepLevel(){
        $query = Prepardnesslevels::find()
            ->asArray()
            ->all();

        $array = ArrayHelper::map($query, 'id', 'gacc_pl');

        return $array;
    }
    protected $_sitReportData;
    protected function setSitReportData(){
        $array = [];
        $query = SitReport::find()
            ->select('id,label,val')
            ->asArray()
            ->all();
        foreach ($query as $key => $value) {
            $array[$value['id']] = $value;
        }
        $this->_sitReportData = $array;
    }
    /**
     * Returns the list of attached event handlers for an event.
     * You may manipulate the returned [[Vector]] object by adding or removing handlers.
     * @return Array list of attached event handlers for the event
     */
    public function getSitReportData(){
        if(!isset($this->_sitReportData)){
            $this->setSitReportData();
        }
        return $this->_sitReportData;
    }

}
