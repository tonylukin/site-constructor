<?php

namespace app\modules\admin\models;

use app\models\Page;
use yii\base\Model;

class PagesFilterForm extends Model
{
    /**
     * @var int
     */
    public $site_id;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['site_id'], 'safe'],
        ];
    }

    /**
     * @return \app\models\queries\PageQuery
     */
    public function getQuery(): \app\models\queries\PageQuery
    {
        if ($this->site_id === null) {
            return Page::find();
        }

        return Page::find()->where(['site_id' => $this->site_id]);
    }
}
