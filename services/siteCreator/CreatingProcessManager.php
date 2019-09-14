<?php

namespace app\services\siteCreator;

class CreatingProcessManager
{
    private const FILE_HOURS_INTERVAL = 3;
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

        if (\time() - \filemtime($filePath) > (3600 * self::FILE_HOURS_INTERVAL)) {
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