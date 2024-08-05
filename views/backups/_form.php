<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Backups $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="backups-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'database_id')->textInput() ?>

    <?= $form->field($model, 'db_type_id')->textInput() ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'datetime')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
