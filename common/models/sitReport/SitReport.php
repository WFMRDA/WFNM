<?php

namespace common\models\sitReport;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sitReport".
 *
 * @property string $id
 * @property integer $label
 * @property string $val
 * @property integer $created_at
 * @property integer $updated_at
 */
class SitReport extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        // use the "vulcandDb" application component
        return \Yii::$app->vulcandDb;  
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sitReport';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'label', 'val'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['id', 'val','label'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Label',
            'val' => 'Val',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getNeedle(){
        $array = [
            'IA' => 'Initial activity:',
            'NLI' => 'New large incidents:',
            'LFC' => 'Large fires contained:',
            'ULF' => 'Uncontained large fires:',
            'ACTC' => 'Area Command Teams Committed:',
            'NIMO' => 'NIMOs committed:',
            'IMTS-I' => 'Type 1 IMTs committed:',
            'IMTS-II' => 'Type 2 IMTs committed:',
            'IAACT' => 'Initial attack activity:'
            ];
        return $array;
    }

    
}
