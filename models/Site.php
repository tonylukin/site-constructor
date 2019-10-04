<?php

namespace app\models;

use app\models\queries\PageQuery;
use app\models\queries\SiteQuery;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "site".
 *
 * @property int $id
 * @property string $search_word
 * @property string $domain
 * @property string $body_class
 * @property string $slug
 * @property string $created_at
 *
 * @property Page[] $pages
 * @property SiteSearchWordLog[] $siteSearchWordLogs
 */
class Site extends ActiveRecord
{
    private const CACHE_DURATION = 3600 * 24 * 30; // 30 days
    private const BODY_CLASSES = [
        'blue',
        'red',
        'yellow',
        'white',
        'black',
        'brown',
        'green',
        'silver',
        'gold',
        'fired',
        'bread',
        'crew',
        'lighten',
        'aired',
    ];
    private const SLUGS = [
        'Just Another {title} Site',
        'This is all about {title}',
        'Make us know about {title}',
        'Wow! You need to know about {title}',
        'What do you always wanted to know about {title}?',
        '{title} and all about it',
        'What to learn about {title}?',
        'Why we did not speak about {title}',
        'Forget all you know about {title}',
        'All interesting about {title} here',
        'Most things you hear are {title}',
        'We all live with {title}',
    ];

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
            [['domain'], 'filter', 'filter' => 'trim'],
            [['created_at', 'body_class', 'slug'], 'safe'],
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
     * @return PageQuery
     */
    public function getPages(): PageQuery
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

    /**
     * @return Site|null
     */
    public static function getCurrentSite(): ?self
    {
        $site = self::find()->byDomain(\Yii::$app->request->hostName);

        if (!YII_DEBUG) {
            $site->cache(self::CACHE_DURATION);
        }
        return $site->one();
    }

    public function setSlug(): void
    {
        $this->slug = self::SLUGS[\array_rand(self::SLUGS)];
        $this->slug = \str_replace('{title}', $this->search_word, $this->slug);
    }

    public function setBodyClass(): void
    {
        $this->body_class = self::BODY_CLASSES[\array_rand(self::BODY_CLASSES)];
    }

    /**
     * @return ActiveQuery
     */
    public function getSiteSearchWordLogs(): ActiveQuery
    {
        return $this->hasMany(SiteSearchWordLog::class, ['site_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getBodyClasses(): array
    {
        return \array_combine(self::BODY_CLASSES, self::BODY_CLASSES);
    }

    /**
     * @return array
     */
    public static function getOptions(): array
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'domain');
    }
}
