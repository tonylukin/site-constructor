<?php

use yii\db\Migration;

/**
 * Class m200327_165549_add_statistic_table
 */
class m200327_165549_add_statistic_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('statistic', [
            'id' => $this->primaryKey(),
            'host' => $this->string(),
            'url' => $this->string(),
            'ip' => $this->string(),
            'created_at' => $this->dateTime()->defaultValue(new \yii\db\Expression('CURRENT_TIMESTAMP'))
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('statistic');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200327_165549_add_statistic_table cannot be reverted.\n";

        return false;
    }
    */
}
