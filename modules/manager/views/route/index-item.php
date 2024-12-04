<?php

use app\models\Edge;
use app\models\Point;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

?>

<div class="form-group mb-3 border border-primary rounded p-3 border-opacity-50">
    <div>
        <div class="d-flex justify-content-between">
            <div>
                <span class="fw-semibold fs-5 text text-primary me-3"> Маршрут № <?= $model->id ?> </span>
                <span class="fw-semibold fs-5 text">
                    <?= Yii::$app->formatter->asDate($model->date_start, 'php:d.m.Y') ?>
                </span>
                в
                <span class="fw-semibold fs-5 text">
                    <?= $model->time_start ?>
                </span>
            </div>
            <?php if ($model->updated_at): ?>
                <div class="alert alert-primary p-2" role="alert">
                    Изменено диспетчером
                </div>
            <?php endif ?>
        </div>
        
        <div class="fw-semibold fs-4 text border-bottom border-primary-subtle border-opacity-75 text-primary-emphasis mb-2">
            <?= $model->pointStart->title ?>
            -
            <?= $model->pointEnd->title ?>
        </div>
    </div>
    <div>
        Время в пути:
        <span class="fw-semibold fs-5 text">
            <?= Edge::secondsToTime( $model->time_all) ?>
        </span>
    </div>
    <div class="form-group d-flex justify-content-end mt-3 px-2 gap-3">
        <?php
            $origin = new DateTime($model->date_start);
            $target = new DateTime();
            if ($origin > $target && $origin->diff($target)->format("%a") >5 && $edit) {
                echo Html::a('Pедактирование маршрута', ['update', 'id' => $model->id], ['class' => 'btn btn-outline-primary']);
            }
        ?>
        <?= Html::a('Pасписание маршрута', ['trace-view', 'id' => $model->id], ['class' => 'btn btn-outline-success btn-route-view-modal'])
        ?>
    </div>
</div>