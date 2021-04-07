<?php

declare(strict_types=1);

namespace tests\unit\helpers;

use app\helpers\TextSplitter;

class TextSplitterTest extends \Codeception\Test\Unit
{
    public function testChunkBySize(): void
    {
        $sampleText = <<<'TEXT'
Updated: 2013 Oct 16 @ 8:52am UTC-6 — added a break to the while-loop for when the remaining $string has not more spaces AND the length of $string is greater than the max-length ($max). This was causing an infinite loop because, in this situation, the strlen($string) will always > $max but $string but $string will never get any smaller. Sadly, found this out on a Live/Production site. DOH!
TEXT;
        $chunkSize = 50;

        $texts = TextSplitter::chunkBySize($sampleText, $chunkSize);
        $chunksCount = \count($texts);
        $chunksCountExpected = (int) ceil(\strlen($sampleText) / $chunkSize);
        if ($chunksCount > $chunksCountExpected) {
            $chunksCountExpected++;
        }
        self::assertSame($chunksCountExpected, $chunksCount);
        self::assertSame($sampleText, implode(' ', $texts));
    }
}
