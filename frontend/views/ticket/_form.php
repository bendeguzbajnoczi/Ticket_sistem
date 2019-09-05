<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Comment;

/* @var $ticket frontend\models\Ticket */
/* @var $comment frontend\models\Comment */
/* @var $uploadImage frontend\models\UploadImage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ticket-form">

    <?php $form = ActiveForm::begin(['options' => [ 'enctype' => 'multipart/form-data']]); ?>

    <p>
        <?= Html::a('Back', ['index' ,], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $form->field($ticket, 'title')->textInput(['maxlength' => true]) ?>

    <?php if(!(Comment::find() -> where(['ticket_id' => $ticket->id]) -> one() )){ ?>

    <?= $form->field($comment, 'message')->textInput(['maxlength' => true]) ?>

    <?php } ?>

    <?= $form->field($uploadImage, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>


    <p>
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </p>

    <?php ActiveForm::end();

    ?>

</div>
