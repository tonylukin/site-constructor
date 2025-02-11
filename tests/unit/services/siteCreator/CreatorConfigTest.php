<?php

namespace app\tests\unit\services\siteCreator;

use app\services\siteCreator\CreatorConfig;

class CreatorConfigTest extends \Codeception\Test\Unit
{
    private const FILENAME = 'site-creator.test.config';

    public function testConfig(): void
    {
        $filePath = \implode(DIRECTORY_SEPARATOR, [
            \Yii::$app->runtimePath,
            self::FILENAME
        ]);
        \file_put_contents($filePath, <<<TEXT
# Some shit here
 small   buildings 
big-buildings.loc,big buildings

 big-dicks.loc, big dicks 

TEXT
);

        /** @var CreatorConfig $creatorConfig */
        $creatorConfig = \Yii::$container->get(CreatorConfig::class);
        $configs = $creatorConfig->setFilename(self::FILENAME)->getConfigs();
        $this->assertCount(3, $configs);
        foreach ($configs as $i => $config) {
            if ($i === 0) {
                $this->assertEquals($config[CreatorConfig::DOMAIN], 'small-buildings.wowtoknow.com');
                $this->assertEquals($config[CreatorConfig::SEARCH_QUERY], 'small   buildings');
            }
            if ($i === 1) {
                $this->assertEquals($config[CreatorConfig::DOMAIN], 'big-buildings.loc');
                $this->assertEquals($config[CreatorConfig::SEARCH_QUERY], 'big buildings');
            }
            $creatorConfig->setFilename(self::FILENAME)->removeConfig($config);
        }
    }
}