<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\User */


$this->title = 'Profil';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-index">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>
    <?php $form = ActiveForm::begin(); ?>
    <p>
        <?= Html::a('Update', ['update-profil'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'id',
            'name',
            'email',
            'registered',
            'last_login',
        ],
    ]) ?>
    <?php
    ActiveForm::end();


    ?>

</div>
