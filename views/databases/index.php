<?php

use app\models\Databases;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\DatabasesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Databases');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="databases-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Databases'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'name',
            [
                'attribute' => 'db_type_id',
                'value' => function($model) {
                    return $model->databaseType ? $model->databaseType->name : '';
                }
            ],
            'host',
            'ssh_user',
            // 'password',
            //'port',
            //'db_host',
            //'db_password',
            //'db_port',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Databases $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
