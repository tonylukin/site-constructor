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
            'title' => $this->string()->notNull(),
            'keywords' => $this->string(),
            'description' => $this->string(),
            'content' => $this->text()->notNull(),
            'url' => $this->string()->notNull(),
            'source_url' => $this->string()->notNull(),
            'site_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->createIndex('ix_page_site_id', 'page', 'site_id');
        $this->createIndex('ix_page_source_url', 'page', 'source_url', true);
        $this->createTable('site', [
            'id' => $this->primaryKey(),
            'search_word' => $this->string()->notNull(),
            'domain' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->createIndex('ix_site_domain', 'site', 'domain', true);
        $this->createIndex('ix_site_search_word', 'site', 'search_word');
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
