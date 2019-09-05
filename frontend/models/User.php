<?php

namespace frontend\models;

use Yii;
use yii\web\IdentityInterface;


/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $last_login
 * @property string $registered
 * @property int $is_admin
 *
 * @property Comment[] $comments
 * @property Ticket[] $tickets
 * @property Ticket[] $tickets0
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface

{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'password'], 'required'],
            ['name', 'trim'],
            ['name', 'unique'],
            [['last_login'], 'safe'],
            [['registered'], 'safe'],
            [['is_admin'], 'integer'],
            [['name', 'email', 'password'], 'string', 'max' => 255],
            ['email', 'trim'],
            ['email', 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'last_login' => 'Last Login',
            'registered' => 'Registered',
            'is_admin' => 'Is Admin',
        ];
    }
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTickets0()
    {
        return $this->hasMany(Ticket::className(), ['user_id' => 'id']);
    }


    /**
     * Generates password hash from password and sets it to the model
     * @param string $password
     */

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Sets last login date
     *
     */

    public function setLastLogin()
    {

        $user = User::findByUsername(Yii::$app->user->username);
        $user->last_login = date('Y-m-d h:i:s', time());
        $user->save();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return User::findOne(['name' => $username]);

    }

    /**
     * {@inheritdoc}d
     */
    public function validateAuthKey($authKey)
    {

    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

    }



    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
}
