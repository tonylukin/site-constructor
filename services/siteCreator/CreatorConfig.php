<?php

namespace app\services\siteCreator;

class CreatorConfig
{
    private const DEFAULT_FILENAME = 'site-creator.config';
    private const COMMENT_SYMBOL = '#';

    private const RAW_LINE = 'rawLine';
    public const DOMAIN = 'domain';
    public const SEARCH_QUERY = 'searchQuery';

    /**
     * @var string
     */
    private $filename = self::DEFAULT_FILENAME;

    /**
     * @return array
     */
    public function getConfigs(): array
    {
        $filePath = \implode(DIRECTORY_SEPARATOR, [
            \Yii::$app->runtimePath,
            $this->filename
        ]);
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
            $domain = $data[0] ?? '';
            $query = $data[1] ?? '';
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