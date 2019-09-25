<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * Class EditForm
 *
 * @package app\models\forms
 */
class EditForm extends Model
{

    public $avatar;
    public $name, $surName, $lastName;
    public $phone;
    public $email;
    public $password;
    public $passwordRepeat;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'name',  'email', ], 'required', 'message' => Yii::t('app', 'The field can not be empty') ],
            [ [ 'name', 'lastName',  'surName', 'phone', 'lastName' ], 'string', 'min' => 3, 'max' => 255, 'tooLong' => Yii::t('app', 'up to 255 symbols'), 'tooShort' => Yii::t('app','from 3 symbols') ],



            [ 'email', 'trim' ],
            [ 'email', 'email', 'message' => Yii::t('app', 'No valid mail') ],
            [ 'email', 'validateEditMail' ],

            [ 'password', 'string', 'min' => 6, 'tooShort' => Yii::t('app', 'Password must be longer than 6 characters') ],
            [ 'password', 'validateRepeatPassword' ],
            [ 'passwordRepeat', 'validateRepeatPassword' ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'      => Yii::t('app', 'Name'),
            'lastName'      => Yii::t('app', 'LastName'),
            'surName'      => Yii::t('app', 'SurName'),
            'phone'      => Yii::t('app', 'Phone'),
        ];
    }

    public function validateEditMail()
    {
        if ( Yii::$app->user->identity->email != $this->email && User::findOne( [ 'email' => $this->email ] ) ) {

            $this->addError( 'email', Yii::t('app', 'This email is already taken') );
        }
    }

    public function validateRepeatPassword()
    {
        if ( $this->password != $this->passwordRepeat ) {
            $this->addError( 'password', Yii::t('app', 'Passwords do not match') );
            $this->addError( 'passwordRepeat', Yii::t('app', 'Passwords do not match' ));
        }
    }


    public function save()
    {
        if ( !$this->validate() ) {
            return null;
        }

        $user = Yii::$app->user->identity;
        $user->name = $this->name;
        $user->surname = $this->surName;
        $user->lastname = $this->lastName;

        $user->phone = $this->phone;

        $user->email = $this->email;

        if ( $this->password ) {
            $user->setPassword( $this->password );
        }

        return $user->save();
    }

}