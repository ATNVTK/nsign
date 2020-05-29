<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ingredient".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $is_hidden
 *
 * @property DishIngredient[] $dishIngredients
 */
class Ingredient extends \yii\db\ActiveRecord
{

    const HIDDEN = 1;
    const NOT_HIDDEN = 0;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ingredient';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_hidden'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'is_hidden' => 'Скрытый ингредиент',
        ];
    }

    public static function getIngredientList(): array
    {
        $ingredients = Ingredient::find()
            ->where(['ingredient.is_hidden'=>0 ])
            ->indexBy('name')
            ->all();
        $ingredients = ArrayHelper::map($ingredients, 'id', 'name');
        return $ingredients;
    }


    public static function getIsHiddenLabels(): array
    {
        return [
            static::HIDDEN => 'Да',
            static::NOT_HIDDEN => 'Нет',
        ];
    }

    public function getIsHiddenLabel(): string
    {
        $labels = static::getIsHiddenLabels();

        return $labels[$this->is_hidden] ?? '-';
    }

    /**
     * Gets query for [[DishIngredients]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDishIngredients()
    {
        return $this->hasMany(DishIngredient::className(), ['ingredient_id' => 'id']);
    }
}
