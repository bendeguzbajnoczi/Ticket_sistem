<?php
namespace frontend\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;


class uploadImage extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $imageFiles;

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 8], ];
    }

    public function upload($ticket)
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {
                do {
                    $name = Yii::$app->security->generateRandomString(20);
                } while (Image::find()->where(['image_path' =>  'uploads/' . $name . '.' . $file->extension])->one());

                    $file->saveAs('uploads/' . $name . '.' . $file->extension);
                    $image = new Image();
                    $image->image_path = 'uploads/' . $name . '.' . $file->extension;
                    $image->ticket_id = $ticket->id;
                    $image->save();
                }

            return true;
        } else {
            return false;
        }
    }
}