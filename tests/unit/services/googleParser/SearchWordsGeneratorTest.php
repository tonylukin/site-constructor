<?php

namespace tests\unit\services\googleParser;

use app\services\googleParser\SearchWordsGenerator;

class SearchWordsGeneratorTest extends \Codeception\Test\Unit
{
    public function testGenerate(): void
    {
        /** @var SearchWordsGenerator $searchWordsGenerator */
        $searchWordsGenerator = \Yii::$container->get(SearchWordsGenerator::class);
        $data = $searchWordsGenerator->generate('Zombie in Japan %s', 30);
        foreach ($data as $datum) {
            self::assertStringStartsWith('Zombie in Japan', $datum);
            self::assertNotContains(',', $datum);
            self::assertNotContains('"', $datum);
            self::assertNotContains('\'', $datum);
        }
    }
}
