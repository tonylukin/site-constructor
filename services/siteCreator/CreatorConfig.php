<?php

namespace app\services\siteCreator;

class CreatorConfig
{
    private const DEFAULT_FILENAME = 'site-creator.config';
    private const COMMENT_SYMBOL = '#';
    private const DEFAULT_2ND_LEVEL_DOMAIN = '.wowtoknow.com';

    public const RAW_LINE = 'rawLine';
    public const DOMAIN = 'domain';
    public const SEARCH_QUERY = 'searchQuery';

    /**
     * @var string
     */
    private $filename = self::DEFAULT_FILENAME;

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return \implode(DIRECTORY_SEPARATOR, [
            \Yii::$app->runtimePath,
            $this->filename,
        ]);
    }

    /**
     * @return array
     */
    public function getConfigs(): array
    {
        $filePath = $this->getFilePath();
        if (!file_exists($filePath)) {
            touch($filePath);
        }

        $lines = \file($filePath);
        if ($lines === false) {
            return [];
        }

        $output = [];
        foreach ($lines as $line) {
            if ($line[0] === self::COMMENT_SYMBOL) {
                continue;
            }

            $data = \array_map('trim', \explode(',', $line));
            if (\count($data) === 1) {
                $query = $data[0];
                $domain = \preg_replace('/\s+/', '-', $query) . self::DEFAULT_2ND_LEVEL_DOMAIN;
            } else {
                $domain = $data[0] ?? '';
                $query = $data[1] ?? '';
            }
            if (!$domain || !$query) {
                $this->removeConfig([self::RAW_LINE => $line]);
                continue;
            }

            $output[] = [
                self::RAW_LINE => $line,
                self::DOMAIN => $domain,
                self::SEARCH_QUERY => $query,
            ];
        }
        return $output;
    }

    /**
     * @param array $config
     */
    public function removeConfig(array $config): void
    {
        if (YII_DEBUG === true) {
            return;
        }

        $line = $config[self::RAW_LINE] ?? PHP_EOL;
        if ($line === PHP_EOL) {
            return;
        }

        $filePath = \implode(DIRECTORY_SEPARATOR, [
            \Yii::$app->runtimePath,
            $this->filename
        ]);
        $contents = \file_get_contents($filePath);
        $contents = \str_replace($line, '', $contents);
        \file_put_contents($filePath, $contents);
        \file_put_contents("{$filePath}.bak", $line, FILE_APPEND);
    }

    /**
     * @param string $filename
     * @return CreatorConfig
     */
    public function setFilename(string $filename): CreatorConfig
    {
        $this->filename = $filename;
        return $this;
    }
}