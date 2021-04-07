<?php

use yii\db\Migration;

/**
 * Class m210407_070435_add_target_language_for_site
 */
class m210407_070435_add_target_language_for_site extends Migration
{
    private const TABLE = 'site';
    private const COLUMN = 'target_language';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, self::COLUMN, $this->string(16)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, self::COLUMN);
    }
}
