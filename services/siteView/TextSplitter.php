<?php

namespace app\services\siteView;

class TextSplitter
{
    /**
     * @param string $text
     * @return array
     */
    public static function split(string $text): array
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
