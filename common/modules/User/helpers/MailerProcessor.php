<?php

namespace common\modules\User\helpers;

use Yii;
use yii\helpers\ArrayHelper;


class MailerProcessor{


	public function processHtml($user,$str,$options = []){
        $mergeVars = $this->getMergeVars();
        foreach ($mergeVars as $value) {
			 $needle = '*|' . $value . '|*';
            /*
            * Check to see if this Variable has been set By User.
            * If it's been set by the user then we won't set it. We'll set it in the options function
            */
            if (empty($options[$value])) {
	            if($this->inString($needle,$str)){
                    $str = str_replace($needle, $this->getMergeValue($value,$user),$str);
                }
            }else{
            	if($this->inString($needle,$str)){
	                $str = str_replace($needle, $options[$value] ,$str);
	                unset($options[$value]);
	           	}
	        }
        }

        foreach ($options as $key => $value) {
		 	$needle = '*|' . $key . '|*';
        	if($this->inString($needle,$str)){
                $str = str_replace($needle, $options[$key] ,$str);
                unset($options[$value]);
           	}
        }
        return $str;
    }

	public function  getMergeVars(){
        return [
           'FNAME','LNAME', 'FULLNAME', 'HOME_URL',
           'DATE','CURRENT_YEAR','USER_EMAIL','USER_USERNAME',
       ];
    }

    private function getMergeValue($var,$user){
        $dataArray =  $this->mergeDataArray($var,$user);
        return ArrayHelper::getValue($dataArray, $var,'');
    }

    protected function mergeDataArray($var,$user){
        return [
            'FNAME'         => $user['profile']['first_name'],
            'LNAME'         => $user['profile']['last_name'],
            'FULLNAME'      => $user['fullName'],
            'HOME_URL'      => Yii::$app->homeUrl,
            'DATE'          => Yii::$app->formatter->asDate(time()),
            'CURRENT_YEAR'  => Yii::$app->formatter->asDate(time(),'y'),
            'USER_EMAIL'    => $user['email'],
            'USER_USERNAME' => $user['username'],
        ];
    }

	public function inString($needle,$haystack){
        return (stripos($haystack,$needle) !== FALSE)?true:false;
    }


}

?>
