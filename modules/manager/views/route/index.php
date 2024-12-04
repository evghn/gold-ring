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

$this->title = 'Личный кабинет менеджера';

?>
<div class="manager-index">

    <h3><?= Html::encode($this->title) ?></h3>


    <div class='row'>



        <div class="fs-4 text border-bottom border-primary-subtle mb-3">
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
                $edit = !$model->updated_at;
                return $this->render('index-item', compact('model', 'edit'));
            },
        ]) ?>

        <?php Pjax::end(); ?>

        <div class="fs-4 text border-bottom border-primary-subtle mb-3">
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
                $edit = false;
                return $this->render('index-item', compact('model', 'edit'));
            },
        ]) ?>
        <?php Pjax::end(); ?>
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
