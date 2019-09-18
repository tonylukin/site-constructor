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
 *
 * @property Site $site
 * @property Image[] $images
 */
class Page extends ActiveRecord
{
    private const CACHE_DURATION = 3600 * 24 * 7;

    /**
     * @var int
     */
    private $pageIndex = 0;

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
            [['created_at', 'publish_date'], 'safe'],
            [['title', 'keywords', 'description'], 'string', 'max' => 255],
            [['site_id'], 'exist', 'skipOnError' => true, 'targetClass' => Site::className(), 'targetAttribute' => ['site_id' => 'id']],
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
        $query = self::find()
            ->andWhere('id < :id', [':id' => $this->id])
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
        $query = self::find()
            ->andWhere('id > :id', [':id' => $this->id])
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

    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!$this->publish_date) {
            $days = \round($this->pageIndex / 3, 0, PHP_ROUND_HALF_DOWN) * \random_int(1, 2);
            $this->publish_date = (new \DateTime())->modify("+ {$days} days")->format('Y-m-d');
        }
        return true;
    }
}
