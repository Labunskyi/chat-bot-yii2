<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;
use app\models\User;

/**
 * Class ResetPasswordForm
 *
 * @package app\models\forms
 */
class ResetPasswordForm extends Model
{

    public $password;
    public $passwordRepeat;

    /**
     * @var \app\models\User
     */
    private $_user;

    /**
     * @param string $token
     * @param array  $config
     *
     * @throws \yii\base\InvalidParamException
     */
    public function __construct( $token, $config = [] )
    {

        if ( empty( $token ) || !is_string( $token ) ) {
            throw new InvalidParamException( 'Password reset token cannot be blank.' );
        }

        $this->_user = User::findByPasswordResetToken( $token );

        if ( !$this->_user ) {
            throw new InvalidParamException( 'Wrong password reset token.' );
        }

        parent::__construct( $config );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ 'password', 'string', 'min' => 6, 'tooShort' => Yii::t('app', 'Password must be longer than 6 characters') ],
            [ 'password', 'required', 'message' => Yii::t('app', 'The field can not be empty') ],
            [ 'passwordRepeat', 'required', 'message' => Yii::t('app', 'The field can not be empty') ],
            [ 'password', 'validateRepeatPassword' ],
            [ 'passwordRepeat', 'validateRepeatPassword' ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function validateRepeatPassword()
    {
        if ( $this->password != $this->passwordRepeat ) {
            $this->addError( 'password', Yii::t('app', 'Passwords do not match') );
            $this->addError( 'passwordRepeat', Yii::t('app', 'Passwords do not match') );
        }
    }

    /**
     * @return bool
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword( $this->password );
        $user->removePasswordResetToken();

        return $user->save( false );
    }

}