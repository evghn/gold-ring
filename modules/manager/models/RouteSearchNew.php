<?php

namespace app\modules\manager\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Route;
use yii\db\Expression;

/**
 * RouteSearch represents the model behind the search form of `app\models\Route`.
 */
class RouteSearchNew extends Route
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'point_start_id', 'point_end_id', 'time_all', 'time_end', 'user_id', 'after_start', 'before_end'], 'integer'],
            [['date_start', 'time_start', 'created_at', 'updated_at'], 'safe'],
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
        $query = Route::find()
            ->with(['pointStart', 'pointEnd'])
            ->andWhere(['>=', 'date_start', new Expression('CURDATE()')])
            ;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10
            ],
            'sort' => [
                'defaultOrder' => [
                    'date_start' => SORT_ASC,
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
            'point_start_id' => $this->point_start_id,
            'point_end_id' => $this->point_end_id,
            'date_start' => $this->date_start,
            'time_start' => $this->time_start,
            'time_all' => $this->time_all,
            'time_end' => $this->time_end,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'after_start' => $this->after_start,
            'before_end' => $this->before_end,
        ]);

        return $dataProvider;
    }
}
