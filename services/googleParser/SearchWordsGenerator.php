<?php

namespace app\services\googleParser;

use Faker\Factory;

class SearchWordsGenerator
{
    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * SearchWordsGenerator constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * @param string $queryPart
     * @param int $count
     * @return array
     * @throws \Exception
     */
    public function generate(string $queryPart, int $count): array
    {
        $searchWords = [];
        foreach (\range(1, $count) as $item) {
            $searchWords[] = \sprintf($queryPart, $this->faker->realText(10, \random_int(1, 5)));
        }

        return $searchWords;
    }
}
