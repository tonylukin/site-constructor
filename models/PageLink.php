<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "page_link".
 *
 * @property int $id
 * @property string $text
 * @property string $url
 * @property int $ref_page_id
 * @property int $page_id
 * @property string $created_at
 *
 * @property Page $page
 * @property Page $refPage
 */
class PageLink extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page_link';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ref_page_id', 'page_id'], 'integer'],
            [['page_id'], 'required'],
            [['created_at'], 'safe'],
            [['text', 'url'], 'string', 'max' => 255],
            [['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => Page::class, 'targetAttribute' => ['page_id' => 'id']],
            [['ref_page_id'], 'exist', 'skipOnError' => true, 'targetClass' => Page::class, 'targetAttribute' => ['ref_page_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'url' => 'Url',
            'ref_page_id' => 'Ref Page ID',
            'page_id' => 'Page ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::class, ['id' => 'page_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefPage()
    {
        return $this->hasOne(Page::class, ['id' => 'ref_page_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\queries\PageLinkQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\PageLinkQuery(static::class);
    }
}
