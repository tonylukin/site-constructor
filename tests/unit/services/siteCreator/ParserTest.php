<?php

namespace tests\unit\services\siteCreator;

use app\services\siteCreator\Parser;

class ParserTest extends \Codeception\Test\Unit
{
    /**
     * @var Parser
     */
    private $parser;

    protected function setUp()
    {
        $this->parser = \Yii::$container->get(Parser::class);
        return parent::setUp();
    }

    public function testParseSiteContent(): void
    {
        $this->parser->getImageParser()->setDomain('beautiful-buildings.loc');
        $content = $this
            ->parser
            ->parseSiteContent('https://www.creativebloq.com/architecture/famous-buildings-around-world-10121105')
        ;
        $this->assertNotNull($content);
        // todo images assertions and content checks to add
    }
}