<?php

namespace app\modules\admin\controllers;

class LogController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        \exec('tail -100 /var/log/create-site.log', $lines);
        $logPath = \implode(DIRECTORY_SEPARATOR, [
            \Yii::$app->runtimePath,
            'logs',
            'app.log'
        ]);
        $year = \date('Y');
        \exec("tail -1000 {$logPath} | grep \"^{$year}\-\"", $linesYiiLog);
        \exec("tail -1000 {$logPath} | grep \"No links found for\"", $linesYiiLogNoLinksFound);

        return $this->render('creation-log-view', [
            'lines' => $lines,
            'linesYiiLog' => $linesYiiLog,
            'linesYiiLogNoLinksFound' => $linesYiiLogNoLinksFound,
        ]);
    }
}
