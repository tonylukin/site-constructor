<?php

use yii\db\Migration;

/**
 * Class m200401_162427_add_additional_fields_to_statistic
 */
class m200401_162427_add_additional_fields_to_statistic extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('statistic', 'additional_info', $this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('statistic', 'additional_info');
    }
}
