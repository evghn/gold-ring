<?php

use app\models\Edge;
use app\models\Point;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\widgets\Pjax;

// VarDumper::dump($model, 10, true);
// VarDumper::dump($data, 10, true); die;
date_default_timezone_set('UTC');

$no_save = $no_save ?? false;
?>

<?php
Pjax::begin([
    'id' => 'route-pause-pjax',
    'enablePushState' => false,
    'enableReplaceState' => false,
    'timeout' => 5000,
])
?>
    <?php
        $form = ActiveForm::begin([
            'id' => 'form-update-route',
            'options' => [
                'data-pjax' => true,
            ]
        ]);
    ?>
        <div class="col-5">
            <div>
                <span class="fw-semibold fs-5 text">
                    <?= Yii::$app->formatter->asDate($model->date_start, 'php:d.m.Y') ?>
                </span>
                в
                <span class="fw-semibold fs-5 text">
                    <?= $model->time_start ?>
                </span>
            </div>
            <div class="fw-semibold fs-4 text border-bottom border-primary-subtle border-opacity-75 text-primary-emphasis mb-2">
                <?= $model->pointStart->title ?>
                -
                <?= $model->pointEnd->title ?>
            </div>
            <div>
                Время в пути:
                <span class="fw-semibold fs-5 text">
                    <?= Edge::secondsToTime($model->time_all) ?>
                </span>
            </div>
            <div>
                Время пребытия:
                <span class="fw-semibold fs-5 text">
                    <?# Edge::timeVisit($model->time_start, $model->time_all) ?>
                    <?= Edge::secondsToTime($model->time_end, true) ?>
                    
                </span>
            </div>

            <?php if (count($stopItems)): ?>

                <?php if ($no_save): ?>
                    <div class="alert alert-danger p-2 my-3 fs-5 text" role="alert">
                        Внимание! Автобуса прибывает на конечный пункт раньше 6 утра, 
                        измените вермя остановок!
                    </div>
                <?php endif ?>

                <div class="fw-semibold fs-5 text my-3">Остановки по маршруту: </div>

                <div class="fw-semibold fs-5 text my-2  border border-primary rounded p-3 ">

                    <?php foreach ($stopItems as $key => $item): ?>
                        <?= $form->field($item, "[$key]time_pause")
                            ->textInput(['type' => 'time', 'class' => 'pause-item  form-control', 'style' => 'width: 150px', 'data-route-id' => $model->id])
                            ->label($item->point->title, ['class' => 'col-8'])
                        ?>
                        <?= $form->field($item, "[$key]point_id")->hiddenInput()->label(false) ?>
                    <?php endforeach ?>
                </div>
            <?php else: ?>
                <div class="fw-semibold fs-5 text my-3">Остановки по маршруту отсуствуют </div>
            <?php endif ?>

            
            <div class="d-flex justify-content-between">
                <?= Html::a('Отменить', ['index'], ['class' => 'btn btn-outline-info']) ?>
                <?= Html::a('Сохранить', ['update', 'id' => $model->id], ['class' => 'btn btn-outline-success ' . ($no_save ? 'disabled' : '') ]) ?>
            </div>
        </div>
    <?php ActiveForm::end() ?>
<?php Pjax::end() ?>