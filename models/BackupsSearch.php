<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Backups;

/**
 * BackupsSearch represents the model behind the search form of `app\models\Backups`.
 */
class BackupsSearch extends Backups
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'database_id', 'db_type_id'], 'integer'],
            [['url', 'datetime'], 'safe'],
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
        $query = Backups::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'datetime' => SORT_DESC, // Sana bo'yicha kamayish tartibida sort qilish
                ]
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
            'database_id' => $this->database_id,
            'db_type_id' => $this->db_type_id,
            'datetime' => $this->datetime,
        ]);

        $query->andFilterWhere(['ilike', 'url', $this->url]);

        return $dataProvider;
    }
}
