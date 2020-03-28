<?php
declare(strict_types=1);

namespace app\services\Statistic;

use app\models\Statistic;

class VisitCounter
{
    private const MY_IPS = [
        '217.150.77.69',
        '213.138.208.25',
        '86.102.119.98',
    ];

    /**
     * @param string $host
     * @param string $url
     * @param string $ip
     */
    public function hit(string $host, string $url, string $ip): void
    {
        if (\in_array($ip, self::MY_IPS, true)) {
            return;
        }

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
