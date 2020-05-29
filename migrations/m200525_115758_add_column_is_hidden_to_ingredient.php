<?php

use yii\db\Migration;

/**
 * Class m200525_115758_add_column_is_hidden_to_ingredient
 */
class m200525_115758_add_column_is_hidden_to_ingredient extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%ingredient}}', 'is_hidden', $this->integer(3)->defaultValue(0)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200525_115758_add_column_is_hidden_to_ingredient cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200525_115758_add_column_is_hidden_to_ingredient cannot be reverted.\n";

        return false;
    }
    */
}
