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

    public ?int $month = null;

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
            'month',
        ]);
    }

    public function actionIndex(): int
    {
        $i = 0;

        do {
            $pages = Page::find()->with('site');
            if ($this->month === null) {
                $pages->andWhere('DATE(created_at) >= DATE_SUB(NOW(), INTERVAL 3 MONTH)');
            } else {
                $pages
                    ->andWhere('MONTH(created_at) = :month')
                    ->addParams(['month' => $this->month])
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
                    $contentSplitted = TextSplitter::chunkBySize($page->content, $this->translation->maxTextLength());
                    $translatedTexts = $this->translation->translate($contentSplitted, Creator::CONTENT_SOURCE_LANGUAGE, $page->site->target_language);
                    unset($contentSplitted);
                    if ($translatedTexts !== null) {
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
