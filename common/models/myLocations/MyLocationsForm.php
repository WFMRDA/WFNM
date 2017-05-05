<?php
namespace common\models\myLocations;

use Yii;
use yii\base\Model;


class MyLocationsForm extends Model
{
    public $address;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['address', 'trim'],
            ['address', 'required'],
        ];
    }



}
