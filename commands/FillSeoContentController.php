<?php

namespace app\commands;

use app\services\siteCreator\ContentGenerator;

class FillSeoContentController extends Controller
{
    private const BATCH_SIZE = 100;

    /**
     * @var ContentGenerator
     */
    private $contentGenerator;

    /**
     * FillSeoContentController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param ContentGenerator $contentGenerator
     */
    public function __construct($id, $module, $config = [], ContentGenerator $contentGenerator)
    {
        $this->contentGenerator = $contentGenerator;
        parent::__construct($id, $module, $config);
    }

    /**
     * Add some faker text mixed with real page content
     * @throws \yii\db\Exception
     */
    public function actionIndex(): void
    {
        $size = self::BATCH_SIZE;
        $step = 0;

        do {
            $offset = $size * $step;
            $sql = <<<SQL
            SELECT id, title, keywords, description, content 
            FROM `page` 
            WHERE seo_content IS NULL 
            LIMIT {$size} OFFSET {$offset}
SQL;
            $data = \YII::$app->db->createCommand($sql)->queryAll();
            $sqlInsert = '';
            $sqlInsertParams = [];
            foreach ($data as $row) {
                $seoContent = $this->contentGenerator->generate(\implode(' ', [
                    $row['title'],
                    $row['keywords'],
                    $row['description'],
                    \substr($row['content'], 0, 10000),
                ]));
                $paramName = ":content_{$row['id']}";
                $sqlInsert .= <<<SQL
UPDATE `page` SET `seo_content` = {$paramName} WHERE id = {$row['id']};
SQL;
                $sqlInsertParams[$paramName] = $seoContent;
            }
            \Yii::$app->db->createCommand($sqlInsert, $sqlInsertParams)->execute();

            $this->writeLog('Successfuly updated pages: ' . \count($data));
            $step++;

            if (\count($data) < $size) {
                break;
            }

        } while (true);

        $this->writeLog("Finished. Batches count: {$step}");
    }
}
