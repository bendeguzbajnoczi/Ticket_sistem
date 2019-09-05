<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $user frontend\models\User */
/* @var $ticker frontend\models\Ticket */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = ['label' => 'Users'];

?>
<div class="ticket-index">

    <?php $form = ActiveForm::begin([
            'action'=>'delete-user',
    ]); ?>

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($model,$key, $index) {
            if ($model['is_admin'] == 1) {
                return $index % 2 ? ['style' => 'background-color: #f5c5c5' ] : ['style' => 'background-color: #ffcfcf' ];
            } else {
                return $index % 2 ? ['style' => 'background-color: #c1f5bf' ] : ['style' => 'background-color: #cbffc9' ];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'email',
            'registered',
            'last_login',
            ['attribute' => 'is_admin',
                'label' =>'Type',
                'value' => function($dataProvider){
                    return $dataProvider['is_admin'] ? 'Admin' : 'User' ;
                },
            ],
            ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width:20px'],
                'template' => '{tickets}',
                'buttons' => [
                    'tickets' => function($url,$dataProvider) {
                        if ($dataProvider['is_admin'] == 0) {
                            return Html::a('Tickets', ['user-tickets', 'id' => $dataProvider['id']], ['class' => 'btn btn-primary']);
                        }else{
                            return null;
                        }
                    },
                ],
            ],
            ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width:20px'],
                'template' => '{updateUser}',
                'buttons' => [
                    'updateUser' => function ($url, $dataProvider) {
                        if ($dataProvider['is_admin'] == 0) {
                            return Html::a('Update', ['ticket/update-user', 'id' => $dataProvider['id']], ['class' => 'btn btn-primary']);
                        }else{
                            return null;
                        }
                    },
                ],
            ],
            ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width:20px'],
                'template' => '{deleteUser}',
                'buttons' => [
                    'deleteUser' => function ($url, $dataProvider) {
                        if ($dataProvider['is_admin'] == 0) {
                            return Html::submitButton('Delete', ['value' => $dataProvider['id'], 'name' => 'deleteUser', 'class' => 'btn btn-danger', 'data' =>
                                ['confirm' => 'Are you sure you want to delete this user ?']]);
                        }else{
                            return null;
                        }
                    },
                ],
            ],

        ],
    ]);

    ActiveForm::end();
    ?>

</div>
