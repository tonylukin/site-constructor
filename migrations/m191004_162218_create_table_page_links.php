<?php

use yii\db\Migration;

/**
 * Class m191004_162218_create_table_page_links
 */
class m191004_162218_create_table_page_links extends Migration
{
    private const TABLE = 'page_link';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'text' => $this->string()->null(),
            'url' => $this->string()->null(),
            'ref_page_id' => $this->integer()->null(),
            'page_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->addForeignKey(
            'fk_page_id_page_id',
            self::TABLE,
            'page_id',
            'page',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_ref_page_id_page_id',
            self::TABLE,
            'ref_page_id',
            'page',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->createIndex('ix_page_id', self::TABLE, 'page_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
