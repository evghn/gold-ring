<?php

use app\models\Point;
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
        'id' => 'create-route-pjax',
        'enablePushState' => false,
        'enableReplaceState' => false,
        'timeout' => 5000,
    ]) ?>

    <?php $form = ActiveForm::begin([
        'id' => 'form-create-route',
        'options' => [
            'data-pjax' => true,
        ]
    ]);
    ?>

    <?= $form->field($model, 'step')->hiddenInput()->label(false) ?>

    <div class="<?= $model->step == 1 ? "" : 'd-none' ?>">
        <div class="form-group mb-3 border border-primary rounded p-3 mb-2 border-opacity-50">
            <div class="col-3">
                <?= $form->field($model, 'point_start_id')->dropDownList(Point::getStartPoints(), [
                    'prompt' => 'Выберете начальный пункт',
                ]) ?>

                <?= $form->field($model, 'point_end_id')->dropDownList(Point::getStartPoints(), [
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
        <div class="form-group mb-3 border border-primary rounded p-3 mb-2 border-opacity-50">
            <div style="color:#999;">
                <h4>Выбор маршрута</h4>
            </div>
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{items}",
                'itemOptions' => ['class' => 'item'],
                'itemView' => function ($data, $key) use ($form, $model) {
                    return $this->render('item', compact('data', 'key', 'model', 'form'));
                },
            ]) ?>
        </div>
    <?php endif ?>

    <?php if ($model->step == 3): ?>

        <div class="form-group mb-3 border border-primary rounded p-3 mb-2 border-opacity-50">
            <div style="color:#999;">
                <h4>Выбор промежуточных остановок</h4>
            </div>
            <div>
                <div class="fw-semibold fs-4 text border-bottom border-primary-subtle border-opacity-75 text-primary-emphasis my-2">
                    <?= Point::getStartPoints()[$model->point_start_id] ?>
                    -
                    <?= Point::getStartPoints()[$model->point_end_id] ?>
                </div>
                <div class="my-2">
                    Отправление:
                    <span class="fw-semibold fs-5 text">
                        <?= Yii::$app->formatter->asDate($model->date_start, 'php:d.m.Y') ?>
                    </span>
                    в
                    <span class="fw-semibold fs-5 text">
                        <?= $model->time_start ?>
                    </span>

                </div>

            </div>
            <!-- pjax -->
            <?= $this->render('pause', compact('form', 'model')) ?>

            <div class="form-group d-flex justify-content-end mt-3 px-2">
                <?= Html::submitButton('Сохранить маршрут', ['class' => 'btn btn-outline-primary']) ?>
            </div>
        </div>
    <?php endif ?>



    <div class="d-flex justify-content-end">
        <?= Html::a('Отменить', ['index'], ['class' => 'btn btn-outline-info']) ?>
    </div>
    <?= $form->field($model, 'route_items')->hiddenInput()->label(false) ?>

    <?php ActiveForm::end(); ?>
    <?php Pjax::end() ?>

</div>


<?php
$this->registerJsFile('/js/create-route.js', ['depends' => JqueryAsset::class]);
?>