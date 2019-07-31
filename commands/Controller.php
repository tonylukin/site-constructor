<?php

namespace app\commands;

use yii\helpers\Console;

class Controller extends \yii\console\Controller
{
    /**
     * @param string $message
     */
    protected function writeLog(string $message): void
    {
        $this->stdout(\date('d.m.Y H:i') . " :: {$message}" . PHP_EOL, Console::UNDERLINE);
    }
}