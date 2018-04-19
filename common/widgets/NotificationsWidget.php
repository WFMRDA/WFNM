<?php

namespace common\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use common\models\helpers\WfnmHelpers;
use common\models\messages\Messages;
use yii\helpers\Json;
use yii\helpers\Url;

class NotificationsWidget extends Widget
{

    const NAV = 'nav';
    const PANEL = 'panel';

    public $dataProvider;
    public $clientOptions;
    public $options = [];
    public $unreadTotal = 0;
    public $alertsUnread = 0;

    protected $_badge;
    protected $_type;
    protected $_vulcanAlert;
    protected $_vulcan;
    protected $_msgRead;
    protected $_userNotification;
    protected $_html;
    protected $_seenTimes = [];
    protected $_messageTimes = [];



    public function init()
    {
        parent::init();


        if ($this->dataProvider === null) {
            throw new InvalidConfigException('The "dataProvider" property must be set.');
        }

        $this->prepareModel();
    }

    public function run()
    {
        parent::run();
        return ($this->_type == self::NAV) ? $this->renderNavBody() : $this->renderPanelBody() ;
    }

    public function renderNavBody(){

        if(!empty($this->_seenTimes) && !empty($this->_messageTimes) ){
            $maxSeenTime = max($this->_seenTimes);
            $messageTime = max($this->_messageTimes);
            // Yii::trace($this->_seenTimes,'dev');
            // Yii::trace($messageTime,'dev');
            if($messageTime <= $maxSeenTime){
                $this->unreadTotal = 0;
            }else{
                foreach ($this->_messageTimes as $key => $time) {
                    if($time < $maxSeenTime){
                        ArrayHelper::remove($this->_messageTimes,$key);
                    }
                }
                $this->unreadTotal = count($this->_messageTimes);
            }
            //check to see if there's a message seen AFTER the highest Max Time
        }else{
            $this->unreadTotal = count($this->_messageTimes);
        }

        $badge = ($this->unreadTotal === 0)?'':$this->unreadTotal;
        $url = Url::to(['map-rest/alerts']);
$html = <<<HTML
<li id="top-notifications-li" class="dropdown notifications-menu">
    <a id="top-notification-href" href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <span id = "header-notification-label" class="label label-warning label-as-badge">{$badge}</span>
    </a>
    <ul id="notifications-dropdown-menu" class="dropdown-menu">
        <div class="panel panel-default">
            <div class="panel-heading text-center">You have <span id="notifications-num">{$this->unreadTotal}</span> new notifications</div>
            <div class="panel-body">
                <ul class="menu">
                    <div id="notifications-div">{$this->_html}</div>
                </ul>
            </div>
            <div class="panel-footer text-center"><a class="legend-btn" href="{$url}"id ="liNotif">View All Notifications</a></div>
        </div>
    </ul>
</li>
HTML;
        return $html;
    }
    public function renderPanelBody(){
        $badge = ($this->unreadTotal === 0)?'':$this->unreadTotal;
$html = <<<HTML
<div id="notifications-panel-div">{$this->_html}</div>
HTML;
        return $html;
    }

    protected function prepareModel(){
        $this->_type = ArrayHelper::getValue($this->options,'type',self::NAV);
        $this->options['id'] = (isset($this->options['id']))?$this->options['id']:$this->getId().'_notif_header';
        if ($this->_type === null) {
            throw new InvalidConfigException('The "Options Type" property must be set and cannot be NULL.');
        }
        $models = array_values($this->dataProvider->getModels());
        $keys = $this->dataProvider->getKeys();
        $rows = [];
        foreach ($models as $index => $model) {
            $key = $keys[$index];
            if($model->type = Messages::ALERTS ){
                $rows[] = $this->create_header_alert_line($model);
            }else if($model->type = Messages::UPDATES ){
                $rows[] = $this->create_header_notification_line($model);
            }
        }
        $this->_html = implode(PHP_EOL, $rows);
    }//End Get Notifications

    protected function getStatusColor($status){

        switch ($status) {
            case 'alert':
                $indicator = 'yellow';
                break;
            case 'watch':
                $indicator = 'orange';
                break;
            case 'warning':
                $indicator = 'red';
                break;
            default:
                $indicator = 'green';
                break;
        }
        return $indicator;
    }

    protected function create_header_alert_line($model){
        // Yii::trace($model->attributes,'dev');
        /*Alert = <10 miles
        Warning = <= 25 miles
        Watch = <= 40 miles
        Notify = <= 60 miles*/
        if($model->seen_at == null || $model->seen_at == 0){
            $read_status = 'unread';
        }else{
            $read_status = 'read';
            $this->_seenTimes[] = $model->seen_at;
        }
        $this->_messageTimes[] = $model->created_at;
        // $read_status = ($model->seen_at == null || $model->seen_at == 0)?'unread':'read';
        $html = '
        <li class="'. $read_status . '">
            <p notif="ALV-' . $model->id . '" data-key="' . $model->irwinID . '" class="notifications ">
                <i class="fa fa-clock-o"> ' . WfnmHelpers::humanTiming($model->created_at) . '</i>
                <i class="fa fa-exclamation-triangle text-' . $this->getStatusColor($model) . '"></i> ' . $model->subject . '
            </p>
        </li>';

        return $html;
    }

}
