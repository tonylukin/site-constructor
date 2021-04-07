<?php

declare(strict_types=1);

namespace app\helpers;

class TextSplitter
{
    public static function chunkBySize(string $text, int $chunkSize): array
    {
        $chunks = [];

        while (\strlen($text) > $chunkSize) {
            // location of last space in the first $chunkSize characters
            $substring = substr($text, 0, $chunkSize);
            // pull from position 0 to $substring position
            $chunks[] = trim(substr($substring, 0, strrpos($substring, ' ')));
            // $string (haystack) now = haystack with out the first $substring characters
            $text = substr($text, strrpos($substring, ' '));
            // UPDATE (2013 Oct 16) - if remaining string has no spaces AND has a string length
            //  greater than $chunkSize, then this will loop infinitely!  So instead, just have to bail-out (boo!)
            if (\strlen($text) <= $chunkSize) {
                break;
            }
        }
        $chunks[] = trim($text); // final bits o' text

        return $chunks;
    }

    public static function splitByDot(string $text): array
    {
        $splitPosition = \strpos($text, '.', 200);
        if ($splitPosition === false) {
            return [$text];
        }

        $splitPosition++;
        return [
            \substr($text, 0, $splitPosition),
            \substr($text, $splitPosition),
        ];
    }
}
