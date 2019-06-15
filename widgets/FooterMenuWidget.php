<?php

namespace app\widgets;

use app\services\siteView\NavigationLinksGetter;

class FooterMenuWidget extends \yii\bootstrap\Widget
{
    private const COLUMN_COUNT = 6;
    private const COLUMN_SIZE = 8;

    /**
     * @var NavigationLinksGetter
     */
    private $navigationLinksGetter;

    /**
     * FooterMenuWidget constructor.
     * @param array $config
     * @param NavigationLinksGetter $navigationLinksGetter
     */
    public function __construct($config = [], NavigationLinksGetter $navigationLinksGetter)
    {
        parent::__construct($config);
        $this->navigationLinksGetter = $navigationLinksGetter;
    }

    public function run(): string
    {
        $links = $this->navigationLinksGetter->get(self::COLUMN_COUNT * self::COLUMN_SIZE);
        $links = \array_chunk($links, self::COLUMN_SIZE);

        return $this->render('@views/widgets/footer-menu', [
            'links' => $links
        ]);
    }
}