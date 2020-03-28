<?php

namespace app\models\queries;

use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[\app\models\Statistic]].
 *
 * @see \app\models\Statistic
 */
class StatisticQuery extends \yii\db\ActiveQuery
{
    public function byHost()
    {
        return $this
            ->addSelect(new Expression('COUNT(*) AS count, GROUP_CONCAT(url) AS urls, GROUP_CONCAT(ip) AS ips'))
            ->groupBy('host')
        ;
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Statistic[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Statistic|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
