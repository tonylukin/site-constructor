<?php

namespace tests\unit\services\siteCreator;

use app\services\siteCreator\Parser;

class ParserTest extends \Codeception\Test\Unit
{
    public function testParseSiteContent(): void
    {
        /** @var Parser $parser */
        $parser = \Yii::$container->get(Parser::class);
        $content = $parser->parseSiteContent('https://justrichest.com/beautiful-buildings-world/');
        $this->assertNotNull($content);
        // todo images assertions and content checks to add
    }
}