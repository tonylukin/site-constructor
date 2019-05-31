<?php

namespace tests\unit\services\siteCreator;

use app\services\siteCreator\Parser;

class ParserTest extends \Codeception\Test\Unit
{
    public function testParseSiteContent(): void
    {
        $parser = \Yii::$container->get(Parser::class);
        $content = $parser->parseSiteContent('https://www.creativebloq.com/architecture/famous-buildings-around-world-10121105');
        $this->assertNotNull($content);
    }
}