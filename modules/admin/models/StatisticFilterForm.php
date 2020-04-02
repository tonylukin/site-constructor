<?php

namespace app\modules\admin\models;

use app\models\Statistic;
use yii\base\Model;

class StatisticFilterForm extends Model
{
    /**
     * @var int
     */
    public $host;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['host'], 'safe'],
        ];
    }

    /**
     * @return \app\models\queries\StatisticQuery
     */
    public function getQuery(): \app\models\queries\StatisticQuery
    {
        if (!$this->host) {
            return Statistic::find();
        }

        return Statistic::find()->where(['host' => $this->host]);
    }
}
