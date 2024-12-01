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


if (empty($form)) {
    $form = ActiveForm::begin([
        'id' => 'form-create-route',
        'options' => [
            'data-pjax' => true,
        ]
    ]);
}
?>

<?php Pjax::begin([
    'id' => 'route-pause-pjax',
    'enablePushState' => false,
    'enableReplaceState' => false,
    'timeout' => 5000,
])
?>

    <div>
        Время в пути:
        <span class="fw-semibold fs-5 text">
            <?= Edge::secondsToTime($model->time_all) ?>
        </span>
    </div>
    <div>
        Время пребытия:
        <span class="fw-semibold fs-5 text">
            <?= Edge::timeVisit($model->time_start, $model->time_all) ?>
        </span>
    </div>
    
    <?php if (count($model->stop_points)): ?>

        <?php if (Edge::isMoning($model->time_start, $model->time_all)): ?>
            <div class="alert alert-danger p-2 my-3 fs-5 text" role="alert">
                Внимание! Во время прибытия автобуса на конечный пункт
                общественный транспорт не работает
            </div>
        <?php endif ?>

        <div class="fw-semibold fs-5 text my-3">Остановки по маршруту: </div>

        <div class="fw-semibold fs-5 text my-2  border border-primary rounded p-3 col-4">

            <?php foreach ($model->stop_points as $key => $item): ?>
                <?= $form->field($item, "[$key]time_pause")
                    ->textInput(['type' => 'time', 'min' => '02:00', 'max' => '06:00', 'class' => 'pause-item  form-control', 'style'=> 'width: 150px',])
                    ->label($item->point->title, ['class' => 'col-8'])
                ?>
                <?= $form->field($item, "[$key]point_id")->hiddenInput()->label(false) ?>
            <?php endforeach ?>
        </div>
    <?php else: ?>
        <div class="fw-semibold fs-5 text my-3">Остановки по маршруту отсуствуют </div>
    <?php endif ?>   

    <?= $form->field($model, 'route_items')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'time_all')->hiddenInput()->label(false) ?>
            
<?php Pjax::end() ?>