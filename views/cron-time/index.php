<?php

use app\models\CronTime;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\CronTimeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Cron Times');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cron-time-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Cron Time'), ['create'], ['class' => 'btn btn-success']) ?>
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
                    return $model->database->project_name ?? '';
                }
            ],
            'minutes',
            'hours',
            'day_of_month',
            'month',
            'day_of_week',
            'active',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, CronTime $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
