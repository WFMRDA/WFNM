<?php
namespace common\models\helpers;

use Yii;
use yii\base\Security;

/**
 * Yii2 Helpers extended from Yii Html Helpers to include framework needed functions
 */
class YiiHelpers extends \yii\helpers\Html
{

    public static function sanatize($str,$replace = '-'){
        $str = preg_replace("/[^\w\s\-]/i", $replace, $str);
        return strtolower($str);
    }

    public static function getDatetime($time,$format,$formatTo){
        $date = \DateTime::createFromFormat(\DateTime::ISO8601, $time);
        return $date->format($formatTo);
    }

    public static function humanTiming ($time){
        $now = new \DateTime();
        $now = $now->setTimezone(new \DateTimeZone('UTC'));
        $now = $now->getTimestamp();

        $time = $now - $time; // to get the time since that moment
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
        }
        return '0 second\'s';

    }

    /**
    * Returns a Date string rounded up to precision clock interval
    *
    * @param DateTime $datetime
    * @param integer $precision
    * @return DateTime Object
    */
    public static function roundTime(\DateTime $datetime, $precision = 15) {
        // 1) Set number of seconds to 0 (by rounding up to the nearest minute if necessary)
        $second = (int) $datetime->format("s");
        if ($second > 30) {
            // Jumps to the next minute
            $datetime->add(new \DateInterval("PT".(60-$second)."S"));
        } elseif ($second > 0) {
            // Back to 0 seconds on current minute
            $datetime->sub(new \DateInterval("PT".$second."S"));
        }
        // 2) Get minute
        $minute = (int) $datetime->format("i");
        // 3) Convert modulo $precision
        if($minute !== 0){
            $exp = ceil($minute/$precision);
            $precision = $precision*$exp;
        }
        $minute = $minute % $precision;
        if ($minute > 0) {
            // 4) Count minutes to next $precision-multiple minutes
            $diff = $precision - $minute;
            // 5) Add the difference to the original date time
            $datetime->add(new \DateInterval("PT".$diff."M"));
        }
        return $datetime;
    }


    /**
    * Returns a GUIDv4 string
    * http://php.net/manual/en/function.com-create-guid.php
    * Uses the best cryptographically secure method
    * for all supported pltforms with fallback to an older,
    * less secure version.
    *
    * @param bool $trim
    * @return string
    */
    public static function GUIDv4 ($trim = true)
    {
        // Windows
        if (function_exists('com_create_guid') === true) {
            if ($trim === true)
                return trim(com_create_guid(), '{}');
            else
                return com_create_guid();
        }

        // OSX/Linux
        if (function_exists('openssl_random_pseudo_bytes') === true) {
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }

        // Fallback (PHP 4.2+)
        mt_srand((double)microtime() * 10000);
        $charid = strtolower(md5(uniqid(rand(), true)));
        $hyphen = chr(45);                  // "-"
        $lbrace = $trim ? "" : chr(123);    // "{"
        $rbrace = $trim ? "" : chr(125);    // "}"
        $guidv4 = $lbrace.
                  substr($charid,  0,  8).$hyphen.
                  substr($charid,  8,  4).$hyphen.
                  substr($charid, 12,  4).$hyphen.
                  substr($charid, 16,  4).$hyphen.
                  substr($charid, 20, 12).
                  $rbrace;
        return $guidv4;
    }


    public static function getStatesList(){

        $array = [
            ['abbr' => 'AL', 'name' =>'ALABAMA'],
            ['abbr' => 'AK', 'name' =>'ALASKA'],
            ['abbr' => 'AS', 'name' =>'AMERICAN SAMOA'],
            ['abbr' => 'AZ', 'name' =>'ARIZONA'],
            ['abbr' => 'AR', 'name' =>'ARKANSAS'],
            ['abbr' => 'CA', 'name' =>'CALIFORNIA'],
            ['abbr' => 'CO', 'name' =>'COLORADO'],
            ['abbr' => 'CT', 'name' =>'CONNECTICUT'],
            ['abbr' => 'DE', 'name' =>'DELAWARE'],
            ['abbr' => 'DC', 'name' =>'DISTRICT OF COLUMBIA'],
            ['abbr' => 'FM', 'name' =>'FEDERATED STATES OF MICRONESIA'],
            ['abbr' => 'FL', 'name' =>'FLORIDA'],
            ['abbr' => 'GA', 'name' =>'GEORGIA'],
            ['abbr' => 'GU', 'name' =>'GUAM GU'],
            ['abbr' => 'HI', 'name' =>'HAWAII'],
            ['abbr' => 'ID', 'name' =>'IDAHO'],
            ['abbr' => 'IL', 'name' =>'ILLINOIS'],
            ['abbr' => 'IN', 'name' =>'INDIANA'],
            ['abbr' => 'IA', 'name' =>'IOWA'],
            ['abbr' => 'KS', 'name' =>'KANSAS'],
            ['abbr' => 'KY', 'name' =>'KENTUCKY'],
            ['abbr' => 'LA', 'name' =>'LOUISIANA'],
            ['abbr' => 'ME', 'name' =>'MAINE'],
            ['abbr' => 'MH', 'name' =>'MARSHALL ISLANDS'],
            ['abbr' => 'MD', 'name' =>'MARYLAND'],
            ['abbr' => 'MA', 'name' =>'MASSACHUSETTS'],
            ['abbr' => 'MI', 'name' =>'MICHIGAN'],
            ['abbr' => 'MN', 'name' =>'MINNESOTA'],
            ['abbr' => 'MS', 'name' =>'MISSISSIPPI'],
            ['abbr' => 'MO', 'name' =>'MISSOURI'],
            ['abbr' => 'MT', 'name' =>'MONTANA'],
            ['abbr' => 'NE', 'name' =>'NEBRASKA'],
            ['abbr' => 'NV', 'name' =>'NEVADA'],
            ['abbr' => 'NH', 'name' =>'NEW HAMPSHIRE'],
            ['abbr' => 'NJ', 'name' =>'NEW JERSEY'],
            ['abbr' => 'NM', 'name' =>'NEW MEXICO'],
            ['abbr' => 'NY', 'name' =>'NEW YORK'],
            ['abbr' => 'NC', 'name' =>'NORTH CAROLINA'],
            ['abbr' => 'ND', 'name' =>'NORTH DAKOTA'],
            ['abbr' => 'MP', 'name' =>'NORTHERN MARIANA ISLANDS'],
            ['abbr' => 'OH', 'name' =>'OHIO'],
            ['abbr' => 'OK', 'name' =>'OKLAHOMA'],
            ['abbr' => 'OR', 'name' =>'OREGON'],
            ['abbr' => 'PW', 'name' =>'PALAU'],
            ['abbr' => 'PA', 'name' =>'PENNSYLVANIA'],
            ['abbr' => 'PR', 'name' =>'PUERTO RICO'],
            ['abbr' => 'RI', 'name' =>'RHODE ISLAND'],
            ['abbr' => 'SC', 'name' =>'SOUTH CAROLINA'],
            ['abbr' => 'SD', 'name' =>'SOUTH DAKOTA'],
            ['abbr' => 'TN', 'name' =>'TENNESSEE'],
            ['abbr' => 'TX', 'name' =>'TEXAS'],
            ['abbr' => 'UT', 'name' =>'UTAH'],
            ['abbr' => 'VT', 'name' =>'VERMONT'],
            ['abbr' => 'VI', 'name' =>'VIRGIN ISLANDS'],
            ['abbr' => 'VA', 'name' =>'VIRGINIA'],
            ['abbr' => 'WA', 'name' =>'WASHINGTON'],
            ['abbr' => 'WV', 'name' =>'WEST VIRGINIA'],
            ['abbr' => 'WI', 'name' =>'WISCONSIN'],
            ['abbr' => 'WY', 'name' =>'WYOMING'],
            ['abbr' => 'AE', 'name' =>'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST'],
            ['abbr' => 'AA', 'name' =>'ARMED FORCES AMERICA (EXCEPT CANADA)'],
            ['abbr' => 'AP', 'name' =>'ARMED FORCES PACIFIC'],
        ];

        return $array;
    }//End Get States List

    public static function getGenderList(){
        return [
            10 => 'Male',
            20 => 'Female',
        ];
    }

}
