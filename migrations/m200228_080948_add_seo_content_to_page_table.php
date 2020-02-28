<?php

use yii\db\Migration;

/**
 * Class m200228_080948_add_seo_content_to_page_table
 */
class m200228_080948_add_seo_content_to_page_table extends Migration
{
    private const TABLE = 'page';
    private const COLUMN = 'seo_content';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, self::COLUMN, $this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, self::COLUMN);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200228_080948_add_seo_content_to_page_table cannot be reverted.\n";

        return false;
    }
    */
}
