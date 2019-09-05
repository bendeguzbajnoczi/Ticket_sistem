<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $user frontend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<br class="ticket-form">

<?php $form = ActiveForm::begin(); ?>
<p>
    <?= Html::a('Users',['users'], ['class' => 'btn btn-success']) ?>
</p>

<?= $form->field($user, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($user, 'email')->textInput(['maxlength' => true]) ?>

<p>
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
</p>

<?php ActiveForm::end(); ?>
