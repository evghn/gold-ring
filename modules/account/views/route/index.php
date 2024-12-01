<?php

use app\models\Route;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Личный кабинет';

?>
<div class="application-index">
    
    <h3><?= Html::encode($this->title) ?></h1>
    
    
    <div class='row'>
        <div class="col-5 p-3">
            
            <div class="d-flex justify-content-between my-3">
                Информация о юр.лице
                <?= Html::a('Редактировать профиль', ['user/update', 'id' => $useriInfo->id], ['class' => 'btn btn-outline-primary']) ?>
            </div>
            <?= DetailView::widget([
                'model' => $useriInfo,
                'attributes' => [                    
                    'title',
                    'address',
                    'rto',
                    [
                        'attribute' => 'inn',
                        'value' => $useriInfo->user->inn,
                    ],
                    'kpp',
                    'rs',
                    'bank',
                    'bik',
                    'kor',
                    'fio',
                    'phone',
                    'email:email',
                    
                ],
                ]) ?>
        </div>
        <div class="col-7 p-3">
            
            <div class='my-3'>
                <?= Html::a('Создание маршрута', ['create'], ['class' => 'btn btn-outline-success']) ?>
                <?# Html::a('test', ['test'], ['class' => 'btn btn-success']) ?>

            </div>

            <?php Pjax::begin(); ?>            
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
        
                    'id',
                    'point_start_id',
                    'point_end_id',
                    'date_start',
                    'time_start',
                    //'time_all',
                    //'time_end',
                    //'user_id',
                    //'created_at',
                    //'updated_at',
                    [
                        'class' => ActionColumn::class,
                        'urlCreator' => function ($action, Route $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                         }
                    ],
                ],
            ]); ?>
        
            <?php Pjax::end(); ?>


        </div>

    </div>

</div>
