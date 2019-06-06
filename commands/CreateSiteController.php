<?php

namespace app\commands;

use app\services\googleParser\SiteListGetter;
use app\services\siteCreator\Creator;
use yii\console\Controller;
use yii\console\ExitCode;

class CreateSiteController extends Controller
{
    /**
     * @param int|null $urlCount
     * @return int
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionIndex(int $urlCount = null): int
    {
        if ($urlCount !== null) {
            $siteListGetter = \Yii::$container->get(SiteListGetter::class);
            $siteListGetter->setSearchResultNumber($urlCount);
        }
        /** @var Creator $creator */
        $creator = \Yii::$container->get(Creator::class);
        $creator->create();

        $this->stdout("New sites: {$creator->getNewSitesCount()}, new pages: {$creator->getNewPagesCount()}");

        return ExitCode::OK;
    }
}