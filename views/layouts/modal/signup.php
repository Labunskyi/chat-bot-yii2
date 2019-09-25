<?php
/**
 * @var $form        ActiveForm
 * @var $signupModel SignupForm
 * @var $pid         PidDetected
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\forms\SignupForm;
use app\widgets\PidDetected;
use dosamigos\datepicker\DatePicker;
use yii\helpers\Url;
use app\models\User;

$signupModel = new SignupForm();

?>

<div class="modal fade" id="registaration" tabindex="-1" role="dialog" aria-labelledby="registarationLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                <h4 class="modal-title" id="registarationLabel"><?=Yii::t('app', 'SignUp')?></h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin( [ 'id' => 'form-signup', 'action' => Url::to(['user/signup']) ] ); ?>
                <?= $form->field( $signupModel, 'email' ) ?>
                <div class="form-group">
                    <?= Html::submitButton( Yii::t('app', 'SignUp'),
                        [ 'class' => 'btn btn-primary', 'name' => 'signup-button' ] ) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>