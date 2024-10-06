<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Backups $model */

$this->title = Yii::t('app', 'Create Backups');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Резервные копии'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="backups-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
