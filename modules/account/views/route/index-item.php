<?php

use app\models\Edge;
use app\models\Point;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

?>

<div class="form-group mb-3 border border-primary rounded p-3 border-opacity-50">
    <div>
        <div>
            <?php if ($model->updated_at): ?>
                <div class="alert alert-primary p-2" role="alert">
                    Изменено диспетчером
                </div>
            <?php endif ?>
        </div>
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
    </div>
    <div>
        Время в пути:
        <span class="fw-semibold fs-5 text">
            <?= 
            
            Edge::secondsToTime( $model->time_all) ?>
        </span>
    </div>
    <div class="form-group d-flex justify-content-end mt-3 px-2 gap-3">
        <?= Html::a('Pасписание маршрута', ['trace-view', 'id' => $model->id], ['class' => 'btn btn-outline-primary btn-route-view-modal']) ?>
        <?php        
            $origin = new DateTime($model->date_start);
            $target = new DateTime();
            if ($origin > $target &&  $origin->diff($target)->format("%a") >= 3) {
                echo Html::a('Отменить', ['delete', 'id' => $model->id], ['class' => 'btn btn-outline-warning', 'data-method' => 'post']);
            }
        ?>
    </div>
</div>