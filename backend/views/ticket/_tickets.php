<?php

use frontend\models\Ticket;
use yii\grid\GridView;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/** @var \frontend\models\TicketSearch $searchModel */


$user = new Ticket();
?>

<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'rowOptions' => function ($model, $key, $index) {
        if (($model['status'] == 0)) {
            //red
            return $index % 2 ? ['style' => 'background-color: #c1f5bf' ] : ['style' => 'background-color: #cbffc9' ];

        } elseif(($model['status'] == 1) && $model['admin_id'] != null) {
            //yellow
            return $index % 2 ? ['style' => 'background-color: #f5e78f' ] : ['style' => 'background-color: #fff199' ];

        } else {
            //green
            return $index % 2 ? ['style' => 'background-color: #f5c5c5'] : ['style' => 'background-color: #ffcfcf' ];
        }
    },
    'columns' => [

        ['class' => 'yii\grid\SerialColumn'],
        'title',
        [
            'attribute' => 'user_id',
            'value' => 'user.name',
            'label' => 'Name',
        ],
        [
            'attribute' => 'admin_id',
            'value' => 'admin.name',
            'label' => 'Admin',
        ],
        'modify_time',
        ['attribute' => 'status',
            'value' => function ($dataProvider) {
                if ($dataProvider['status'] == 1) {
                    return 'Opened';
                }
                return 'Closed';
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
    ]
]);
?>
