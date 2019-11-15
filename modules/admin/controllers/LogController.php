<?php

namespace app\modules\admin\controllers;

class LogController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        if (\Yii::$app->request->isPost) {
            $pid = \Yii::$app->request->post('pidToKill');
            \exec("kill {$pid}");
        }

        \exec('tail -100 /var/log/create-site.log', $lines);
        $logPath = \implode(DIRECTORY_SEPARATOR, [
            \Yii::$app->runtimePath,
            'logs',
            'app.log'
        ]);
        $year = \date('Y');
        \exec("tail -1000 {$logPath} | grep \"^{$year}\-\"", $linesYiiLog);
        \exec("tail -1000 {$logPath} | grep \"No links found for\"", $linesYiiLogNoLinksFound);
        \exec('ps aux | grep create-site', $createSiteProcesses);
        $pids = [];
        foreach ($createSiteProcesses as $createSiteProcess) {
            \preg_match('/^root\s+(\d+)\s+/', $createSiteProcess, $matches);
            $pids[] = $matches[1];
        }

        return $this->render('creation-log-view', [
            'lines' => $lines,
            'linesYiiLog' => $linesYiiLog,
            'linesYiiLogNoLinksFound' => $linesYiiLogNoLinksFound,
            'createSiteProcesses' => $createSiteProcesses,
            'pids' => $pids,
        ]);
    }
}
