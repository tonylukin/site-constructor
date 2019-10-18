<?php

namespace app\modules\admin\controllers;

use app\models\Page;
use app\models\Site;
use app\modules\admin\models\PagesFilterForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $pagesFilterForm = new PagesFilterForm();
        $pagesFilterForm->load(\Yii::$app->request->get());

        $dataProvider = new ActiveDataProvider([
            'query' => $pagesFilterForm->getQuery(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'pagesFilterForm' => $pagesFilterForm,
            'sitesOptions' => Site::getOptions(),
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
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Page();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param string $q
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionPagesList(string $q)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $sql = <<<SQL
SELECT id, title AS text FROM page WHERE title LIKE :q LIMIT 1000;
SQL;
        $data = \Yii::$app->db->createCommand($sql, ['q' => "%{$q}%"])->queryAll();

        return ['results' => \array_values($data)];
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Page
    {
        if (($model = Page::find()->with('pageLinks')->where(['id' => $id])->one()) !== null) {
            return $model->loadLinks();
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
