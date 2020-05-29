<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Ingredient;

/* @var $this yii\web\View */
/* @var $model app\models\DishSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dish-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?=
    $form->field($model, 'ingredients')->widget(Select2::class, [
        'data' =>\app\models\Ingredient::getIngredientList(),
        'options' => ['placeholder' => 'Выберите ингредиенты', 'data-role' => 'ingredient'],
        'showToggleAll' => false,
        'pluginOptions' => [
            'allowClear' => true,
            'language' => "ru",
            'multiple' => true,
            'maximumSelectionLength'=> 5,
        ],
        'pluginLoading' => false,
    ])->label('Ингредиенты');
    ?>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary','name' => 'to-update', 'value' => 'to-update',]) ?>
        <?= Html::resetButton('Сбросить', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
