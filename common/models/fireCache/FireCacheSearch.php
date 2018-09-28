<?php

namespace common\models\fireCache;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\fireCache\FireCache;

/**
 * FireCacheSearch represents the model behind the search form of `common\models\fireCache\FireCache`.
 */
class FireCacheSearch extends FireCache
{
    public $q;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['irwinID', 'name','q'], 'safe'],
            [['updated_at', 'created_at'], 'integer'],
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
        $query = FireCache::find();

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
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'irwinID', $this->irwinID])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    public function searchRest($params){
        $query = FireCache::getDb()->cache(function ($db) {
            return FireCache::find();
        });
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params,'');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'name', $this->q]);
        return $dataProvider;
    }
}
