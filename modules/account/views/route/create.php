<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Route $model */

$this->title = 'Создание маршрута';
?>
<div class="application-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,        
        'dataProvider' => $dataProvider,
    ]) ?>

</div>
