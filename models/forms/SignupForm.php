<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * Class SignupForm
 *
 * @package app\models\forms
 */
class SignupForm extends Model
{

    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'email',
                ],
                'required', 'message' => Yii::t('app', 'The field can not be empty'),
            ],

            [ 'email', 'trim' ],
            [ 'email', 'email', 'message' => Yii::t('app', 'No valid mail') ],
            [ 'email', 'unique', 'targetClass' => '\app\models\User', 'message' => Yii::t('app','This email is already taken') ],

        ];
    }


    /**
     * @return User|null
     * @throws \yii\base\Exception
     */
    public function signup()
    {
        if ( !$this->validate() ) {
            return null;
        }

        $user = new User();
        $user->name             = explode('@', $this->email)[0];
        $user->email            = $this->email;
        $user->status           = User::STATUS_ACTIVE;

        $this->password = Yii::$app->getSecurity()->generateRandomString(10);
        $user->setPassword( $this->password );
        $user->generateAuthKey();


        if($user->save()){
//            Yii::$app
//                ->mailer
//                ->compose(
//                    [ 'html' => 'signupAdmin-html', 'text' => 'signupAdmin-text' ],
//                    [ 'user' => $user, 'pass' =>  $this->password]
//                )
//                ->setFrom( [ 'info@' . $_SERVER['HTTP_HOST'] => 'Info' ] )
//                ->setTo( $this->email )
//                ->setSubject( 'Новый пользователь' )
//                ->send();
            return $user;
        }

        return null;
    }

}