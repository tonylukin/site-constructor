<?php

namespace app\models;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property string $source
 * @property string $original_url
 * @property int $page_id
 * @property string $created_at
 *
 * @property Page $page
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['source', 'original_url', 'page_id'], 'required'],
            [['page_id'], 'integer'],
            [['created_at'], 'safe'],
            [['source', 'original_url'], 'string', 'max' => 255],
            [['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => Page::class, 'targetAttribute' => ['page_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'source' => 'Source',
            'original_url' => 'Original Url',
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
     * {@inheritdoc}
     * @return \app\models\queries\ImageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\ImageQuery(static::class);
    }

    /**
     * @return string
     */
    public function getSourceUrl(): string
    {
        return '/' . $this->source;
    }
}
