<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\models\Comment;
use frontend\models\UploadImage;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['options' => [ 'enctype' => 'multipart/form-data']]);
    $comment = new Comment();
    $image = new UploadImage();
    ?>


    <p>This is the About page. You may modify the following file to customize its content:</p>
    <div class="form-group">
    </div>
    <code><?= __FILE__ ?></code>

</div>
