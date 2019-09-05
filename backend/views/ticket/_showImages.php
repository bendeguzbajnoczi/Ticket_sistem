<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $imageProvider yii\data\ActiveDataProvider */

?>


<?= GridView::widget([
    'dataProvider' => $imageProvider,
    'options' => ['style' => 'width:48%'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute' => 'images',
            'headerOptions' => ['style' => 'width:20%'],
            'label' => 'Images',
            'value'     => function($imageProvider){
                return Html::img('http://ticketsystem.test/'.Yii::$app->urlManager->createUrl($imageProvider['image_path']),['alt' =>  'Image not found :(','width'=> '500px', 'height'=>'500px']) ;
            },
            'format' => ['raw'],
        ],
    ],
]); ?>


