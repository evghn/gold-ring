<?php

use yii\bootstrap5\LinkPager;
use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Личный кабинет';

?>
<div class="route-index">

    <h3><?= Html::encode($this->title) ?></h3>


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
            <div class="col-7 p-3 block-route-list">

                <div class='my-3'>
                    <?= Html::a('Создание маршрута', ['create'], ['class' => 'btn btn-outline-success']) ?>
                    <?= Html::a('test', ['test'], ['class' => 'btn btn-success']) ?>

                </div>
                <div class="fs-4 text border-bottom border-primary-subtle">
                    Предстоящие маршруты
                </div>
                <?php Pjax::begin([
                    'id' => 'block-1',
                    'enablePushState' => false,
                    'timeout' => false,                    
                ]); ?>
                    <?= ListView::widget([
                        'dataProvider' => $dataProviderOn,
                        'layout' => "{items}\n{pager}",
                        // 'itemOptions' => ['class' => 'item'],
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
                <?php Pjax::begin([
                    'id' => 'block-2',
                    'enablePushState' => false,                    
                    'timeout' => false,
                ]); ?>
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

<?php

if ($dataProviderOn->count || $dataProviderOff->count) {
    Modal::begin([
        'id' => 'view-route-modal',
        'title' => 'Просмотр маршрута',
        'size' => 'modal-lg',
        
    ]);        
        Pjax::begin([
            'id' => 'route-view-modal-pjax',
            'enablePushState' => false,                                
            'timeout' => false,
        ]);            
        Pjax::end();
    Modal::end();  
    
    $this->registerJsFile('/js/route-view.js', ['depends' => JqueryAsset::class]);
    // $this->registerJs(['$.pjax.defaults.updateUrl' => false]);

}
