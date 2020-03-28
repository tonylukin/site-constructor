<?php
declare(strict_types=1);

namespace app\services\Statistic;

use app\models\Statistic;

class Report
{
    public function get()
    {
        return Statistic::find()->byHost()->all();
    }
}
