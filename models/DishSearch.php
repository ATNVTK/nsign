<?php

namespace app\models;

use app\models\Dish;
use Codeception\Module\Yii2;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * DishSearch represents the model behind the search form of `app\models\Dish`.
 */
class DishSearch extends Dish
{

    public $showAll;
    public $ingredients = [];
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'safe'],
            [['ingredients'], 'safe'],
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
        $query = Dish::find();

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

        if (!$this->showAll) {
            if (!$this->ingredients){
                $this->ingredients = [];
            }
            if ((count($this->ingredients) < 2) && (Yii::$app->request->getQueryParam('to-update'))) {
                Yii::$app->session->setFlash('success', 'Выберите больше ингредиентов');
            }
            $subQuery = Dish::find();
            //$ingredientQuery = Ingredient::find()->where(['ingredient.is_hidden'=>0 ]);
            $dishSubQuery = Dish::find()->select(['dish.id'])
                ->leftJoin('dish_ingredient', 'dish_ingredient.dish_id = dish.id')
                ->leftJoin('ingredient', 'dish_ingredient.ingredient_id=ingredient.id')
                ->where(['ingredient.is_hidden'=>1]);
            $subQuery
                ->select(['dish.id', 'dish.name','COUNT(dish_ingredient.id) as ingredient_count'])
                ->leftJoin('dish_ingredient', 'dish_ingredient.dish_id = dish.id')
                ->leftJoin('ingredient', 'dish_ingredient.ingredient_id=ingredient.id')
                ->where(['in', 'ingredient.id', count($this->ingredients) >= 2 ? $this->ingredients : []])
                ->andWhere(['not in', 'dish.id', $dishSubQuery])
                ->groupBy(['dish.id', 'dish.name'])
                ->having(['=', 'COUNT(dish_ingredient.id)', count($this->ingredients)])
                ->orderBy(['ingredient_count' => SORT_DESC]);
            if (!$subQuery->all()) {
                $query
                    ->select(['dish.id', 'dish.name', 'COUNT(dish_ingredient.id) as ingredient_count'])
                    ->leftJoin('dish_ingredient', 'dish_ingredient.dish_id = dish.id')
                    ->leftJoin('ingredient', 'dish_ingredient.ingredient_id=ingredient.id')
                    ->where(['in', 'ingredient.id', count($this->ingredients) >= 2 ? $this->ingredients : []])
                    ->andWhere(['not in', 'dish.id', $dishSubQuery])
                    ->groupBy(['dish.id', 'dish.name'])
                    ->having(['>', 'COUNT(dish_ingredient.id)', 1])
                    ->orderBy(['ingredient_count' => SORT_DESC]);
            }
            else{
                $dataProvider = new ActiveDataProvider([
                    'query' => $subQuery,
                ]);
            }

        }
        return $dataProvider;
    }
}
