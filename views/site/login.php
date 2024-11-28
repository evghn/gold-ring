<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Аутентификация';

?>
<div class="site-login">
    <h3><?= Html::encode($this->title) ?></h3>    

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                    'inputOptions' => ['class' => 'col-lg-3 form-control'],
                    'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                ],
            ]); ?>

                <?= $form->field($model, 'inn')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>
                

                <div class="form-group d-flex justify-content-between">
                    
                        <?= Html::a('Регистрация', ['site/register'], ['class' => 'btn btn-outline-primary', 'name' => 'login-button']) ?>
                        <?= Html::submitButton('Вход', ['class' => 'btn btn-outline-success', 'name' => 'login-button']) ?>
                </div>
            
            <?php ActiveForm::end(); ?>

            <div style="color:#999;">
                
            </div>
        </div>
    </div>
</div>
