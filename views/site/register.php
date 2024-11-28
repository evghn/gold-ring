<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UserInfo $model */
/** @var ActiveForm $form */
?>
<div class="site-register">
    <h2>Регистрация пользователя</h2>


    <?php $form = ActiveForm::begin(); ?>

    <div class="mt-5 form-group mb-3 border border-primary rounded p-3 mb-2 border-opacity-50">
        <h4>Сведения о юридическом лице</h4>
        <?= $form->field($model, 'title')->textInput(['value' => 'q']) ?>
        <?= $form->field($model, 'address')->textInput(['value' => 'q']) ?>
        <?= $form->field($model, 'rto')->widget(\yii\widgets\MaskedInput::class, [
            'mask' => 'РТО 999999',
        ]) ?>
        <?= $form->field($model, 'inn')->textInput(['value' => '1234567890']) ?>
        <?= $form->field($model, 'kpp')->textInput(['value' => '123456789']) ?>
    </div>    
    <div class="form-group mb-3 border border-primary rounded p-3 mb-2 border-opacity-50">
        <h4>Сведения о банковских реквизитах</h4>
        <?= $form->field($model, 'rs')->textInput(['value' => '12345678901234567890']) ?>
        <?= $form->field($model, 'bank')->textInput(['value' => 'q']) ?>
        <?= $form->field($model, 'bik')->textInput(['value' => '123456789']) ?>
        <?= $form->field($model, 'kor')->textInput(['value' => '12345678901234567890']) ?>
    </div>
    <div class="form-group mb-3 border border-primary rounded p-3 mb-2 border-opacity-50">
        <h4>Сведения о лице, работающего с договорами</h4>
        <?= $form->field($model, 'fio')->textInput(['value' => 'й']) ?>
        <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::class, [
            'mask' => '+7 999 999 99 99',
            'options' => ['value' => '+7 999 999 99 99'],
        ]) ?>
        <?= $form->field($model, 'email')->textInput(['value' => 'q@q.q']) ?>
    </div>
    <div class="form-group mb-3 border border-primary rounded p-3 mb-2 border-opacity-50">
        <h4>Сведения для входа в систему</h4>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'password_repeat')->passwordInput() ?>
    </div>

        
        <div class="form-group d-flex justify-content-between">
            <?= Html::a('Назад', ['/'], ['class' => 'btn btn-outline-info']) ?>
            <?= Html::submitButton('Регистрация', ['class' => 'btn btn-outline-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

    
</div>
