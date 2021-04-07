<?php

declare(strict_types=1);

namespace app\services\Translations;

use \Dejurin\GoogleTranslateForFree;

class GoogleTranslate implements TranslationInterface
{
    public function translate(array $text, string $sourceLanguage, string $targetLanguage): ?array
    {
        try {
            return GoogleTranslateForFree::translate($sourceLanguage, $targetLanguage, $text);
        } catch (\Throwable $e) {
            \Yii::error($e->getTrace());
            return null;
        }
    }

    public function maxTextLength(): int
    {
        return 4000;
    }
}
