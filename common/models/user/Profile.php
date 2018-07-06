<?php

namespace common\models\user;
use yii\helpers\ArrayHelper;


class Profile extends \ptech\pyrocms\models\user\Profile
{
    const ALL_EMAILS = 100;
    const ALERTS_EMAILS_ONLY  = 2;
    const NO_EMAILS  = 0;


    public function rules()
   {
       return ArrayHelper::merge(parent::rules(),[
           ['alert_dist', 'integer','min'=>1,'max'=>100],
           ['alert_dist','required']
       ]);
   }
   public function attributeLabels()
   {
       return ArrayHelper::merge(parent::rules(),[
           'alert_dist' => 'Alert Distance',
       ]);
   }


}
