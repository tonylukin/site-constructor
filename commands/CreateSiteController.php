<?php

namespace app\commands;

use app\services\googleParser\SiteListGetter;
use app\services\siteCreator\CreatingProcessManager;
use app\services\siteCreator\Creator;
use app\services\siteCreator\CreatorConfig;
use app\services\siteCreator\Parser;
use yii\base\Module;
use yii\console\ExitCode;
use yii\helpers\Console;

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
            $this->writeLog('Process is already started');
            return ExitCode::OK;
        }

        $configs = $this->creatorConfig->getConfigs();
        if (empty($configs)) {
            $this->writeLog('Config is empty');
            return ExitCode::OK;
        }

        $this->creatingProcessManager->setProcessStarted();
        if ($urlCount !== null) {
            $this->siteListGetter->setSearchResultNumber($urlCount);
        }

        $this->writeLog('Process started with config count ' . \count($configs));
        foreach ($configs as $config) {
            try {
                $this->writeLog('Run config: ' . $config[CreatorConfig::DOMAIN] . ' : \'' . $config[CreatorConfig::SEARCH_QUERY] . '\'');
                $result = $this->creator->create($config[CreatorConfig::DOMAIN], $config[CreatorConfig::SEARCH_QUERY]);
                $this->writeLog('Finish config: ' . $config[CreatorConfig::DOMAIN] . ' : \'' . $config[CreatorConfig::SEARCH_QUERY] . '\'');

            } catch (\Throwable $e) {
                $this->creatingProcessManager->setProcessFinished();
                \Yii::error($e->getTraceAsString(), Parser::LOGGER_PREFIX);
                $this->writeLog("Error: {$e->getMessage()}");
                return ExitCode::UNSPECIFIED_ERROR;
            }

            if ($result) {
                $this->creatorConfig->removeConfig($config);
            }
            if ($this->creator->isPageCountLimitReached()) {
                break;
            }
        }

        $this->creatingProcessManager->setProcessFinished();
        $this->writeLog("New sites: {$this->creator->getNewSitesCount()}, new pages: {$this->creator->getNewPagesCount()}, existing pages: {$this->creator->getExistingPagesCount()}, new images: {$this->creator->getImagesSavedCount()}");
        return ExitCode::OK;
    }
}