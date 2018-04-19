<?php
namespace common\models\helpers;

use yii\helpers\Html;
use Yii;

/**
 * NEFI is the model behind the dictionary of Info needed. 
 */



class GPS {


    /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*::      http://www.geodatasource.com/developers/php                        :*/
    /*::  This routine calculates the distance between two points (given the     :*/
    /*::  latitude/longitude of those points). It is being used to calculate     :*/
    /*::  the distance between two locations using GeoDataSource(TM) Products    :*/
    /*::                     													 :*/
    /*::  Definitions:                                                           :*/
    /*::    South latitudes are negative, east longitudes are positive           :*/
    /*::                                                                         :*/
    /*::  Passed to function:                                                    :*/
    /*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
    /*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
    /*::    unit = the unit you desire for results                               :*/
    /*::           where: 'M' is statute miles                                   :*/
    /*::                  'K' is kilometers (default)                            :*/
    /*::                  'N' is nautical miles                                  :*/
    /*::  Worldwide cities and other features databases with latitude longitude  :*/
    /*::  are available at http://www.geodatasource.com                          :*/
    /*::                                                                         :*/
    /*::  For enquiries, please contact sales@geodatasource.com                  :*/
    /*::                                                                         :*/
    /*::  Official Web site: http://www.geodatasource.com                        :*/
    /*::                                                                         :*/
    /*::         GeoDataSource.com (C) All Rights Reserved 2015                  :*/                     
    /*::echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";:*/
    /*::echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";:*/
    /*::echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";:*/
    /*::                                                                         :*/
    /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    public static function distance($lat1, $lon1, $lat2, $lon2, $unit = "M") {

      $theta = $lon1 - $lon2;
      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $miles = $dist * 60 * 1.1515;
      $unit = strtoupper($unit);

      if ($unit == "K") {
        return ($miles * 1.609344);
      } else if ($unit == "N") {
          return ($miles * 0.8684);
        } else {
            return $miles;
          }
    }

    /*
        http://blog.fedecarg.com/2009/02/08/geo-proximity-search-the-haversine-equation/
        $radius = 20; // in miles
    */

    public static function getBoundingBox($longitude,$latitude,$radius){
        $longitude = (float) $longitude;
        $latitude = (float) $latitude;

        $lng_min = $longitude - $radius / abs(cos(deg2rad($latitude)) * 69);
        $lng_max = $longitude + $radius / abs(cos(deg2rad($latitude)) * 69);
        $lat_min = $latitude - ($radius / 69);
        $lat_max = $latitude + ($radius / 69);

        // echo 'lng (min/max): ' . $lng_min . '/' . $lng_max . PHP_EOL;
        // echo 'lat (min/max): ' . $lat_min . '/' . $lat_max;
        
        return [$lat_min,$lat_max,$lng_min,$lng_max];
    }


    
}


?>