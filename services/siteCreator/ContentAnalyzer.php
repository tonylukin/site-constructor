<?php

namespace app\services\siteCreator;

class ContentAnalyzer
{
    /**
     * @param string $text
     * @return bool
     */
    public function checkContentIsEnglish(string $text): bool
    {
        if (!\preg_match_all('/[\x20-\x7E]/', $text, $matches)) {
            return false;
        }

        $textLength = \strlen($text);
        $englishTextLength = \strlen(\implode('', $matches[0]));

        return $englishTextLength / $textLength > 0.8;
    }

    /**
     * @param string $text
     * @return string
     */
    public function cleanFromLongWords(string $text): string
    {
        $wordsToBeReplaced = [];
        $wordsToReplace = [];
        if (\preg_match_all('/[A-Z]?[a-z]+([A-Z][a-z]+)+/', $text, $matches)) {
            foreach ($matches[0] as $camelCasedWord) {
                $wordsToBeReplaced[] = $camelCasedWord;
                $pieces = \preg_split('/(?=[A-Z])/', $camelCasedWord);
                $wordToReplace = \strtolower(\trim(\implode(' ', $pieces)));
                $wordsToReplace[] = $wordToReplace;
            }
        }
        return \str_replace($wordsToBeReplaced, $wordsToReplace, $text); // todo
    }
}