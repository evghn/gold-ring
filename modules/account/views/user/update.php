<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\UserInfo $model */

$this->title = 'Редактирование профиля: ' . $model->title;

?>
<div class="user-info-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
