<?php

declare(strict_types=1);

namespace tests\unit\services\Translations;

use app\services\Translations\GoogleTranslate;

class GoogleTranslateTest extends \Codeception\Test\Unit
{
    /**
     * @var GoogleTranslate
     */
    private $googleTranslate;

    protected function setUp()
    {
        $this->googleTranslate = \Yii::$container->get(GoogleTranslate::class);
        parent::setUp();
    }

    public function testTranslate(): void
    {
        $translated = $this->googleTranslate->translate('Hello world', 'en', 'ru');
        self::assertSame('Привет мир', $translated);
    }
}
