<?php

namespace app\commands;

use app\services\siteView\SiteMapGenerator;

class CreateSitemapController extends Controller
{
    /**
     * @var SiteMapGenerator
     */
    private $siteMapGenerator;

    /**
     * CreateSitemapController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param SiteMapGenerator $siteMapGenerator
     */
    public function __construct(
        $id,
        $module,
        $config = [],
        SiteMapGenerator $siteMapGenerator
    )
    {
        $this->siteMapGenerator = $siteMapGenerator;
        parent::__construct($id, $module, $config);
    }


    public function actionIndex(): void
    {
        $maps = $this->siteMapGenerator->generate();
        $this->writeLog('Maps generated: ' . \implode(', ', $maps));
    }
}
