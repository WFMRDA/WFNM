<?php

namespace common\models\devices;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\devices\DeviceList;

/**
 * DeviceListsSearch represents the model behind the search form of `common\models\devices\DeviceList`.
 */
class DeviceListsSearch extends DeviceList
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_id','token'], 'safe'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DeviceList::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'device_id' => $this->device_id,
            'token' => $this->token,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        return $dataProvider;
    }
}
