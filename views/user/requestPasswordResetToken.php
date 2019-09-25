<?php
/**
 * @var $model app\models\forms\PasswordResetRequestForm
 * @var $form  yii\bootstrap\ActiveForm
 * @var $this  yii\web\View
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
\app\assets\InnerAsset::register($this);

$this->title = Yii::t('app','Password recovery'). ' - ' . Yii::$app->name ;
?>


<div class="card border-grey border-lighten-3 px-2 py-2 m-0">
    <div class="card-content">
        <div class="card-body">
            <?php $form = ActiveForm::begin(
                    [
                            'id' => 'request-password-reset-form',
                            'fieldConfig' => [
                                'template'     => "<fieldset class=\"form-group position-relative has-icon-left\">{input}<div class=\"form-control-position\"><i class=\"ft-mail\"></i></div>{error}</fieldset>\n",
                            ],
                    ] ); ?>
            <?= $form->field( $model, 'email' )->textInput( [ 'class' => 'form-control' , 'placeholder' => Yii::t('app', 'Enter the email of your account')] )->label('') ?>
            <div class="form-group">
                <?= Html::submitButton( '<i class="ft-unlock"></i> '.Yii::t('app', 'Restore password'), [ 'class' => 'btn btn-info btn-md btn-block' ] ) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>