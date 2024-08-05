<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CronTime;

/**
 * CronTimeSearch represents the model behind the search form of `app\models\CronTime`.
 */
class CronTimeSearch extends CronTime
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'database_id', 'active'], 'integer'],
            [['minutes', 'hours', 'day_of_month', 'month', 'day_of_week'], 'safe'],
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
        $query = CronTime::find();

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
            'database_id' => $this->database_id,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['ilike', 'minutes', $this->minutes])
            ->andFilterWhere(['ilike', 'hours', $this->hours])
            ->andFilterWhere(['ilike', 'day_of_month', $this->day_of_month])
            ->andFilterWhere(['ilike', 'month', $this->month])
            ->andFilterWhere(['ilike', 'day_of_week', $this->day_of_week]);

        return $dataProvider;
    }
}
