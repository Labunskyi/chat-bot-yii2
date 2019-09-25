<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * Class DeleteAccountForm
 *
 * @package app\models\forms
 */
class DeleteAccountForm extends Model
{
    public $reason;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ 'password', 'required' ],
            [ 'reason', 'string' ],
            [ 'password', 'validatePassword' ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password'      => Yii::t('app', 'Your password'),
            'reason'      => Yii::t('app', 'Reason to delete an account'),
        ];
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function validatePassword()
    {
        if ( Yii::$app->getSecurity()->validatePassword($this->password, Yii::$app->user->identity->password_hash)  ) {
            return true;
        }
        $this->addError('password',Yii::t('app', 'Password wrong'));
        return false;
    }


    public function save()
    {
        if ( !$this->validate() ) {
            return null;
        }

        $user = Yii::$app->user->identity;

        return $user->delete();
    }

}