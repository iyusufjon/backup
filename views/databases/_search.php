<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\DatabasesSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="databases-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'db_type_id') ?>

    <?= $form->field($model, 'host') ?>

    <?= $form->field($model, 'password') ?>

    <?php // echo $form->field($model, 'port') ?>

    <?php // echo $form->field($model, 'db_host') ?>

    <?php // echo $form->field($model, 'db_password') ?>

    <?php // echo $form->field($model, 'db_port') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
