<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Feedback;

/**
 * FeedbackQuery represents the model behind the search form of `app\models\Feedback`.
 */

class FeedbackQuery extends Feedback
{

    // public $daterange;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'subject',], 'integer'],
            [['name', 'phone', 'email', 'body', 'ipaddr', 'voicefile', 'daterange', 'created_at', 'updated_at'], 'safe'],
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
        $query = Feedback::find();

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

        if(isset ($this->created_at)&&$this->created_at!=''){ //you dont need the if function if yourse sure you have a not null date
            $date_explode_created_at = explode(" - ",$this->created_at);
            $date_created_at1 = new \Datetime(trim($date_explode_created_at[0]));
            $date_created_at2 = new \Datetime(trim($date_explode_created_at[1]));
            $query->andFilterWhere(['between','created_at',$date_created_at1->format('U'), $date_created_at2->format('U')+86400]);
        }
        

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'subject' => $this->subject,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ]);

        if ($this->voicefile === '0') {  //возможно нужно будет 0 взять в кавычки -> '0'
            $query->andWhere(['IS', 'voicefile', NULL]); 
        } elseif ($this->voicefile == 1) {
                $query->andWhere(['IS NOT', 'voicefile', NULL]); 
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['like', 'ipaddr', $this->ipaddr]);

        return $dataProvider;
    }
}
