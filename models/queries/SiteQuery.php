<?php

namespace app\models\queries;

use app\models\Site;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Site]].
 *
 * @see Site
 */
class SiteQuery extends ActiveQuery
{
    /**
     * @return $this
     */
    public function active(): self
    {
        return $this->andWhere('page.active = 1');
    }

    /**
     * @param string $searchWord
     * @param string $domain
     * @return SiteQuery
     */
    public function byWordOrDomain(string $searchWord, string $domain): self
    {
        return $this->andWhere([
            'OR',
            ['search_word' => $searchWord],
            ['domain' => $domain],
        ]);
    }

    /**
     * @param string $domain
     * @return SiteQuery
     */
    public function byDomain(string $domain): self
    {
        return $this->andWhere([
            'domain' => $domain
        ]);
    }

    /**
     * {@inheritdoc}
     * @return Site[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Site|array|null
     */
    public function one($db = null): ?Site
    {
        return parent::one($db);
    }
}
