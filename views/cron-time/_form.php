<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Databases;
/** @var yii\web\View $this */
/** @var app\models\CronTime $model */
/** @var yii\widgets\ActiveForm $form */

$databases = Databases::all();
$minutes = minutes();
$hours = hours();
$dayOfMonth = dayOfMonth();
$months = months();
$dayOfWeek = dayOfWeek();

if ($model->isNewRecord) {
    $model->minutes = '*';
    $model->hours = '*';
    $model->day_of_month = '*';
    $model->month = '*';
    $model->day_of_week = '*';
}
?>

<div class="cron-time-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-8">
                    <?= $form->field($model, 'database_id')->dropDownList($databases, ['prompt' => '---']) ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'active')->textInput() ?>
                </div>
            </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-2">
                    <?= $form->field($model, 'minutes')->dropDownList($minutes) ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'hours')->dropDownList($hours) ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'day_of_month')->dropDownList($dayOfMonth) ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'month')->dropDownList($months) ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'day_of_week')->dropDownList($dayOfWeek) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
