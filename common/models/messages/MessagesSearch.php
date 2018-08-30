<?php

namespace common\models\messages;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\messages\Messages;

/**
 * MessagesSearch represents the model behind the search form about `common\models\messages\Messages`.
 */
class MessagesSearch extends Messages
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'type', 'sent_at','seen_at', 'send_tries', 'created_at'], 'integer'],
            [['subject', 'email', 'body', 'irwinID', 'data'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Messages::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeLimit' => [0, 100]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'sent_at' => $this->sent_at,
            'seen_at' => $this->seen_at,
            'irwinID' => $this->irwinID,
            'send_tries' => $this->send_tries,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email]);
            // ->andFilterWhere(['like', 'subject', $this->subject])
            // ->andFilterWhere(['like', 'body', $this->body])
            // ->andFilterWhere(['like', 'data', $this->data]);

        return $dataProvider;
    }
}
