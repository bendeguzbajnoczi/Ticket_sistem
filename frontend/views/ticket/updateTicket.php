<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $ticket frontend\models\Ticket */
/* @var $uploadImage frontend\models\UploadImage */

$this->title = 'Update Ticket: ' . $ticket->title;
$this->params['breadcrumbs'][] = ['label' => 'Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $ticket->title, 'url' => ['view', 'id' => $ticket->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ticket-update">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'ticket' => $ticket,
        'uploadImage' => $uploadImage
    ]) ?>

</div>
