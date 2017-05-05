<?php

namespace common\models\user;

use Yii;
use common\models\user\User;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
/**
 * This is the model class for table "userSettings".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $key
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class UserSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'userSettings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'key', 'data'], 'required'],
            [['user_id', 'key', 'created_at', 'updated_at'], 'integer'],
            [['data'], 'string', 'max' => 255],
            [['user_id', 'key'], 'unique', 'targetAttribute' => ['user_id', 'key'], 'message' => 'The combination of User ID and Key has already been taken.'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'key' => 'Key',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    protected $settings = [
        'toggleBtn' => 10,
        'mapLayers' => 20,
    ];

    public function getSettingId($key){
        return (isset($this->settings[$key]))? $this->settings[$key] : null;
    }

    protected function processMapLayers($params){

        // Yii::trace($params,'dev');
        $data = [];
        foreach ($params as $key => $value) {
            if($value['name'] == 'fireSize[]'){
                $data['fireSize'][] = $value['value'];
            }else if($value['name'] == 'fireClass[]'){
                $data['fireClass'][] = $value['value'];
            }else if($value['name'] == 'addtlLayers[]'){
                $data['addtlLayers'][] = $value['value'];
            }
        }
        // Yii::trace($data,'dev');
        return Json::encode($data);
    }
    public function findSettings($params){
        $key = $params['type'];
        $val = $params['val'];
        if(isset($this->settings[$key])){
            switch (true) {
                case $key == 'toggleBtn':
                    $data = $val;
                    break;
                case $key == 'mapLayers':
                    $data = $this->processMapLayers($val);
                    break;
            }
            //See if User has setting
            $model = self::find()->andWhere(['user_id'=>Yii::$app->user->identity->id,'key'=>$this->settings[$key]])->one();
            if($model == null){
                $setting = Yii::createObject([
                    'class'=> self::className(),
                    'user_id'=>Yii::$app->user->identity->id,
                    'key' => $this->settings[$key],
                    'data'=> $data,
                ]);
                $response = $setting->save();
            }else{
                $response = $model->updateAttributes(['data'=>$data]);
            }
            // Yii::trace($val,'dev');
            // Yii::trace($data,'dev');
        }else{
            $response = false;
        }
        return $response;
    }

/*
    public function getKey($key){
        $keys = [
            self::TOOGLEBTN => 'toggle-btn'
        ];

        return ArrayHelper::getValue($keys,$key,null);

    }
*/
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
