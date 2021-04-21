<?php

use yii\db\Migration;

/**
 * Class m210421_130731_remove_active_index_from_page
 */
class m210421_130731_remove_active_index_from_page extends Migration
{
    private const TABLE = 'page';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropIndex('ix_active', self::TABLE);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createIndex('ix_active', self::TABLE, 'active');
    }
}
