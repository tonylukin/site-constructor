<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "site_search_word_log".
 *
 * @property int $id
 * @property int $site_id
 * @property string $search_word
 * @property string $created_at
 *
 * @property Site $site
 */
class SiteSearchWordLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_search_word_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['site_id', 'search_word'], 'required'],
            [['site_id'], 'integer'],
            [['created_at'], 'safe'],
            [['search_word'], 'string', 'max' => 255],
            [['site_id'], 'exist', 'skipOnError' => true, 'targetClass' => Site::className(), 'targetAttribute' => ['site_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'site_id' => 'Site ID',
            'search_word' => 'Search Word',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(Site::class, ['id' => 'site_id']);
    }
}
