<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $imageProvider yii\data\ActiveDataProvider */

?>

<?php $form = ActiveForm::begin([
    'action' => '/ticket/delete-images',
]); ?>

<?= GridView::widget([
    'dataProvider' => $imageProvider,
    'options' => ['style' => 'width:60%'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'images',
            'header' => Html::submitButton('Delete selected', ['class' => 'btn btn-success pull-right']),
            'headerOptions' => ['style' => 'width:20%'],
            'label' => 'Images',
            'value'     => function($imageProvider){
                return Html::img('@web/'.$imageProvider['image_path'],['alt' =>  'Image not found :(','width'=> '500px', 'height'=>'500px']) ;
            },
            'format' => ['raw'],
        ],
        ['class' => 'yii\grid\CheckboxColumn',
            'headerOptions' => ['style' => 'width:20%'],

            'header' => Html::checkBox('selection_all', false, [
                'class' => 'select-on-check-all',
                'label' => 'Check All',
            ]),
        ],
    ]
]); ?>

<?php ActiveForm::end();?>
