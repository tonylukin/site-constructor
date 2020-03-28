<?php
declare(strict_types=1);

namespace app\services\Statistic;

use app\models\Statistic;

class VisitCounter
{
    /**
     * @param string $host
     * @param string $url
     * @param string $ip
     */
    public function hit(string $host, string $url, string $ip): void
    {
        $hit = new Statistic([
            'host' => $host,
            'url' => $url,
            'ip' => $ip,
        ]);
        if (!$hit->save()) {
            \Yii::error('Can not save hit: ' . \print_r($hit->attributes, true));
        }
    }
}
