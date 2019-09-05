<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $comment frontend\models\Comment */
/* @var $ticket frontend\models\Ticket */

$this->title = 'Chat';
$this->params['breadcrumbs'][] = ['label' => 'Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="ticket-index">
<?php

?>
    <h1>
        <?= Html::encode('Title: ('.$ticket->title. ')') ?>
    </h1>
    <h1>
        <?= Html::encode('Status: '.($ticket->status ? 'Opened' : 'Closed')) ?>
    </h1>

    <?php $form = ActiveForm::begin(); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'rowOptions' => function ($model, $key, $index) {
                if ($model['is_admin'] == 1) {
                    return $index % 2 ? ['style' => 'background-color: #f7f7f7' ] : ['style' => 'background-color: #f0f0f0' ];

                } else {
                    return $index % 2 ? ['style' => 'background-color: #d8e3d5' ] : ['style' => 'background-color: #ced9cb' ];
                }
            },
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name',
                'message',
                'create_time',
            ],
        ]); ?>
    <p>
    <?=  $form->field($comment, 'message')->textarea(['maxlength' => true]) ?>
    </p>
    <p>
    <?= Html::submitButton('Send message', ['name' => 'sendMessage', 'class' => 'btn btn-success']) ?>
    </p>

    <?php ActiveForm::end(); ?>



</div>
