<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $user frontend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-group">

    <p>
        <?= Html::a('Back', ['profil' ,], ['class' => 'btn btn-success']) ?>
    </p>


<?php
 $form = ActiveForm::begin([
        'id' => 'name_adn_email',
    'action' => 'update-profil',
]); ?>

    <?= $form->field($user, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($user, 'email')->textInput(['maxlength' => true]) ?>

    <p>
        <?= Html::submitButton('Save', ['name' => 'saveNameAndEmail', 'class' => 'btn btn-success']) ?>
    </p>
    <?php ActiveForm::end();




    $form = ActiveForm::begin([
            'id' => 'password',
            'action' => 'update-profil',
]); ?>

    <h2>
        <?= Html::encode('Change password:') ?>
    </h2>

    <?= $form->field($user, 'password')->passwordInput(['value' => ''])->label('Current password') ?>

    <?= $form->field($user, 'newPassword')->passwordInput(['value' => '','required' => true])->label('New password')?>
    <p>
        <?= Html::submitButton('Change password', ['name' => 'changePassword', 'class' => 'btn btn-success']) ?>
    </p>

    <?php ActiveForm::end();
    ?>



</div>
