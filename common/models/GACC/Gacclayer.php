<?php

namespace common\models\GACC;

use Yii;

/**
 * This is the model class for table "gacclayer".
 *
 * @property int $OGR_FID
 * @property string $SHAPE
 * @property string $objectid
 * @property string $fid_nation
 * @property string $unit_id
 * @property string $gacc_name
 * @property string $gacc_label
 * @property string $location
 * @property string $contact_ph
 * @property string $gacc_nwcg_
 * @property double $shape__are
 * @property double $shape__len
 */
class Gacclayer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gacclayer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['SHAPE'], 'required'],
            [['SHAPE'], 'string'],
            [['objectid', 'fid_nation', 'shape__are', 'shape__len'], 'number'],
            [['unit_id', 'gacc_name', 'gacc_label', 'location', 'contact_ph', 'gacc_nwcg_'], 'string', 'max' => 80],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'OGR_FID' => 'Ogr  Fid',
            'SHAPE' => 'Shape',
            'objectid' => 'Objectid',
            'fid_nation' => 'Fid Nation',
            'unit_id' => 'Unit ID',
            'gacc_name' => 'Gacc Name',
            'gacc_label' => 'Gacc Label',
            'location' => 'Location',
            'contact_ph' => 'Contact Ph',
            'gacc_nwcg_' => 'Gacc Nwcg',
            'shape__are' => 'Shape  Are',
            'shape__len' => 'Shape  Len',
        ];
    }
    
}
