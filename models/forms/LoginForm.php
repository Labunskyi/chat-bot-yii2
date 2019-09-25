<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * Class LoginForm
 *
 * @package app\models\forms
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [ [ 'email', 'password' ], 'required', 'message' => Yii::t('app', 'The field can not be empty') ],
            [ 'email', 'email', 'message' => Yii::t('app', 'No valid mail') ],
            [ 'rememberMe', 'boolean' ],
            [ 'password', 'validatePassword' ],
        ];
    }

    /**
     * @param string $attribute
     * @param array  $params
     */
    public function validatePassword( $attribute, $params )
    {
        if ( !$this->hasErrors() ) {
            $user = $this->getUser();

            if ( !$user || !$user->validatePassword( $this->password ) ) {
                $this->addError( $attribute, Yii::t('app', 'Invalid username or password') );
            }
        }
    }

    /**
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ( $this->validate() ) {
            return Yii::$app->user->login( $this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0 );
        }

        return false;
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        if ( $this->_user === false ) {
            $this->_user = User::findByEmail( $this->email );
        }

        return $this->_user;
    }
}
