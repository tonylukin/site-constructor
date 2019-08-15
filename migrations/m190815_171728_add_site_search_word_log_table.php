<?php

use yii\db\Migration;

/**
 * Class m190815_171728_add_site_search_word_log_table
 */
class m190815_171728_add_site_search_word_log_table extends Migration
{
    private const TABLE = 'site_search_word_log';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'site_id' => $this->integer()->notNull(),
            'search_word' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->addForeignKey(
            'fk_site_search_word_log_site_id_site_id',
            self::TABLE,
            'site_id',
            'site',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
