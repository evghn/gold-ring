<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UserInfo $model */
/** @var ActiveForm $form */
?>
<div class="site-register">  

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group d-flex justify-content-between mt-3">
            <?= Html::a('Назад', ['/account'], ['class' => 'btn btn-outline-info']) ?>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-outline-primary']) ?>
        </div>
    <div class="mt-2 form-group mb-3 border border-primary rounded p-3 border-opacity-50">
        <h4>Сведения о юридическом лице</h4>
        <?= $form->field($model, 'title') ?>
        <?= $form->field($model, 'address') ?>
        <?= $form->field($model, 'rto')->widget(\yii\widgets\MaskedInput::class, [
            'mask' => 'РТО 999999',
        ]) ?>
        <?= $form->field($model, 'inn') ?>
        <?= $form->field($model, 'kpp') ?>
    </div>    
    <div class="form-group mb-3 border border-primary rounded p-3 mb-2 border-opacity-50">
        <h4>Сведения о банковских реквизитах</h4>
        <?= $form->field($model, 'rs') ?>
        <?= $form->field($model, 'bank') ?>
        <?= $form->field($model, 'bik') ?>
        <?= $form->field($model, 'kor') ?>
    </div>
    <div class="form-group mb-3 border border-primary rounded p-3 mb-2 border-opacity-50">
        <h4>Сведения о лице, работающего с договорами</h4>
        <?= $form->field($model, 'fio') ?>
        <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::class, [
            'mask' => '+7 999 999 99 99',
        ]) ?>
        <?= $form->field($model, 'email') ?>
    </div>
    <div class="form-group mb-3 border border-primary rounded p-3 mb-2 border-opacity-50">
        <h4>Сведения для входа в систему</h4>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'password_repeat')->passwordInput() ?>
    </div>

        
        <div class="form-group d-flex justify-content-between">
            <?= Html::a('Назад', ['/account'], ['class' => 'btn btn-outline-info']) ?>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-outline-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>    
</div>
