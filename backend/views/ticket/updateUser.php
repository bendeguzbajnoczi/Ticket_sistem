<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\User */

$this->title = 'Update User: ';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['users']];
$this->params['breadcrumbs'][] = ['label' => $user -> name];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ticket-update">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_formUser', [
        'user' => $user,
    ]) ?>

</div>
