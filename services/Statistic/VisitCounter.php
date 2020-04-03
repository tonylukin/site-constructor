<?php
declare(strict_types=1);

namespace app\services\Statistic;

use app\models\Statistic;

class VisitCounter
{
    private const EXCLUDE_IPS = [
        '217.150.77.69',
        '213.138.208.25',
        '86.102.119.98',
    ];
    private const EXCLUDE_USER_AGENTS = [
        'SemrushBot',
        'Bytespider',
        'MJ12bot',
        'YandexBot',
    ];

    /**
     * @param string $host
     * @param string $url
     * @param string $ip
     * @param array $additionalInfo
     */
    public function hit(string $host, string $url, string $ip, array $additionalInfo = []): void
    {
        if (\in_array($ip, self::EXCLUDE_IPS, true)) {
            return;
        }

        $excludePattern = '/(' . \implode('|', self::EXCLUDE_USER_AGENTS) . ')/';
        if (\preg_match($excludePattern, \implode(' ', $additionalInfo))) {
            return;
        }

        $hit = new Statistic([
            'host' => $host,
            'url' => $url,
            'ip' => $ip,
            'additional_info' => $additionalInfo
        ]);
        if (!$hit->save()) {
            \Yii::error('Can not save hit: ' . \print_r($hit->attributes, true));
        }
    }
}
