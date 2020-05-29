<?php

namespace app\models;

use app\models\Dish;
use app\models\Ingredient;
use yii\helpers\ArrayHelper;

/**
 * Модель формы task
 */
class DishForm extends Dish
{

    public $ingredients = [];
    public $names = [];


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(
                parent::rules(),
                [
                    [['ingredients'], 'safe'],
                ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['ingredients'] = \Yii::t('app', 'Ингредиенты');

        return $labels;
    }

    /**
     * Сохраняет форму
     * $this->addColumn('{{%task}}', 'category_id', $this->integer());
     * @param array $data параметры запроса
     *
     * @return bool результат сохранения
     * @throws \yii\db\Exception
     * @throws \yii\base\Exception
     */


    public function afterFind()
    {
        foreach ($this->dishIngredients as $ingredient) {
            $this->ingredients []= $ingredient->ingredient_id;
        }
    }

    public function saveData(array $data): bool
    {
        $transaction = $this->getDb()->beginTransaction();
        try {
            if ($this->load($data)) {
                if (!$this->save()) {
                    throw new \Exception('Cant safe task');
                }

                $oldIngredients = ArrayHelper::getColumn($this->dishIngredients, 'ingredient_id');
                if(is_array($this->ingredients)) {
                    $toCreate = array_diff($this->ingredients, $oldIngredients);
                    foreach ($toCreate as $ingredient){
                        $dishIngredient = new DishIngredient();
                        $dishIngredient->dish_id = $this->id;
                        $dishIngredient->ingredient_id = $ingredient;
                        $dishIngredient->save();
                    }
                    $toDelete = array_diff($oldIngredients,$this->ingredients);
                }
                else{
                    $toDelete = $oldIngredients;
                }
                foreach ($toDelete as $delete){
                    $dishIngredient = DishIngredient::findOne(['dish_id' => $this->id, 'ingredient_id' => $delete]);
                    $dishIngredient->delete();
                }
                $transaction->commit();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit;
            $transaction->rollBack();
            throw new $e;
        }

        return true;
    }

    public function test()
    {
        $ingredientTest = new Ingredient(['name' => 'первый']);
        $ingredientTest->save();
        $dishTest1 = new Dish(['name' => 'первое']);
        $dishTest1->save();
        $dishTest2 = new Dish(['name' => 'второе']);
        $dishTest2->save();
        $first = Ingredient::findOne(['name' => 'первый']);
        $firstDish = Dish::findOne(['name' => 'первое']);
        $secondDish = Dish::findOne(['name' => 'второе']);
        $dishIngredientTest1 = new DishIngredient(['dish_id' => $firstDish->id, 'ingredient_id' => $first->id]);
        $dishIngredientTest1->save();
        $dishIngredientTest2 = new DishIngredient(['dish_id' => $secondDish->id, 'ingredient_id' => $first->id]);
        $dishIngredientTest2->save();
        $dish = Dish::findOne(['name' => 'первое']);
        $dishes = Dish::find()
            ->joinWith('dishIngredients')
            ->where(['id' => $first->id])
            ->all();
    }





    /**
     * Возвращает имя класса для поиска сущности по словарю
     *
     * @return string имя класса сохраняемой модели
     */
    protected static function getClassNamespace(): string
    {
        return Task::class;
    }
}
