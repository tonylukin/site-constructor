<?php

declare(strict_types=1);

namespace app\services\Translations;

interface TranslationInterface
{
    /**
     * Max text length to be translated per one request
     */
    public function maxTextLength(): int;

    public function translate(array $text, string $sourceLanguage, string $targetLanguage): ?array;
}
