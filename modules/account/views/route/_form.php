<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\VarDumper;
use yii\web\JqueryAsset;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\Application $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="route-form">

    <?php Pjax::begin([
        'id' => 'route-pjax',
        'enablePushState' => false,
        'enableReplaceState' => false,
        'timeout' => 5000,
    ]) ?>

    <?php $form = ActiveForm::begin([
        'id' => 'route-form',
        'options' => [
            'data-pjax' => true,
        ]
    ]);
    ?>

    <?= $form->field($model, 'step')->hiddenInput()->label(false) ?>

    <div class="<?= $model->step == 1 ? "" : 'd-none' ?>">
        <div class="form-group mb-3 border border-primary rounded p-3 mb-2 border-opacity-50">
            <div class="col-3">
                <?= $form->field($model, 'point_start_id')->dropDownList($startPoints, [
                    'prompt' => 'Выберете начальный пункт',
                ]) ?>

                <?= $form->field($model, 'point_end_id')->dropDownList($startPoints, [
                    'prompt' => 'Выберете конечный пункт'
                ]) ?>

            </div>


            <div class="col-2">
                <?= $form->field($model, 'date_start')->textInput(['type' => 'date', 'min' => date('Y-m-d', time() + 3600 * 24 * 10)]) ?>

                <?= $form->field($model, 'time_start')->textInput(['type' => 'time']) ?>
            </div>
            
            <div class="form-group">
                <?= Html::submitButton('Далее', ['class' => 'btn btn-outline-success']) ?>
            </div>
        </div>

    </div>
    
    <?php if ($model->step == 2): ?>
        <!-- <div class="<?= $model->step == 2 ? "" : 'd-none' ?>"> -->
        <div>
            <div class="form-group mb-3 border border-primary rounded p-3 mb-2 border-opacity-50">
                <div style="color:#999;">Выбор маршрута</div>
            
                        
                    
                
                <div>
                    <?= Yii::$app->formatter->asDate($model->date_start, 'php:d.m.Y') ?> в <?= $model->time_start ?>
                </div>

                <?= ListView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => "{items}",
                    'itemOptions' => ['class' => 'item'],
                    'itemView' => function ($data) use ($form, $model) {
                        return $this->render('item', compact('data', 'model', 'form'));
                    },
                ]) ?>
            </div>
        </div>
    <?php endif ?>

    <?= $form->field($model, 'route_item')->hiddenInput()->label(false) ?>
    <? # $form->field($model, 'time_all')->textInput() 
    ?>

    <? # $form->field($model, 'time_end')->textInput() 
    ?>







    <?php ActiveForm::end(); ?>
    <?php Pjax::end() ?>

</div>


<?php
// $this->registerJsFile('/js/create-route.js', ['depends' => JqueryAsset::class]);
?>