<?php

namespace app\models;

use app\models\queries\SiteQuery;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "site".
 *
 * @property int $id
 * @property string $search_word
 * @property string $domain
 * @property string $created_at
 *
 * @property Page[] $pages
 */
class Site extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['search_word', 'domain'], 'unique'],
            [['search_word', 'domain'], 'required'],
            [['created_at'], 'safe'],
            [['search_word', 'domain'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'search_word' => 'Search Word',
            'domain' => 'Domain',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::class, ['site_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return SiteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SiteQuery(static::class);
    }
}
