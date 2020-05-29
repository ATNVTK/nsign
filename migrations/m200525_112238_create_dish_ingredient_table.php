<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dish_ingredient}}`.
 */
class m200525_112238_create_dish_ingredient_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%dish_ingredient}}', [
            'id' => $this->primaryKey(),
            'ingredient_id' => $this->integer(11),
            'dish_id' => $this->integer(11),
        ], $tableOptions);
        $this->addForeignKey('fk_dish_ingredient_ingredient_id', 'dish_ingredient', 'ingredient_id', 'ingredient', 'id', 'RESTRICT', 'RESTRICT');
        $this->createIndex('idx_dish_ingredient_ingredient_id', 'dish_ingredient', 'ingredient_id');
        $this->addForeignKey('fk_dish_ingredient_dish_id', 'dish_ingredient', 'dish_id', 'dish', 'id', 'RESTRICT', 'RESTRICT');
        $this->createIndex('idx_dish_ingredient_dish_id', 'dish_ingredient', 'dish_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%dish_ingredient}}');
    }
}
