<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * Class PasswordResetRequestForm
 *
 * @package app\models\forms
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ 'email', 'trim' ],
            [ 'email', 'required', 'message' => Yii::t('app', 'The field can not be empty') ],
            [ 'email', 'email', 'message' => Yii::t('app', 'No valid mail \ does not exist') ],
            [
                'email', 'exist',
                'targetClass' => '\app\models\User',
                'filter'      => [ 'status' => User::STATUS_ACTIVE ],
                'message'     => Yii::t('app', 'No valid mail \ does not exist'),
            ],
        ];
    }

    /**
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne( [
            'status' => User::STATUS_ACTIVE,
            'email'  => $this->email,
        ] );

        if ( !$user ) {
            return false;
        }

        if ( !User::isPasswordResetTokenValid( $user->password_reset_token ) ) {
            $user->generatePasswordResetToken();

            if ( !$user->save() ) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                [ 'html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text' ],
                [ 'user' => $user ]
            )
            ->setFrom( [ 'info@' . $_SERVER['HTTP_HOST'] => 'Info - '.Yii::t('app','Password recovery') ] )
            ->setTo( $this->email )
            ->setSubject( Yii::t('app','Password recovery on ') . Yii::$app->name )
            ->send();
    }

}