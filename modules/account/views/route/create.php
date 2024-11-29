<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Route $model */

$this->title = 'Создание маршрута';
?>
<div class="application-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'startPoints' => $startPoints,
    ]) ?>

</div>
