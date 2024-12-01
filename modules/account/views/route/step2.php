<?php

use app\models\Edges;
use app\models\Point;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

// VarDumper::dump($data, 10, true);
?>

<div class="form-group mb-3 border border-primary rounded p-3 border-opacity-50 route-item">
        <?php if ($data['min_time']): ?>
            <div class="alert alert-primary p-2" role="alert">
                самый быстрый маршрут
            </div>
        <?php endif ?>

        <div>
            <div class="fw-semibold fs-4 text border-bottom border-primary-subtle border-opacity-75 text-primary-emphasis mb-2">
                <?= Point::getPoints()[$model->point_start_id] ?>
                -
                <?= Point::getPoints()[$model->point_end_id] ?>
            </div>
            <div>
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

        <span class="fw-semibold fs-6 text">Маршрут: </span>
        <span class="fw-semibold fs-5 text text-secondary">
            <?= Point::getPoints()[$model->point_start_id] 
                . ( $data['points'] 
                    ? ' - ' . implode(" - ", ArrayHelper::getColumn($data['points'], 'source_title'))
                    : ''
                )
                . ' - '  
                . Point::getPoints()[$model->point_end_id]
            ?>
        </span>
        <div>
            Время в пути: 
            <span class="fw-semibold fs-5 text">
                <?= Edges::secondsToTime($data['time_all']) ?>
            </span>
        </div>
        <div class="form-group d-flex justify-content-end mt-3 px-2">
            <?= Html::submitButton('Выбрать маршрут', ['class' => 'btn btn-outline-primary', 'name' => "route-$key", 'value' => $key ]) ?>
        </div>
</div>
