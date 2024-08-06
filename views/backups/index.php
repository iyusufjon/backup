<?php

use app\models\Backups;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\BackupsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Backups');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="backups-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Backups'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'database_id',
                'value' => function($model) {
                    return $model->database ? $model->database->name : '';
                }
            ],
            [
                'attribute' => 'db_type_id',
                'value' => function($model) {
                    return $model->databaseType ? $model->databaseType->name : '';
                }
            ],
            [
                'attribute' => 'url',
                'value' => function($model) {
                    return Html::a('Download Backup', ['backups/download', 'filename' => $model->url]);
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
