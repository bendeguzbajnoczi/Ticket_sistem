<?php

namespace backend\controllers;

use frontend\models\TicketSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use frontend\models\Comment;
use frontend\models\Image;
use frontend\models\Ticket;
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
                        'actions' => ['all-tickets', 'own-tickets', 'chat', 'send-message', 'close-ticket', 'administrate', 'users', 'update-user', 'delete-user', 'user-tickets', 'delete-user-ticket',],
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
     * Lists all ticket models.
     * @return mixed
     */
    public function actionAllTickets()
    {
        Url::remember();

        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->post());

        return $this->render('allTickets', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists ticket models administrated by admin user.
     * @return mixed
     */
    public function actionOwnTickets()
    {
        Url::remember();

        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->post());
        $dataProvider->query->andWhere(['admin_id' => Yii::$app->user->id]);

        return $this->render('ownTickets', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all comment models depending on ticket id.
     * @param integer $id ticket_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionChat($id)
    {
        $ticket = $this->findModel($id);

        $imageProvider = new ActiveDataProvider([
            'query' => Image::find()->where(['ticket_id' => $id]),
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => Comment::find()->where(['ticket_id' => $id])
        ]);

        return $this->render('chat', [
            'dataProvider' => $dataProvider,
            'imageProvider' => $imageProvider,
            'ticket' => $ticket,
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
        $ticket = $this->findModel($_POST['ticketId']);


        if (trim( $_POST['Comment']['message']) != '' && $comment->load(Yii::$app->request->post()) && $comment->validate()) {

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
        return $this->redirect(['chat','id'=> $ticket->id]);
    }

    /**
     * Closes ticket status depending on ticket id.
     * Sets tickets status to zero, => "CLOSED"
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionCloseTicket()
    {
        $ticket = $this->findModel($_POST['close']);
        $ticket->status = 0;

        if ($ticket->save()) {
            // flash message
            Yii::$app->session->setFlash('success', "Ticket Closed.");

        } else {
            // flash message
            Yii::$app->session->setFlash('error', "Could not close, there was an error during save.");
        }
        return $this->redirect(['chat','id'=> $ticket->id]);

    }

    /**
     * Adds ticket to administrated ones.
     * Sets tickets admin_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAdministrate()
    {
        $ticket = $this->findModel($_POST['add']);
        $ticket->admin_id = (Yii::$app->user->id);

        if ($ticket->save()) {
            // flash message
            Yii::$app->session->setFlash('success', "Ticket added to administrated ones.");

        } else {
            // flash message
            Yii::$app->session->setFlash('error', "Ticket have not been added, there was an error during save.");
        }

        return $this->redirect(['chat','id'=> $ticket->id]);

    }

    /**
     * Displays all users.
     * @return mixed
     */
    public function actionUsers()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
            'sort' => [
                'attributes' => [
                    'name',
                    'email',
                    'registered',
                    'last_login',
                    'is_admin',
                ],
                'defaultOrder' => [
                    'is_admin' => SORT_ASC,
                    'name' => SORT_ASC,
                ],
            ],
        ]);

        return $this->render('users', [
            'dataProvider' => $dataProvider,

        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'users' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateUser($id)
    {
        $user = $this->findUser($id);

        if ($user->load(Yii::$app->request->post()) && $user->validate()) {

            if ($user->save()) {
                // flash message
                Yii::$app->session->setFlash('success', "User updated successfully.");

            } else {
                // flash message
                Yii::$app->session->setFlash('error', "User not saved.");

            }
            return $this->redirect(['users',]);
        }
        return $this->render('updateUser', [
            'user' => $user,
        ]);
    }

    /**
     * Deletes user.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Exception|\Throwable in case delete failed.
     */
    public function actionDeleteUser()
    {

        $tickets = Ticket::find()->where(['user_id' => $_POST['deleteUser']])->all();
        foreach($tickets as $ticket) {

            $images = Image::find()->where(['ticket_id' => $ticket->id ])->all();

            foreach ($images as $singleImage) {
                $image = $this->findImage($singleImage->id);
                unlink(Yii::getAlias('@frontend') . '/web/' . $image->image_path);
            }
        }

        if($this->findUser($_POST['deleteUser'])->delete()){
            // flash message
            Yii::$app->session->setFlash('success', "User deleted successfully.");

        } else {
            // flash message
            Yii::$app->session->setFlash('error', "Could not delete user, there was an error during the process.");
        }
        return $this->redirect(['users']);

    }

    /**
     * Lists all image models depending on the ticket id.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUserTickets($id)
    {
        Url::remember();

        $user = $this->findUser($id);
        $dataProvider = new ActiveDataProvider([
            'query' => Ticket::find()->where(['user_id' => $id])
                ->orderBy([
                    'status' => SORT_DESC,
                    'admin_id' => SORT_ASC,
                    'modify_time'=>SORT_DESC,
                ]),
        ]);

        return $this->render('userTickets', [
            'dataProvider' => $dataProvider,
            'user' => $user,

        ]);
    }

    /**
     * Deletes users ticket.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Exception|\Throwable in case delete failed.
     */
    public function actionDeleteUserTicket()
    {

        $ticket = $this->findModel($_POST['deleteTicket']);

        $images = Image::find()->where(['ticket_id' => $_POST['deleteTicket']])->all();

        foreach($images as $singleImage){
            $image = $this->findImage($singleImage->id);
            unlink(Yii::getAlias('@frontend'). '/web/'.$image->image_path);
        }

        if ($ticket->delete()) {
            // flash message
            Yii::$app->session->setFlash('success', "Ticket deleted successfully.");

        } else {
            // flash message
            Yii::$app->session->setFlash('error', "Could not delete ticket, there was an error during the process.");

        }
        return $this->goBack();

    }

    /**
     * Finds the Ticket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return mixed
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
