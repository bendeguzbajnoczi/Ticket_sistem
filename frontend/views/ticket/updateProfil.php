<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\User */

$this->title = 'Update Profil: ';
$this->params['breadcrumbs'][] = ['label' => 'Profil', 'url' => ['profil']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ticket-update">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_formProfil', [
        'user' => $user,
    ]) ?>

</div>
