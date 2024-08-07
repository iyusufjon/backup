<?php

namespace app\controllers;

use Yii;
use app\models\Backups;
use app\models\BackupsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\controllers\AdminController;

/**
 * BackupsController implements the CRUD actions for Backups model.
 */
class BackupsController extends AdminController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
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
     * Lists all Backups models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BackupsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Backups model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Backups model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    // public function actionCreate()
    // {
    //     $model = new Backups();

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
     * Updates an existing Backups model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
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
     * Deletes an existing Backups model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model) {
            $fileUrl = $model->url;

            // Fayllar yo'lini aniqlash
            $tarFilePath = \Yii::getAlias('@app/data/' . $fileUrl);
            $sqlFilePath = str_replace('.tar', '.sql', $tarFilePath);

            // Fayllarni o'chirish
            $tarDeleted = false;
            $sqlDeleted = false;

            if (file_exists($tarFilePath)) {
                $tarDeleted = unlink($tarFilePath);
            }

            if (file_exists($sqlFilePath)) {
                $sqlDeleted = unlink($sqlFilePath);
            }

            // Xabarni setFlash orqali o'rnatish
            if ($tarDeleted && $sqlDeleted) {
                $model->delete();
                
                \Yii::$app->session->setFlash('success', 'Fayllar muvaffaqiyatli o\'chirildi.');
            } elseif ($tarDeleted || $sqlDeleted) {
                \Yii::$app->session->setFlash('warning', 'Ba\'zi fayllar muvaffaqiyatli o\'chirildi, lekin hammasi emas.');
            } else {
                \Yii::$app->session->setFlash('error', 'Fayllarni o\'chirishda xatolik yuz berdi yoki fayllar topilmadi.');
            }
        }

        return $this->redirect(['index']);
    }

    public function actionDownload($filename)
    {
        $filePath = Yii::getAlias('@app/data/') . $filename;

        if (file_exists($filePath)) {
            return Yii::$app->response->sendFile($filePath);
        } else {
            throw new \yii\web\NotFoundHttpException("The file does not exist.");
        }
    }

    /**
     * Finds the Backups model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Backups the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Backups::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
