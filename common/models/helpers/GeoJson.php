<?php 

namespace common\models\helpers;

use Yii;
use yii\base\Model;

class GeoJson extends Model{

    public $geojson;
    
    public function init(){
        parent::init();
        // $crs = '{ "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } }';
        // $crs = json_decode('{ "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } }', true);
        $this->geojson['type'] = 'FeatureCollection';
        $this->geojson['crs'] = json_decode('{ "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } }', true);
    }
    
    public function addNode($id,$name,$lat,$lng,$options = []){
        
        $marker = [
            'type' => 'Feature',
            'properties' => [
                'Id' => $id,
                'Name' =>  $name,
                'Lat' => $lat,
                'Lng' => $lng,
            ],
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [ 
                    $lng,
                    $lat
                ]
            ]
        ];
        foreach ($options as $key => $value) {
            $marker['properties'][$key] = $value;
        }
        $this->geojson['features'][] =  $marker;
    }
    
    public function exportGeoJson(){  
        return $this->geojson;   
    }
    
    
}