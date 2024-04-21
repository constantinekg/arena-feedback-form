<?php

namespace app\controllers;

use app\models\Feedback;
use app\models\FeedbackQuery;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Ipdiapasons;

/**
 * FeedbackController implements the CRUD actions for Feedback model.
 */
class FeedbackController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['index', 'view', 'delete'],
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'delete'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Feedback models.
     * @return mixed
     */
    public function actionIndex()
    {
        $ipdiapasons = Ipdiapasons::find()->select(["CONCAT(ipaddr, '/', netmask) AS net", 'description'])->
        orderBy(['id' => SORT_DESC])->asArray()->all();
        $searchModel = new FeedbackQuery();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];
        $dataProvider->pagination = [
            'forcePageParam' => false,
            'pageSizeParam' => false,
            'pageSize' => 50 
        ];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'ipdiapasons' => $ipdiapasons,
        ]);
    }

    /**
     * Displays a single Feedback model.
     * @param string $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $ipdiapasons = Ipdiapasons::find()->select(["CONCAT(ipaddr, '/', netmask) AS net", 'description'])->
        orderBy(['id' => SORT_DESC])->asArray()->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'ipdiapasons' => $ipdiapasons,
        ]);
    }

    /**
     * Creates a new Feedback model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate()
    // {
    //     $model = new Feedback();

    //     if ($this->request->isPost) {
    //         if ($model->load($this->request->post()) && $model->save()) {
    //             return $this->redirect(['view', 'id' => $model->id]);
    //         }
    //     } else {
    //         $model->loadDefaultValues();
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Updates an existing Feedback model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }

    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Deletes an existing Feedback model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $recordfordelete = $this->findModel($id);
        $oggfile = \Yii::getAlias('@app/web/uploadedogg') . DIRECTORY_SEPARATOR . $recordfordelete->voicefile . '.wav';
        $successstatus = 0;
        if (file_exists($oggfile)) {
            if (unlink($oggfile)) {
                $successstatus = 0;
            }
            else {
                \Yii::$app->session->setFlash('danger', 'Невозможно удалить файл записи, удаление записи отклонено.');
                $successstatus = 1;
            }
        }
        if ($successstatus === 0) {
            if ($recordfordelete->delete()) {
                \Yii::$app->session->setFlash('success', 'Запись с id ' . $recordfordelete->id . ' удалена.');
                return $this->redirect(['index']);
            }
        }
        // $this->findModel($id)->delete();

        // return $this->redirect(['index']);
    }

    /**
     * Finds the Feedback model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id ID
     * @return Feedback the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Feedback::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
