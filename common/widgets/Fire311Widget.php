<?php

namespace common\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use common\models\helpers\WfnmHelpers;
use Yii;

class Fire311Widget extends Widget
{
    const NAV = 'nav'; 
    const PANEL = 'panel'; 

    public $dataProvider;
    public $options = [];
    public $unreadTotal = 0;
    public $alertsUnread = 0;

    protected $_type;
    protected $_vulcanAlert;
    protected $_vulcan;
    protected $_msgRead;
    protected $_userNotification;
    protected $_html;   

    
    public function init()
    {
        parent::init();

        if ($this->dataProvider === null) {
            throw new InvalidConfigException('The "dataProvider" property must be set.');
        }

        $this->prepareModel();

        if ($this->_type === null) {
            throw new InvalidConfigException('The "Options Type" property must be set and cannot be NULL.');
        }


    }

    public function run()
    {
        return  $this->renderBody();
    }
    protected function prepareModel(){

        $this->_type = ArrayHelper::getValue($this->options,'type',self::NAV);
        $this->_vulcanAlert = ArrayHelper::getValue($this->dataProvider,'vulcanAlert',[]);
        $this->_vulcan = ArrayHelper::getValue($this->dataProvider,'vulcan',[]);
        $this->_msgRead = ArrayHelper::getValue($this->dataProvider,'msgRead',[]);
        $this->_userNotification = ArrayHelper::getValue($this->dataProvider,'userNotification',[]);
        $this->getUserMonitoringList();
        // $id = Yii::$app->user->identity->id;
        // // $vulcan = new WFNMSystem;
        // $vulcan = \Yii::createObject([
        //     'class'          => 'common\models\system\WFNMSystem',
        // ]);
        // $tables = new FireDBTables;
        // //get Vulcan 311 Fires List
        // list($user_fire_table,$count) = $tables->getUserMonitoringList($id);
            
        // list($unread,$alerts_unread,$notification_html) = $vulcan->getNotifications(Yii::$app->user->identity->id,'header');
        // // $plLevel= WfnmHelpers::getGaccPrepLevel('NICC'); 
        
        // $json = array('monitoringCount' => $count ,'monitoringList' => $user_fire_table ,'notification_num' => $unread ,'alerts_num' => $alerts_unread, 'notification_html' => $notification_html);
        
    }
    public function renderBody(){
        $count = count($this->_vulcan);
$html = <<<HTML
<li id="top-monitoring-li" class="dropdown monitoring-menu">
    <a id="top-monitoring-href" href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-flag-o"></i>
    </a>
    <ul id="monitoring-dropdown-menu" class="dropdown-menu">
        <div class="panel panel-default">
            <div class="panel-heading text-center">You are monitoring <span id="monitoring-num">{$count}</span> fires</div>
            <div class="panel-body">
                <ul class="menu">
                    <div id="311_monitoring-div">{$this->_html}</div>
                </ul>
            </div>
            <div class="panel-footer text-center"><a id ="liNotif">View All Monitored Fires</a></div>
        </div>
    </ul>
</li>
HTML;
        return $html;
    }

    protected function getUserMonitoringList(){
        $status = false;
        $vulcan = $this->_vulcan;
        
        // Yii::trace($vulcan,'dev');
        foreach ($vulcan as $key => $record){
            //Get Fire Info
            // $data_array = IrwinDb::find()
            //     ->where(['irwinID' => $data['fire_id']])
            //     ->asArray()
            //     ->one(); 
            //Build Table Line
            $htmlArray[] = $this->createMonitoringHeaderLine($record['fireInfo']);
        }
        //Build Monitoring Fire Drop Down Html
        $this->_html = (isset($htmlArray))?$this->createMonitoringHeaderHtml($htmlArray):'';
    }

    protected function createMonitoringHeaderLine($data){
        
        $state = str_replace('US-','',$data['pooState']);
            $row = "<tr id='{$data['irwinID']}'> 
                    <td>{$data['incidentName']}</td>
                </tr>";


        $html = '
        <li>
            <a href="#" rel="' . $data['irwinID'] . '" class="fire311monitoring smaller-font">
                <i class="fa fa-fire-triangle text-red"></i> ' . $row . ' 
            </a>
        </li>';
        
        return $html;
    }

    protected function createMonitoringHeaderHtml($lines=[]){
        $notification_html = '<li><ul class="inner-menu">';
        if(!empty($lines)){
            foreach ($lines as $line) {
                if(is_array($line)){
                    foreach ($line as $text) {
                        $notification_html .= $text;
                    }
                }else{
                    $notification_html .= $line;
                }
            }
        }
        $notification_html .= '</ul></li>';

        return $notification_html;
    }
}