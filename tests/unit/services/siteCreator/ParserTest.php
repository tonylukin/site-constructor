<?php

namespace tests\unit\services\siteCreator;

use app\services\siteCreator\Parser;

class ParserTest extends \Codeception\Test\Unit
{
    private const URL = 'https://www.aldergrovestar.com/opinion/letter-save-buildings-with-real-historical-value-in-fort-langley/';

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
            ->parseSiteContent(self::URL)
        ;
        $this->assertNotNull($content);
        // todo images assertions and content checks to add
    }
}