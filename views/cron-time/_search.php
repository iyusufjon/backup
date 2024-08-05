<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CronTimeSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cron-time-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'database_id') ?>

    <?= $form->field($model, 'minutes') ?>

    <?= $form->field($model, 'hours') ?>

    <?= $form->field($model, 'day_of_month') ?>

    <?php // echo $form->field($model, 'month') ?>

    <?php // echo $form->field($model, 'day_of_week') ?>

    <?php // echo $form->field($model, 'active') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
