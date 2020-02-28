<?php

namespace app\services\siteCreator;

use app\models\Page;
use Faker\Factory;

class ContentGenerator
{
    /**
     * @var \Faker\Generator
     */
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * @param string $inputContent
     * @return string
     */
    public function generate(string $inputContent): string
    {
        $fakeText = $this->faker->realText(2000, 5) . ' ' . $this->faker->text(2000);
        $words = \array_filter(\array_map('trim', \explode(' ', $inputContent . ' ' . $fakeText)));
        \shuffle($words);

        return \implode(' ', $words);
    }

    /**
     * @param Page $page
     * @return string
     */
    public function generateForPage(Page $page): string
    {
        return $this->generate(\implode(' ', [
            $page->title,
            $page->keywords,
            $page->description,
            $page->content,
        ]));
    }
}