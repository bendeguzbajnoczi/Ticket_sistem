<?php

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\User;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $comment frontend\models\Comment */
/* @var $uploadImage frontend\models\UploadImage */
/* @var \frontend\models\TicketSearch $searchModel */

$this->title = 'Tickets';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="ticket-index">

    <h1>
        <?= Html::encode($this->title) ?>

    </h1>

    <?php
    $form = ActiveForm::begin();
    $user = User::find()->where(['id' => (Yii::$app->user->id)])->one();
    ?>

    <p>
        <?= Html::a('Create Ticket', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model, $key, $index) {
            if (($model['status'] == 0)) {
                //green
                return $index % 2 ? ['style' => 'background-color: #c1f5bf'] : ['style' => 'background-color: #cbffc9'];

            } elseif(($model['status'] == 1) && $model['admin_id'] != null) {
                //yellow
                return $index % 2 ? ['style' => 'background-color: #f5e78f'] : ['style' => 'background-color: #fff199'];

            } else {
                //red
                return $index % 2 ? ['style' => 'background-color: #f5c5c5'] : ['style' => 'background-color: #ffcfcf'];

            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            [
                'attribute' => 'admin_id',
                'value' => 'admin.name',
                'label' => 'Admin',

            ],
            'modify_time',

            ['attribute' => 'status',
                'value' => function($model) {
                    return $model['status'] ? 'Opened' : 'Closed';

                },

            ],
            ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width:20px'],
                'template' => '{chat}',
                'buttons' => [
                    'chat' => function ($url, $model, $key) {
                        return Html::a('Chat', ['chat', 'id' => $model['id']], ['class' => 'btn btn-success']);
                    },
                ],
            ],
            ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width:20px'],
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('View', ['view', 'id' => $model['id']], ['class' => 'btn btn-success']);
                    },
                ],
            ],
            ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width:20px'],
                'template' => '{update}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('Update', ['update-ticket', 'id' => $model['id']], ['class' => 'btn btn-success']);
                    },
                ],
            ],
            ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width:20px'],
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a('Delete', ['delete-ticket', 'id' => $model['id']], ['class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],]);
                    },
                ],
            ],
        ],
    ]); ?>

    <?php
    ActiveForm::end();
    ?>


</div>
