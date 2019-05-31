<?php

namespace tests\unit\services\siteCreator;

use app\services\googleParser\SiteListGetter;
use app\services\siteCreator\Creator;

class CreatorTest extends \Codeception\Test\Unit
{
    public function testCreate(): void
    {
        /** @var SiteListGetter $siteListGetter */
        $siteListGetter = \Yii::$container->get(SiteListGetter::class);
        $siteListGetter->setSearchResultNumber(10);
        /** @var Creator $creator */
        $creator = \Yii::$container->get(Creator::class);
        $creator->create();
    }
}
