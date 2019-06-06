<?php

namespace app\models\queries;

use app\models\Page;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\models\Page]].
 *
 * @see \app\models\Page
 */
class PageQuery extends ActiveQuery
{
    /**
     * @param string $sourceUrl
     * @return PageQuery
     */
    public function bySourceUrl(string $sourceUrl): self
    {
        return $this->andWhere([
            'source_url' => $sourceUrl,
        ]);
    }

    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

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
