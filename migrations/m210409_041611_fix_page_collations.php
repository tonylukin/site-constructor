<?php

use yii\db\Migration;

/**
 * Class m210409_041611_fix_page_collations
 */
class m210409_041611_fix_page_collations extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('ALTER TABLE `page` MODIFY `title` VARCHAR(255) CHARSET utf8mb4 NOT NULL;');
        $this->execute('ALTER TABLE `page` MODIFY `keywords` VARCHAR(255) CHARSET utf8mb4 NULL;');
        $this->execute('ALTER TABLE `page` MODIFY `description` VARCHAR(255) CHARSET utf8mb4 NULL;');
        $this->execute('ALTER TABLE `page` MODIFY `content` TEXT CHARSET utf8mb4 NOT NULL;');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210409_041611_fix_page_collations cannot be reverted.\n";
    }
}
