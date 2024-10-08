<?php

use app\models\Backups;
use app\models\Databases;
use app\models\DatabaseType;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\BackupsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Резервные копии');
$this->params['breadcrumbs'][] = $this->title;

$databases = Databases::all();
$dbTypes = DatabaseType::all();
?>
<div class="backups-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'database_id',
                'label' => Yii::t('app', 'Project Name'),
                'value' => function($model) {
                    return $model->database ? $model->database->project_name : '';
                },
                'filter' => $databases
            ],
            [
                'attribute' => 'db_type_id',
                'value' => function($model) {
                    return $model->databaseType ? $model->databaseType->name : '';
                },
                'filter' => $dbTypes
            ],
            [
                'attribute' => 'url',
                'value' => function($model) {
                    return Html::a(Yii::t('app', 'Download Backup'), ['backups/download', 'filename' => $model->url]);
                },
                'format' => 'HTML'
            ],
            [
                'attribute' => 'file_size',
                'value' => function($model) {
                    return $model->file_size . ' MB';
                },
                'format' => 'HTML'
            ],
            // 'url:url',
            'datetime',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Backups $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
