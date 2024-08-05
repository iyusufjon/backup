<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\DatabaseType $model */

$this->title = Yii::t('app', 'Create Database Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Database Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="database-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
