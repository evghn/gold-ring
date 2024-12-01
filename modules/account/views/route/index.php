<?php

use yii\bootstrap5\LinkPager;
use yii\bootstrap5\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;
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
                    <?= Html::a('test', ['test'], ['class' => 'btn btn-success']) ?>

                </div>
                <div class="fs-4 text border-bottom border-primary-subtle">
                    Предстоящие маршруты
                </div>
                <?php Pjax::begin(); ?>
                <?= ListView::widget([
                    'dataProvider' => $dataProviderOn,
                    'layout' => "{items}\n{pager}",
                    'itemOptions' => ['class' => 'item'],
                    'pager' => [
                        'class' => LinkPager::class
                    ],
                    'itemView' => function ($model) {
                        return $this->render('index-item', compact('model'));
                    },
                ]) ?>

                <?php Pjax::end(); ?>

                <div class="fs-4 text border-bottom border-primary-subtle">
                    Прошедшие маршруты
                </div>
                <?php Pjax::begin(); ?>
                    <?= ListView::widget([
                        'dataProvider' => $dataProviderOff,
                        'layout' => "{items}\n{pager}",
                        'itemOptions' => ['class' => 'item'],
                        'pager' => [
                            'class' => LinkPager::class
                        ],
                        'itemView' => function ($model) {
                            return $this->render('index-item', compact('model'));
                        },
                    ]) ?>
                <?php Pjax::end(); ?>


            </div>

        </div>

</div>