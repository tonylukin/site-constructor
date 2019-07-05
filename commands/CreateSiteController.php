<?php

namespace app\commands;

use app\services\googleParser\SiteListGetter;
use app\services\siteCreator\Creator;
use app\services\siteCreator\CreatorConfig;
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
     * @var CreatorConfig
     */
    private $creatorConfig;

    /**
     * CreateSiteController constructor.
     * @param string $id
     * @param Module $module
     * @param array $config
     * @param Creator $creator
     * @param SiteListGetter $siteListGetter
     * @param CreatorConfig $creatorConfig
     */
    public function __construct(
        string $id,
        Module $module,
        array $config = [],
        Creator $creator,
        SiteListGetter $siteListGetter,
        CreatorConfig $creatorConfig
    )
    {
        $this->creator = $creator;
        $this->siteListGetter = $siteListGetter;
        $this->creatorConfig = $creatorConfig;
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

        foreach ($this->creatorConfig->getConfigs() as $config) {
            try {
                $this->creator->create($config[CreatorConfig::DOMAIN], $config[CreatorConfig::SEARCH_QUERY]);
            } catch (\Throwable $e) {
                \Yii::error($e->getMessage(), 'parser');
                $this->stdout($e->getMessage());
                return ExitCode::UNSPECIFIED_ERROR;
            }
            $this->creatorConfig->removeConfig($config);
        }

        $this->stdout("New sites: {$this->creator->getNewSitesCount()}, new pages: {$this->creator->getNewPagesCount()}, updated pages: {$this->creator->getUpdatedPagesCount()}, new images: {$this->creator->getImagesSavedCount()}");
        return ExitCode::OK;
    }
}