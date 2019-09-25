<?php
/**
 * @var $form yii\bootstrap\ActiveForm
 * @var $model app\models\forms\ResetPasswordForm
 * @var $this yii\web\View
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
                        'template'     => "<fieldset class=\"form-group position-relative\">{label}{input}<div class=\"form-control-position\"></div>{error}</fieldset>\n",
                    ],
                ] ); ?>
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => ''])->label(Yii::t('app','New Password')) ?>
            <?= $form->field($model, 'passwordRepeat')->passwordInput(['placeholder' => ''])->label(Yii::t('app','Repeat New Password')) ?>
            <div class="form-group">
                <?= Html::submitButton( '<i class="ft-save"></i> '.Yii::t('app', 'Save'), [ 'class' => 'btn btn-info btn-md btn-block' ] ) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>