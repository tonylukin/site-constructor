<?php

namespace app\services\siteCreator;

class CreatingProcessManager
{
    private const FILE_SECONDS_INTERVAL = 60 * 60 * 12;
    private const PROCESS_NAME = 'create-site';

    public function setProcessStarted(): void
    {
        $filePath = $this->getProcessFilename();
        if (!\touch($filePath)) {
            throw new \RuntimeException("Can not create file '{$filePath}'");
        }
    }

    public function setProcessFinished(): void
    {
        $filePath = $this->getProcessFilename();
        if (!\unlink($filePath)) {
            throw new \RuntimeException("Can not delete file '{$filePath}'");
        }
    }

    /**
     * @return bool
     */
    public function isProcessInProgress(): bool
    {
        $filePath = $this->getProcessFilename();
        $fileExists = \file_exists($filePath);
        if (!$fileExists) {
            return false;
        }

        if (\time() - \filemtime($filePath) > self::FILE_SECONDS_INTERVAL) {
            $this->setProcessFinished();
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    private function getProcessFilename(): string
    {
        return \implode(DIRECTORY_SEPARATOR, [
            \Yii::$app->runtimePath,
            self::PROCESS_NAME
        ]);
    }
}