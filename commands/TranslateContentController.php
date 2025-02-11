<?php

namespace app\commands;

use app\helpers\TextSplitter;
use app\models\Page;
use app\services\siteCreator\Creator;
use app\services\Translations\TranslationInterface;
use yii\base\Module;
use yii\console\ExitCode;

class TranslateContentController extends Controller
{
    private const BATCH_SIZE = 100;
    private TranslationInterface $translation;

    public ?int $day = null;
    public ?int $month = null;
    public ?int $siteId = null;

    public function __construct(
        string $id,
        Module $module,
        array $config = [],
        TranslationInterface $translation
    ) {
        parent::__construct($id, $module, $config);
        $this->translation = $translation;
    }

    public function options($actionID): array
    {
        return array_merge(parent::options($actionID), [
            'day',
            'month',
            'siteId',
        ]);
    }

    public function actionIndex(): int
    {
        $i = 0;

        do {
            $pages = Page::find()->with('site');

            if ($this->day !== null) {
                $pages
                    ->andWhere('DAY(created_at) = :day')
                    ->addParams(['day' => $this->day])
                ;
            }
            if ($this->month !== null) {
                $pages
                    ->andWhere('MONTH(created_at) = :month')
                    ->addParams(['month' => $this->month])
                ;
            }

            if ($this->siteId !== null) {
                $pages
                    ->andWhere('site_id = :siteId')
                    ->addParams(['siteId' => $this->siteId])
                ;
            }

            $pages = $pages
                ->limit(self::BATCH_SIZE)
                ->offset(self::BATCH_SIZE * $i)
                ->orderBy('id DESC')
                ->all()
            ;
            foreach ($pages as $page) {
                if ($page->site->target_language === null) {
                    continue;
                }

                try {
                    $translatedTexts = [];
                    $chunksGenerator = TextSplitter::chunkBySize($page->content, $this->translation->maxTextLength());
                    foreach ($chunksGenerator as $contentSplitted) {
                        $translation = $this->translation->translate([$contentSplitted], Creator::CONTENT_SOURCE_LANGUAGE, $page->site->target_language);
                        if ($translation !== null) {
                            $translatedTexts[] = $translation[0];
                        }
                    }
                    if (!empty($translatedTexts)) {
                        $page->content = implode(' ', $translatedTexts);
                    } else {
                        $this->writeLog("Could not translate content for page #{$page->id}");
                        continue;
                    }

                    $translation = $this->translation->translate([$page->title], Creator::CONTENT_SOURCE_LANGUAGE, $page->site->target_language);
                    if ($translation !== null) {
                        $page->title = $translation[0];
                    } else {
                        $this->writeLog("Could not translate title for page #{$page->id}");
                    }
                    $translation = $this->translation->translate([$page->keywords], Creator::CONTENT_SOURCE_LANGUAGE, $page->site->target_language);
                    if ($translation !== null) {
                        $page->keywords = $translation[0];
                    } else {
                        $this->writeLog("Could not translate keywords for page #{$page->id}");
                    }
                    $translation = $this->translation->translate([$page->description], Creator::CONTENT_SOURCE_LANGUAGE, $page->site->target_language);
                    if ($translation !== null) {
                        $page->description = $translation[0];
                    } else {
                        $this->writeLog("Could not translate description for page #{$page->id}");
                    }

                    $page->save();

                } catch (\Throwable $e) {
                    $this->writeLog("Error: {$e->getMessage()}");
                    continue;
                }
            }
            $i++;

        } while (\count($pages) > 0);

        return ExitCode::OK;
    }
}
