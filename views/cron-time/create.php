<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CronTime $model */

$this->title = Yii::t('app', 'Create Cron Time');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cron Times'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cron-time-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
