<?php

namespace app\commands;

use app\services\googleParser\SiteListGetter;
use app\services\siteCreator\Creator;
use yii\base\Module;
use yii\console\Controller;
use yii\console\ExitCode;

class CreateSiteController extends Controller
{
    /**
     * @var
     */
    private $creator;

    /**
     * @var SiteListGetter
     */
    private $siteListGetter;

    /**
     * CreateSiteController constructor.
     * @param string $id
     * @param Module $module
     * @param array $config
     * @param Creator $creator
     * @param SiteListGetter $siteListGetter
     */
    public function __construct(
        string $id,
        Module $module,
        array $config = [],
        Creator $creator,
        SiteListGetter $siteListGetter
    )
    {
        $this->creator = $creator;
        $this->siteListGetter = $siteListGetter;
        parent::__construct($id, $module, $config);
    }

    /**
     * @param int|null $urlCount
     * @return int
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionIndex(int $urlCount = null): int
    {
        if ($urlCount !== null) {
            $this->siteListGetter->setSearchResultNumber($urlCount);
        }

        $this->creator->create();

        $this->stdout("New sites: {$this->creator->getNewSitesCount()}, new pages: {$this->creator->getNewPagesCount()}, updated pages: {$this->creator->getUpdatedPagesCount()}, new images: {$this->creator->getImagesSavedCount()}");
        return ExitCode::OK;
    }
}