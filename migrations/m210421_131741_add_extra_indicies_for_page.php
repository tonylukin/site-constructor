<?php

use yii\db\Migration;

/**
 * Class m210421_131741_add_extra_indicies_for_page
 */
class m210421_131741_add_extra_indicies_for_page extends Migration
{
    private const TABLE = 'page';
    private const INDEX = 'ix_site_id_publish_date';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex(self::INDEX, self::TABLE, ['site_id', 'publish_date']);
        $this->dropIndex('ix_page_site_id', self::TABLE);
        $this->dropIndex('ix_page_source_url', self::TABLE);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createIndex('ix_page_source_url', self::TABLE, 'source_url', true);
        $this->createIndex('ix_page_site_id', self::TABLE, 'site_id');
        $this->dropIndex(self::INDEX, self::TABLE);
    }
}
