<?php
declare(strict_types=1);

namespace app\modules\admin\controllers;

use app\services\Statistic\Report;

class StatisticController extends Controller
{
    /**
     * @var Report
     */
    private $report;

    public function __construct($id, $module, $config = [], Report $report)
    {
        $this->report = $report;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        return $this->report->get();
    }
}
