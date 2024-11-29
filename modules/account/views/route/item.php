<?php

use yii\bootstrap5\Html;
use yii\helpers\VarDumper;

// VarDumper::dump($model, 10, true);
// VarDumper::dump($form, 10, true);
?>

<?= $form->field($model, 'route_item')->hiddenInput(['value' => json_encode($data)])->label(false) ?>
<div class="form-group mb-3 border border-primary rounded p-3 mb-2 border-opacity-50">
    <div>
        
        
    </div>
    <div>

    </div>
    <div class="form-group">
        <?= Html::submitButton('Выбрать', ['class' => 'btn btn-outline-success']) ?>
    </div>
</div>
