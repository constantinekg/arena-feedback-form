<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ipdiapasons;

/**
 * IpdiapasonsQuery represents the model behind the search form of `app\models\Ipdiapasons`.
 */
class IpdiapasonsQuery extends Ipdiapasons
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'netmask'], 'integer'],
            [['ipaddr', 'description'], 'safe'],
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
        $query = Ipdiapasons::find();

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
            'netmask' => $this->netmask,
        ]);

        $query->andFilterWhere(['like', 'ipaddr', $this->ipaddr])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
