<?php

namespace frontend\controllers;

use frontend\models\TicketSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use frontend\models\Comment;
use frontend\models\Image;
use frontend\models\Ticket;
use frontend\models\UploadImage;
use frontend\models\User;

date_default_timezone_set('Europe/Budapest');
/**
 * TicketController implements the CRUD actions for Ticket model.
 */
class TicketController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'chat', 'send-message', 'delete-images', 'profil', 'view', 'create', 'update-ticket', 'update-profil', 'delete-ticket'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists Users all Ticket models.
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember();

        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->post());
        $dataProvider->query->andWhere(['user_id' => Yii::$app->user->id]);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all comment models depending on the ticket id.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionChat($id)
    {
        Url::remember();

        $ticket = $this->findModel($id);

        $uploadImage = new UploadImage();
        $this->uploadImage($uploadImage, $ticket);

        $imageProvider = new ActiveDataProvider([
            'query' => Image::find()->where(['ticket_id' => $id]),
        ]);

        $dataProvider = new ActiveDataProvider([
        'query' => Comment::find()->where(['ticket_id' => $id])
        ]);

        return $this->render('chat', [
            'dataProvider' => $dataProvider,
            'ticket' => $ticket,
            'imageProvider' => $imageProvider,
            'uploadImage' => $uploadImage,
        ]);
    }

    /**
     * Creates a new comment with the message posted.
     * Sets tickets status to one => "OPENED".
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionSendMessage()
    {
        $comment = new Comment();
        $newComment = $_POST['Comment'];
        $ticket = $this->findModel($_POST['ticketId']);

        if (trim($newComment['message']) != '' && $comment->load(Yii::$app->request->post()) && $comment->validate()) {

            $comment->user_id = Yii::$app->user->id;
            $comment->create_time = date('Y-m-d h:i:s', time());
            $comment->ticket_id = $_POST['ticketId'];

            if (!$comment->save()) {
                // flash message
                Yii::$app->session->setFlash('error', "Could not send message, there was an error during save.");
            }

            $ticket->status = 1;
            $ticket->modify_time = date('Y-m-d h:i:s', time());
            if (!$ticket->save()) {
                // flash message
                Yii::$app->session->setFlash('error', "Ticket not saved, there was an error during save.");
            }
        }
        return $this->redirect(['chat', 'id' => $ticket->id]);
    }

    /**
     *  Deletes image depending on id.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Exception|\Throwable in case delete failed.
     */
    public function actionDeleteImages()
    {

        if(isset($_POST['selection'])){
            foreach($_POST['selection'] as $selectedImageId){

                $image = $this->findImage($selectedImageId);
                unlink($image->image_path);

                if($image->delete()){
                } else {
                    // flash message
                    Yii::$app->session->setFlash('error', "Could not delete image, there was an error during the process.");

                }
            }
            Yii::$app->session->setFlash('success', "Images deleted successfully.");
        }

        return $this->goBack();

    }

    /**
     * Displays user profil.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionProfil()
    {
        return $this->render('profil', [
            'model' => $this->findUser(Yii::$app->user->id),
        ]);
    }

    /**
     * Displays a single Ticket model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        Url::remember();

        $imageProvider = new ActiveDataProvider([
            'query' => Image::find()->where(['ticket_id' => $id]),
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'imageProvider' => $imageProvider,
        ]);
    }

    /**
     * Creates a new Ticket, Comment adn uploadImage model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $ticket = new Ticket();
        $comment = new Comment();
        $uploadImage = new UploadImage();

        if ($comment->load(Yii::$app->request->post()) && $comment->validate()) {

            if ($ticket->load(Yii::$app->request->post()) && $ticket->validate()) {
                $ticket->user_id = Yii::$app->user->id;
                $ticket->modify_time = date('Y-m-d H:i:s', time());

                if ($ticket->save()) {
                    // flash message
                    Yii::$app->session->setFlash('success', "Ticket created successfully.");
                } else {
                    // flash message
                    Yii::$app->session->setFlash('error', "Ticket not saved.");
                    return $this->redirect(['index']);
                }
            }
            $comment->user_id = Yii::$app->user->id;
            $comment->create_time = date('Y-m-d H:i:s', time());
            $comment->ticket_id = $ticket->id;

            if ($comment->save()) {
                // flash message
                Yii::$app->session->setFlash('success', "Ticket and Comment created successfully.");
            } else {
                // flash message
                Yii::$app->session->setFlash('error', "Comment not saved.");
                return $this->redirect(['index']);
            }

            $this->uploadImage($uploadImage, $ticket);

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'ticket' => $ticket,
            'comment' => $comment,
            'uploadImage' => $uploadImage,
        ]);
    }

    /**
     * Updates an existing Ticket model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateTicket($id)
    {

        $ticket = $this->findModel($id);
        $uploadImage = new UploadImage;

        if ($ticket->load(Yii::$app->request->post()) && $ticket->validate()) {

            $ticket->modify_time = date('Y-m-d H:i:s', time());

            if ($ticket->save()) {
                // flash message
                Yii::$app->session->setFlash('success', "Ticket updated successfully.");

            } else {
                // flash message
                Yii::$app->session->setFlash('error', "Ticket not saved.");
                return $this->redirect(['index']);

            }
            $this->uploadImage($uploadImage, $ticket);

            return $this->goBack();

        }
        return $this->render('updateTicket', [
            'ticket' => $ticket,
            'uploadImage' => $uploadImage,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'profil' page.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateProfil()
    {
        $user = $this->findUser(Yii::$app->user->id);
        if(isset($_POST['saveNameAndEmail'])) {

            if ($user->load(Yii::$app->request->post()) && $user->validate()) {

                if ($user->save()) {
                    //flash message
                    Yii::$app->session->setFlash('success', "User updated successfully.");

                } else {
                    //flash message
                    Yii::$app->session->setFlash('error', "User not saved.");

                }
                return $this->redirect(['profil', 'id' => $user->id]);
            }
            return $this->render('updateProfil', [
                'user' => $user,
            ]);
        }

        if(isset($_POST['changePassword'])) {

            $password=$_POST['User']['password'];
            $newPassword=$_POST['User']['newPassword'];

            if ($user->validatePassword($password)) {

                $user->setPassword($newPassword);

                if ($user->save()) {
                    // flash message
                    Yii::$app->session->setFlash('success', "Password updated successfully.");

                } else {
                    // flash message
                    Yii::$app->session->setFlash('error', "Password not saved.");

                }
                return $this->redirect(['profil', 'id' => $user->id]);
            }
            // flash message
            Yii::$app->session->setFlash('error', "Incorrect password!
            ");
        }

        return $this->render('updateProfil', [
            'user' => $user,
        ]);
    }


    /**
     * Deletes an existing Ticket model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteTicket($id)
    {
        $ticket = $this->findModel($id);

        $images = Image::find()->where(['ticket_id' => $id])->all();

        foreach($images as $singleImage){
            $image = $this->findImage($singleImage->id);
            unlink($image->image_path);
        }

        if ($ticket->delete()) {
            // flash message
            Yii::$app->session->setFlash('success', "Ticket deleted successfully.");

        } else {
            // flash message
            Yii::$app->session->setFlash('error', "Could not delete ticket, there was an error during the process.");

        }

        return $this->redirect(['index']);
    }

    /**
     * Saves image and stores its path in database
     * * @param UploadImage $uploadImage[]
     * * @param Ticket $ticket
     */

    protected function uploadImage($uploadImage, $ticket)
    {

        if (Yii::$app->request->isPost) {
            $uploadImage->imageFiles = UploadedFile::getInstances($uploadImage, 'imageFiles');
            if ($uploadImage->upload($ticket)) {
                // file is uploaded successfully

                Yii::$app->session->setFlash('success', 'Save successful!');
            } else {
                Yii::$app->session->setFlash('error', 'Save failed.');
            }
        }
    }

    /**
     * Finds the Ticket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ticket::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findUser($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    /**
     * Finds the Image model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Image
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findImage($id)
    {
        if (($model = Image::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
