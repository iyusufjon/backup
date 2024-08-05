<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Databases $model */

$this->title = Yii::t('app', 'Create Databases');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Databases'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="databases-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
