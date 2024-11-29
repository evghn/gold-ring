<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\JqueryAsset;
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

        <div class="form-group mb-3 border border-primary rounded p-3 mb-2 border-opacity-50">
            <div class="<?= $model->step == 1 ? "" : 'd-none' ?>">
                <div class="col-3">
                    <?= $form->field($model, 'point_start_id')->dropDownList($startPoints, [
                    'prompt' => 'Выберете начальный пункт',            
                ]) ?>
            
                    <?= $form->field($model, 'point_end_id')->dropDownList([], [
                    'prompt' => 'Выберете конечный пункт'
                ]) ?> 

                </div>
                
               
                <div class="col-2">
                    <?= $form->field($model, 'date_start')->textInput(['type' => 'date', 'min' => date('Y-m-d', time() + 3600 * 24 * 10) ]) ?>
                
                    <?= $form->field($model, 'time_start')->textInput(['type' => 'time']) ?>
                </div>        
            </div>
            
        </div>

    <div class="<?= $model->step == 2 ? "" : 'd-none' ?>">
        
    </div>
    

    <?# $form->field($model, 'time_all')->textInput() ?>

    <?# $form->field($model, 'time_end')->textInput() ?>

    

    <?= $form->field($model, 'step')->hiddenInput()->label(false) ?>

    

    <div class="form-group">
        <?= Html::submitButton(
            $model->step == 3 ? 'Сохранить' : 'Далее',
            ['class' => 'btn btn-outline-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php Pjax::end() ?>

</div>


<?php 
    $this->registerJsFile('/js/create-route.js', ['depends' => JqueryAsset::class]);
?>
