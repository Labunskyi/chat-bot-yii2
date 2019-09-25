<?php
/**
 * @var $resetModel PasswordResetRequestForm
 * @var $form       ActiveForm
 */

use app\models\forms\PasswordResetRequestForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$resetModel = new PasswordResetRequestForm();

?>
<div class="modal fade" id="resetpass" tabindex="-1" role="dialog" aria-labelledby="resetpassLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                <h4 class="modal-title" id="resetpassLabel"><?=Yii::t('app', 'PASSWORD RECOVERY')?></h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin( [ 'id' => 'request-password-reset-form', 'action' => '/user/request-password-reset' ] ); ?>
                <?= $form->field( $resetModel, 'email' ) ?>
                <div class="col-xs-12">
                    <p><?=Yii::t('app','Enter your e-mail when registering and we will send you a link for password recovery')?>
                    </p>
                </div>
                <div class="form-group">
                    <?= Html::submitButton( Yii::t('app', 'Send'), [ 'class' => 'btn btn-primary' ] ) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>