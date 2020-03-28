<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "statistic".
 *
 * @property int $id
 * @property string $host
 * @property string $url
 * @property string $ip
 * @property string $created_at
 */
class Statistic extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'statistic';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['host', 'url', 'ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'host' => 'Host',
            'url' => 'Url',
            'ip' => 'Ip',
            'created_at' => 'Created At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \app\models\queries\StatisticQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\StatisticQuery(static::class);
    }
}
