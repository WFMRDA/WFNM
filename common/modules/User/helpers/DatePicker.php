<?php

namespace common\modules\User\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Object;

class DatePicker extends Object
{
	public static function getMonths()
	{
	 	$array  = [
            ['id' => '1' , 'name' =>  'January'],
            ['id' => '2' , 'name' =>  'February'],
            ['id' => '3' , 'name' =>  'March'],
            ['id' => '4' , 'name' =>  'April'],
            ['id' => '5' , 'name' =>  'May'],
            ['id' => '6' , 'name' =>  'June'],
            ['id' => '7' , 'name' =>  'July'],
            ['id' => '8' , 'name' =>  'August'],
            ['id' => '9' , 'name' =>  'September'],
            ['id' => '10', 'name' =>  'October'],
            ['id' => '11', 'name' =>  'November'],
            ['id' => '12', 'name' =>  'December'],
        ]; 
         $array =  ArrayHelper::map($array,'id','name');
         return $array;
	}
	
	public static function getYears(){
		$date = new \DateTime();
		$date->setTimezone(new \DateTimeZone('UTC'));
		$start_date = (int)$date->format('Y');
		$endyear = $start_date - 110;
		$array = [];
		for ($i=$start_date; $i >= $endyear ; $i--) { 
			$array[]= ['id' => $i , 'name' => $i];
		}

	 	$array =  ArrayHelper::map($array,'id','name');
     	return $array;
	}

	public static function getDays(){
		$array = [];
		for ($i=1; $i <= 31 ; $i++) { 
			$array[]= ['id' => $i , 'name' => $i];
		}

	 	$array =  ArrayHelper::map($array,'id','name');
     	return $array;
	}


	public static function getEvalCycle(){
		$qtr  = ['10'=>'Winter','20'=>'Spring','30'=>'Summer','40'=>'Fall'];

        $date = new \DateTime();
		$date->setTimezone(new \DateTimeZone('UTC'));
		$endyear = 2014;
		$start_date = (int)$date->format('Y');
		$year = [];
		for ($i=$start_date; $i >= $endyear ; $i--) { 
			$year[$i] = $i;
		}

		return array($qtr,$year);

	}

	public function getWeek($week = 0,$format = 'm/d/Y'){
	    $date = new \DateTime($week . ' week');
	    $date->setTimezone(new \DateTimeZone('UTC'));
	    $dt_min = clone($date);
	    $dt_min->modify('last sunday');
	    $dt_max = clone($dt_min);
	    $week_no = $dt_min->format("W");
	    $dt_max->modify('+6 Days'); 
	    $dt_min = $dt_min->format($format);
	    $dt_max = $dt_max->format('M d Y');
	    return array($dt_min,$dt_max,$week);
	}

	public function getWeekTimestamp($week = 0){
	    $date = new \DateTime($week . ' week');
	    $date->setTimezone(new \DateTimeZone('UTC'));
	    $dt_min = clone($date);
	    $dt_min->modify('last sunday');
	    $dt_max = clone($dt_min);
	    $dt_max->modify('+7 Days'); 
	    $dt_min = $dt_min->getTimestamp();
	    $dt_max = $dt_max->getTimestamp();
	    return array($dt_min,$dt_max);
	}

	public function getMonth($month = 0,$format = 'm/d/Y'){
	    $date = new \DateTime($month . ' month');
	    $date->setTimezone(new \DateTimeZone('UTC'));
	    $dt_min = clone($date);
	    $dt_min->modify('first day of this month');
	    $dt_max = clone($dt_min);
	    $week_no = $dt_min->format("W");
	    $dt_max->modify('last day of this month'); 
	    $dt_min = $dt_min->format($format);
	    $dt_max = $dt_max->format($format);
	    return array($dt_min,$dt_max,$month);
	}

	public function getMonthTimestamp($month = 0){
	    $date = new \DateTime($month . ' month');
	    $date->setTimezone(new \DateTimeZone('UTC'));
	    $dt_min = clone($date);
	    $dt_min->modify('first day of this month');
	    $dt_max = clone($dt_min);
	    $week_no = $dt_min->format("W");
	    $dt_max->modify('last day of this month');
	    $dt_min = $dt_min->getTimestamp();
	    $dt_max = $dt_max->getTimestamp();
	    return array($dt_min,$dt_max);
	}




}