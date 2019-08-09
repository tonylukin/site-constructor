<?php

use yii\db\Migration;

/**
 * Class m190809_155341_add_body_css_add_slug_to_site
 */
class m190809_155341_add_body_css_add_slug_to_site extends Migration
{
    private const TABLE = 'site';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'body_class', $this->string()->null()->after('domain'));
        $this->addColumn(self::TABLE, 'slug', $this->string()->null()->after('body_class'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'slug');
        $this->dropColumn(self::TABLE, 'body_class');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190809_155341_add_body_css_add_slug_to_site cannot be reverted.\n";

        return false;
    }
    */
}
