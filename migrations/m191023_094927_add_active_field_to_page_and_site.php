<?php

use yii\db\Migration;

/**
 * Class m191023_094927_add_active_field_to_page_and_site
 */
class m191023_094927_add_active_field_to_page_and_site extends Migration
{
    private const TABLE_PAGE = 'page';
    private const TABLE_SITE = 'site';
    private const TABLE_ACTIVE_COLUMN = 'active';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_PAGE, self::TABLE_ACTIVE_COLUMN, $this->boolean()->notNull()->defaultValue(1));
        $this->addColumn(self::TABLE_SITE, self::TABLE_ACTIVE_COLUMN, $this->boolean()->notNull()->defaultValue(1));
        $this->createIndex('ix_active', self::TABLE_PAGE, self::TABLE_ACTIVE_COLUMN);
        $this->createIndex('ix_active', self::TABLE_SITE, self::TABLE_ACTIVE_COLUMN);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE_SITE, self::TABLE_ACTIVE_COLUMN);
        $this->dropColumn(self::TABLE_PAGE, self::TABLE_ACTIVE_COLUMN);
    }
}
