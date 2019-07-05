<?php

use yii\db\Migration;

/**
 * Class m190705_161526_add_publish_date
 */
class m190705_161526_add_publish_date extends Migration
{
    private const TABLE = 'page';
    private const COLUMN = 'publish_date';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, self::COLUMN, $this->date()->null());
        $this->createIndex('page_url_site_id', self::TABLE, ['url', 'site_id'], true);
        $this->createIndex('image_source', 'image', 'source', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, self::COLUMN);
        $this->dropIndex('page_url_site_id', self::TABLE);
        $this->dropIndex('image_source', 'image');
    }
}
