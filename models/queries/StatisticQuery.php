<?php

namespace app\models\queries;

/**
 * This is the ActiveQuery class for [[\app\models\Statistic]].
 *
 * @see \app\models\Statistic
 */
class StatisticQuery extends \yii\db\ActiveQuery
{
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
