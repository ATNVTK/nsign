<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Dish */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dish-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?=
    $form->field($model, 'ingredients')->widget(Select2::class, [
        'data' =>\app\models\Ingredient::getIngredientList(),
        'options' => ['placeholder' => 'Выберите ингредиент', 'data-role' => 'ingredient'],
        'pluginOptions' => [
            'allowClear' => true,
            'language' => "ru",
            'multiple' => true,
        ],
        'pluginLoading' => false,
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
