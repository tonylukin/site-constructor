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
        $replaceCount = \substr_count($queryPart, '%s');

        $searchWords = [];
        foreach (\range(1, $count) as $item) {
            $words = \explode(' ', $this->faker->realText(50, \random_int(1, 5)));

            $replaceArguments = [];
            foreach (\range(1, $replaceCount) as $item2) {
                $replaceArguments[] = $words[\array_rand($words)];
            }
            $searchWords[] = \sprintf($queryPart, ...$replaceArguments);
        }

        return \array_unique($searchWords);
    }
}
