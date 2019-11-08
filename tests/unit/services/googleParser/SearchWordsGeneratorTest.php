<?php

namespace app\tests\unit\services\googleParser;

use app\services\googleParser\SearchWordsGenerator;

class SearchWordsGeneratorTest extends \Codeception\Test\Unit
{
    public function testGenerate(): void
    {
        /** @var SearchWordsGenerator $searchWordsGenerator */
        $searchWordsGenerator = \Yii::$container->get(SearchWordsGenerator::class);
        $data = $searchWordsGenerator->generate('Zombie in Japan %s', 10);
        $this->assertCount(10, $data);
        foreach ($data as $datum) {
            $this->assertStringStartsWith('Zombie in Japan', $datum);
        }
    }
}
