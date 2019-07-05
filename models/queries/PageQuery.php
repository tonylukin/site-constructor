<?php

namespace app\models\queries;

use app\models\Page;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[\app\models\Page]].
 *
 * @see \app\models\Page
 */
class PageQuery extends ActiveQuery
{
    public function init()
    {
        $this->andWhere([
            '<=',
            'page.publish_date',
            new Expression('NOW()')
        ]);
        parent::init();
    }

    /**
     * @param string[] $sourceUrls
     * @return PageQuery
     */
    public function bySourceUrls(array $sourceUrls): self
    {
        return $this->andWhere([
            'source_url' => $sourceUrls,
        ]);
    }

    /**
     * @return PageQuery
     */
    public function byHost(): self
    {
        return $this
            ->innerJoinWith('site')
            ->andWhere([
                'site.domain' => \Yii::$app->request->hostName,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     * @return Page[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Page|array|null
     */
    public function one($db = null): ?Page
    {
        return parent::one($db);
    }
}
