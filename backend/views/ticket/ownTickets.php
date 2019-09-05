<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var \frontend\models\TicketSearch $searchModel */

$this->title = 'Owned tickets';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="ticket-index">

    <?php
    $form = ActiveForm::begin();
    ?>

    <h1>
        <?= Html::encode('Own Tickets') ?>
    </h1>
    <p>
        <?= Html::a('All tickets',['all-tickets'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= Yii::$app->controller->renderPartial('_tickets',['dataProvider' => $dataProvider, 'searchModel' => $searchModel ]);?>

    <?php
    ActiveForm::end();
    ?>

</div>
