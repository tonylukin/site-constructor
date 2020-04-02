<?php
declare(strict_types=1);

namespace app\modules\admin\controllers;

use app\models\Statistic;
use app\modules\admin\models\StatisticFilterForm;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class StatisticController extends Controller
{
    public function actionIndex(): string
    {
        $statisticFilterForm = new StatisticFilterForm();
        $statisticFilterForm->load(\Yii::$app->request->get());

        $dataProvider = new ActiveDataProvider([
            'query' => $statisticFilterForm->getQuery(),
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'statisticFilterForm' => $statisticFilterForm,
        ]);
    }

    /**
     * Displays a single Page model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Statistic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Statistic
    {
        if (($model = Statistic::find()->where(['id' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
