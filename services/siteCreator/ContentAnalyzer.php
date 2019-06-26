<?php

namespace app\services\siteCreator;

class ContentAnalyzer
{
    public function analyze(string $text): string
    {
        $text = $this->fixNonEnglishText($text);

        return $text;
    }

    private function fixNonEnglishText(string $text): string
    {
        $match = \preg_match('/^[^\x20-\x7E]$/', $text);
        $res = preg_replace("/[^[:alnum:][:space:]]/u", '', $text);
        return \preg_replace('/^[^\x20-\x7E]$/u', '', $text);
    }

    private function cleanFromLongWords(string $text): string
    {
        \preg_split('/(?=[A-Z])/', $text);
    }
}