<?php

namespace app\commands;

use app\services\googleParser\SiteListGetter;
use app\services\siteCreator\CreatingProcessManager;
use app\services\siteCreator\Creator;
use app\services\siteCreator\CreatorConfig;
use app\services\siteCreator\Parser;
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
     * @var CreatingProcessManager
     */
    private $creatingProcessManager;

    /**
     * CreateSiteController constructor.
     * @param string $id
     * @param Module $module
     * @param array $config
     * @param Creator $creator
     * @param SiteListGetter $siteListGetter
     * @param CreatorConfig $creatorConfig
     * @param CreatingProcessManager $creatingProcessManager
     */
    public function __construct(
        string $id,
        Module $module,
        array $config = [],
        Creator $creator,
        SiteListGetter $siteListGetter,
        CreatorConfig $creatorConfig,
        CreatingProcessManager $creatingProcessManager
    )
    {
        $this->creator = $creator;
        $this->siteListGetter = $siteListGetter;
        $this->creatorConfig = $creatorConfig;
        $this->creatingProcessManager = $creatingProcessManager;

        parent::__construct($id, $module, $config);
    }

    /**
     * @param int|null $urlCount
     * @return int
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionIndex(int $urlCount = null): int
    {
        if ($this->creatingProcessManager->isProcessInProgress()) {
            $this->stdout('Process is already started');
            return ExitCode::OK;
        }

        $this->creatingProcessManager->setProcessStarted();
        if ($urlCount !== null) {
            $this->siteListGetter->setSearchResultNumber($urlCount);
        }

        foreach ($this->creatorConfig->getConfigs() as $config) {
            try {
                $this->creator->create($config[CreatorConfig::DOMAIN], $config[CreatorConfig::SEARCH_QUERY]);

            } catch (\Throwable $e) {
                $this->creatingProcessManager->setProcessFinished();
                \Yii::error($e->getMessage(), Parser::LOGGER_PREFIX);
                $this->stdout(\date('d.m.Y H:i') . " :: {$e->getMessage()}" . PHP_EOL);
                return ExitCode::UNSPECIFIED_ERROR;
            }
            $this->creatorConfig->removeConfig($config);
        }

        $this->creatingProcessManager->setProcessFinished();
        $this->stdout(\date('d.m.Y H:i') . " :: New sites: {$this->creator->getNewSitesCount()}, new pages: {$this->creator->getNewPagesCount()}, updated pages: {$this->creator->getUpdatedPagesCount()}, new images: {$this->creator->getImagesSavedCount()}" . PHP_EOL);
        return ExitCode::OK;
    }
}