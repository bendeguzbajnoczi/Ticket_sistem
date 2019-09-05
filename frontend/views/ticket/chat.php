<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Comment;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $imageProvider yii\data\ActiveDataProvider */
/* @var $comment frontend\models\Comment */
/* @var $ticket frontend\models\Ticket */
/* @var $uploadImage frontend\models\UploadImage */

$this->title = 'Chat';
$this->params['breadcrumbs'][] = ['label' => 'Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$ticket_id=$ticket->id;
$comment = new Comment;
?>

<div class="ticket-index">

    <h1>
        <?= Html::encode('Title: '.$ticket->title) ?>
    </h1>
    <h1>
        <?= Html::encode('Status: '.($ticket->status ? 'Opened' : 'Closed')) ?>
    </h1>
    <?php $form = ActiveForm::begin([
            'action' => '/ticket/send-message',
    ]); ?>
    <p>
        <?= Html::a('Tickets',['index'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($model, $key, $index) {
            if ($model['user_id'] == Yii::$app->user->id) {
                return $index % 2 ? ['style' => 'background-color: #d8e3d5' ] : ['style' => 'background-color: #ced9cb' ];

            } else {
                return $index % 2 ? ['style' => 'background-color: #f7f7f7' ] : ['style' => 'background-color: #f0f0f0' ];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'user_id',
                'value' => 'user.name',
                'label' => 'Name',

            ],

            'message',
            'create_time',
        ],
    ]); ?>
    <p>
        <?=  $form->field($comment, 'message')->textarea(['maxlength' => true]) ?>
    </p>
    <p>
        <?= Html::submitButton('Send message', [
                'value' => $ticket->id,
            'name' => 'ticketId',
            'class' => 'btn btn-success']) ?>
    </p>
    <?php ActiveForm::end(); ?>







    <?php $form = ActiveForm::begin(['options' => [ 'enctype' => 'multipart/form-data']]); ?>
    <h2>
        <?= Html::encode('Images') ?>
    </h2>
    <p>
        <?= $form->field($uploadImage, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*'])->label('Upload images:') ?>
    </p>
    <p>
        <?= Html::submitButton('Upload images', ['name' => 'uploadImages', 'class' => 'btn btn-success']) ?>
    </p>

    <?php ActiveForm::end(); ?>

    <?= Yii::$app->controller->renderPartial('_showImages',['imageProvider' => $imageProvider]);?>



</div>
