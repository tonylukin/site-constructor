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

    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

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
