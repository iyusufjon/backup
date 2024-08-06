<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Databases;

/**
 * DatabasesSearch represents the model behind the search form of `app\models\Databases`.
 */
class DatabasesSearch extends Databases
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'db_type_id'], 'integer'],
            [['name', 'host', 'password', 'port', 'db_host', 'db_password', 'db_port'], 'safe'],
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
        $query = Databases::find();

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
            'id' => $this->id,
            'db_type_id' => $this->db_type_id,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'host', $this->host])
            ->andFilterWhere(['ilike', 'password', $this->password])
            ->andFilterWhere(['ilike', 'port', $this->port])
            ->andFilterWhere(['ilike', 'db_host', $this->db_host])
            ->andFilterWhere(['ilike', 'db_password', $this->db_password])
            ->andFilterWhere(['ilike', 'db_port', $this->db_port]);

        return $dataProvider;
    }
}
