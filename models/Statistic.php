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
 * @property string $additional_info
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
            [['created_at', 'additional_info'], 'safe'],
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

    public function afterFind()
    {
        if ($this->additional_info) {
            $this->additional_info = \json_decode($this->additional_info, true);
        }

        parent::afterFind();
    }

    public function beforeSave($insert)
    {
        $this->additional_info = \json_encode($this->additional_info);
        return parent::beforeSave($insert);
    }
}
