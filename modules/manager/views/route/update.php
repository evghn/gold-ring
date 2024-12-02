<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;

/** @var yii\web\View $this */
/** @var app\models\Route $model */

$this->title = 'Редактирование маршрута №: ' . $model->id;

?>
<div class="route-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('form-update', [
        'model' => $model,
        'stopItems' => $stopItems,
    ]) ?>

</div>

<?php

$this->registerJsFile('/js/update-route.js', ['depends' => JqueryAsset::class]);
