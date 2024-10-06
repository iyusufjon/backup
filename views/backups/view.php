<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Backups $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Резервные копии'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="backups-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'database_id',
                'label' => Yii::t('app', 'Project name'),
                'value' => function($model) {
                    return $model->database ? $model->database->project_name : '';
                }
            ],
            [
                'attribute' => 'db_type_id',
                'value' => function($model) {
                    return $model->databaseType ? $model->databaseType->name : '';
                }
            ],
            'url:url',
            'datetime',
        ],
    ]) ?>

</div>
