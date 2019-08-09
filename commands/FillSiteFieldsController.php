<?php

namespace app\commands;

use app\models\Site;

class FillSiteFieldsController extends Controller
{
    public function actionIndex(): void
    {
        $sites = Site::find()
            ->alias('s')
            ->where([
                'OR',
                's.slug IS NULL',
                's.body_class IS NULL',
            ])
            ->all()
        ;
        $i = 0;
        foreach ($sites as $site) {
            if ($site->slug === null) {
                $site->setSlug();
            }
            if ($site->body_class === null) {
                $site->setBodyClass();
            }
            if ($site->save()) {
                $i++;
            } else {
                $this->writeLog('Could not save site model due to errors: ' . \implode(', ', $site->getErrorSummary(true)));
            }
        }

        $this->writeLog("Sites affected: {$i}");
    }
}