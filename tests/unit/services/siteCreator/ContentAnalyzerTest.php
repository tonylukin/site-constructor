<?php

namespace tests\unit\services\siteCreator;

use app\services\siteCreator\ContentAnalyzer;

class ContentAnalyzerTest extends \Codeception\Test\Unit
{
    public function testAnalyze(): void
    {
        /** @var ContentAnalyzer $contentAnalyzer */
        $contentAnalyzer = \Yii::$container->get(ContentAnalyzer::class);
        $result = $contentAnalyzer->analyze('привет дядя федор');
        $this->assertEmpty($result);
    }
}