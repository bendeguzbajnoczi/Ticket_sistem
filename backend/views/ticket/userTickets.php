<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $user frontend\models\User */
/* @var $ticker frontend\models\Ticket */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tickets';
$this->params['breadcrumbs'][] = ['label' => $user -> name];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-index">

    <h1>
        <?= Html::encode('Tickets of '. $user['name']) ?>
    </h1>
    <p>
        <?= Html::a('Users',['users'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php $form = ActiveForm::begin([
        'action' => 'delete-user-ticket'
    ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($dataProvider, $index) {
            if(($dataProvider['status'] == 0)) {
                //green
                return $index % 2 ? ['style' => 'background-color: #c1f5bf' ] : ['style' => 'background-color: #cbffc9' ];

            } elseif(($dataProvider['status'] == 1) && $dataProvider['admin_id'] != null) {
                //yellow
                return $index % 2 ? ['style' => 'background-color: #f5e78f' ] : ['style' => 'background-color: #fff199' ];

            } else {
                //red
                return $index % 2 ? ['style' => 'background-color: #f5c5c5'] : ['style' => 'background-color: #ffcfcf' ];

            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute' => 'user_id',
                'value' => 'user.name',
                'label' => 'User',

            ],
            'title',
            [
                'attribute' => 'admin_id',
                'value' => 'admin.name',
                'label' => 'Admin',

            ],
            'modify_time',
            ['attribute' => 'status',
                'value' => function($dataProvider){
                    return $dataProvider['status'] ? 'Opened' : 'Closed' ;
                },
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{chat}',
                'buttons' => [
                    'chat' => function ($url, $model, $key) {
                        return Html::a('Chat', ['chat', 'id' => $model['id']], ['class' => 'btn btn-success']);
                    },
                ],
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{deleteTicket}',
                'buttons' => [
                    'deleteTicket' => function ($url, $dataProvider) {
                        return Html::submitButton('Delete', ['value' => $dataProvider['id'], 'name' => 'deleteTicket', 'class' => 'btn btn-danger','data' => [
                            'confirm' => 'Are you sure you want to delete this ticket ?']]);
                    },
                ],
            ],

        ],
    ]);

    ActiveForm::end();
    ?>

</div>
