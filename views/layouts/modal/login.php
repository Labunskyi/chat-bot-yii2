<?php
/**
 * @var $loginModel LoginForm
 * @var $form       ActiveForm
 */

use app\models\forms\LoginForm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$loginModel = new LoginForm();

?>

<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="loginLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                <h4 class="modal-title" id="loginLabel"><?=Yii::t('app', 'Login')?></h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin( [ 'id' => 'login-form', 'action' => '/login' ] ); ?>
                <?= $form->field( $loginModel, 'email' )->label(Yii::t('app', 'Email')) ?>
                <?= $form->field( $loginModel, 'password' )->passwordInput()->label(Yii::t('app', 'Password')) ?>
                <?= $form->field( $loginModel, 'rememberMe' )->checkbox( [
                        'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                        'label'    => Yii::t('app', 'Remember Me'),
                    ] ) ?>
                <div class="form-group">
                    <div class="col-lg-xs">
                        <div class="col-sm-3">
                            <?= Html::a( Yii::t('app', 'Forgot your password?'), '#',
                                [  'data-toggle' => 'modal', 'data-target' => '#resetpass', 'data-dismiss' => 'modal', 'onclick' => '$("div.modal#login").removeClass("fade in").fadeOut(); $("div.modal-backdrop.fade.in").fadeOut().detach()', ] ) ?>
                        </div>
                        <div class="col-sm-9">
                            <?=Yii::t('app', 'If you do not have an account yet, ')?><?= Html::a( Yii::t('app', 'register now'), '#',
                                ['data-toggle' => 'modal', 'data-target' => '#registaration', 'data-dismiss' => 'modal', 'onclick' => '$("div.modal#login").removeClass("fade in").fadeOut(); $("div.modal-backdrop.fade.in").fadeOut().detach()', ] ) ?>
                        </div>


                    </div>

                </div>
                <div class="form-group">
                    <?= Html::submitButton( Yii::t('app', 'Login'),
                            [ 'class' => 'btn btn-primary', 'name' => 'login-button' ] ) ?>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
