<?php

namespace app\models;

use app\models\queries\PageQuery;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "page".
 *
 * @property int $id
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $content
 * @property string $url
 * @property string $source_url
 * @property int $site_id
 * @property string $created_at
 * @property string $publish_date
 * @property int $active
 *
 * @property Site $site
 * @property Image[] $images
 * @property PageLink[] $pageLinks
 */
class Page extends ActiveRecord
{
    private const CACHE_DURATION = 3600 * 24 * 7;

    /**
     * @var int
     */
    private $pageIndex = 0;

    /**
     * @var array
     */
    public $links = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content', 'url', 'source_url', 'site_id'], 'required'],
            [['source_url'], 'unique'],
            [['content'], 'string'],
            [['site_id'], 'integer'],
            [['created_at', 'publish_date', 'links'], 'safe'],
            [['title', 'keywords', 'description'], 'string', 'max' => 255],
            [['site_id'], 'exist', 'skipOnError' => true, 'targetClass' => Site::class, 'targetAttribute' => ['site_id' => 'id']],
            ['active', 'in', 'range' => [0, 1]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'content' => 'Content',
            'site_id' => 'Site ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(Site::class, ['id' => 'site_id']);
    }

    /**
     * {@inheritdoc}
     * @return PageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PageQuery(static::class);
    }

    /**
     * @return ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::class, [
           'page_id' => 'id'
        ]);
    }

    /**
     * @return Page|null
     */
    public function getPrevPage(): ?self
    {
        $site = Site::getCurrentSite();
        if ($site === null) {
            return  null;
        }

        $query = self::find()
            ->andWhere('id < :id', [':id' => $this->id])
            ->andWhere('publish_date <= NOW()')
            ->andWhere(['site_id' => $site->id])
            ->orderBy('id DESC')
            ->limit(1)
        ;
        if (!YII_DEBUG) {
            $query->cache(self::CACHE_DURATION);
        }
        return $query->one();
    }

    /**
     * @return Page|null
     */
    public function getNextPage(): ?self
    {
        $site = Site::getCurrentSite();
        if ($site === null) {
            return  null;
        }

        $query = self::find()
            ->andWhere('id > :id', [':id' => $this->id])
            ->andWhere('publish_date <= NOW()')
            ->andWhere(['site_id' => $site->id])
            ->limit(1)
        ;
        if (!YII_DEBUG) {
            $query->cache(self::CACHE_DURATION);
        }
        return $query->one();
    }

    /**
     * @param int $pageIndex
     * @return Page
     */
    public function setPageIndex(int $pageIndex): Page
    {
        $this->pageIndex = $pageIndex;
        return $this;
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \Exception
     */
    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!$this->publish_date) {
            $days = \round($this->pageIndex / 3, 0, PHP_ROUND_HALF_DOWN) * \random_int(1, 2);
            $this->publish_date = (new \DateTime())
                ->modify("+ {$days} days")
                ->format('Y-m-d')
            ;
        }

        return true;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (\is_array($this->links)) {
            foreach ($this->links as $link) {
                if (\is_numeric($link)) {
                    if ($this->isNewRecord
                        || PageLink::findOne(['page_id' => $this->id, 'ref_page_id' => $link]) === null) {
                        $pageLink = new PageLink();
                        $pageLink->page_id = $this->id;
                        $pageLink->ref_page_id = $link;
                        $pageLink->save();
                    }
                } elseif ($this->isNewRecord
                    || PageLink::findOne(['page_id' => $this->id, 'url' => $link]) === null) {
                    $pageLink = new PageLink();
                    $pageLink->page_id = $this->id;
                    $pageLink->url = $link;
                    $pageLink->save();
                }
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return ActiveQuery
     */
    public function getPageLinks(): ActiveQuery
    {
        return $this->hasMany(PageLink::class, ['page_id' => 'id']);
    }

    /**
     * Load each page by it's link. Only used for admin panel
     * @return self
     */
    public function loadLinks(): self
    {
        $this->links = [];
        foreach ($this->pageLinks as $pageLink) {
            if ($pageLink->ref_page_id !== null) {
                $this->links[] = $pageLink->ref_page_id;
            } else {
                $this->links[] = $pageLink->url;
            }
        }
        return $this;
    }

    /**
     * @param int|null $siteId
     * @return string|null
     * @throws \yii\db\Exception
     */
    public static function getLastPublishDate(?int $siteId): ?string
    {
        if ($siteId === null) {
            return null;
        }

        $sql = <<<SQL
SELECT MAX(publish_date) FROM page
GROUP BY site_id
HAVING site_id = :siteId
SQL;
        $date = \Yii::$app->db->createCommand($sql, ['siteId' => $siteId])->queryScalar();
        return $date === false ? null : $date;
    }
}
