<?php

namespace tests\unit\services\googleParser;

use app\services\googleParser\SiteListGetter;

class SiteListGetterTest extends \Codeception\Test\Unit
{
    public function testGetSearchList(): void
    {
        $siteListGetter = \Yii::$container->get(SiteListGetter::class);
        $result = $siteListGetter->getSearchList('beautiful buildings');
        $this->assertNotEmpty($result);
    }
}