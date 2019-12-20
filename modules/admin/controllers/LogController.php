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

            $startMysql = \Yii::$app->request->post('startMysql');
            if ($startMysql !== null) {
                \exec('service mysql start');
            }
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
            if (\preg_match('/^[\w\-]+\s+(\d+)\s+/', $createSiteProcess, $matches) === 1) {
                $pids[] = $matches[1];
            } else {
                $pids[] = 0;
            }
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
