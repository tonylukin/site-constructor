<?php

use yii\db\Migration;

/**
 * Class m190531_080531_init_tables
 */
class m190531_080531_init_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('page', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'keywords' => $this->string(),
            'description' => $this->string(),
            'content' => $this->text(),
            'site_id' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->createIndex('ix_page_site_id', 'page', 'site_id');
        $this->createTable('site', [
            'id' => $this->primaryKey(),
            'search_word' => $this->string(),
            'domain' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->createIndex('ix_site_domain', 'site', 'domain');
        $this->addForeignKey('fk_page_site_id_site_id', 'page', 'site_id', 'site', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('page');
        $this->dropTable('site');
    }
}
