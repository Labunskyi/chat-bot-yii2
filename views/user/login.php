<?php
/**
 * @var $this  yii\web\View
 * @var $form  yii\bootstrap\ActiveForm
 * @var $model app\models\forms\LoginForm
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t( 'app', 'Login - ' ) . Yii::$app->name;

\app\assets\InnerAsset::register( $this );
?>

<div class="card border-grey border-lighten-3 px-1 py-1 m-0">
    <br>
    <div class="card-content">
        <div class="card-body pt-0">
            <?php $form = ActiveForm::begin( [
                'id'          => 'login-form',
                'layout'      => 'horizontal',
                'fieldConfig' => [
                    'template' => "<fieldset class=\"form-group floating-label-form-group\">{label}{input}{error}</fieldset>\n",
                ],
            ] ); ?>

            <?= $form->field( $model, 'email' )->label( 'Email' )->textInput( [
                'placeholder' => Yii::t( 'app', 'Email' ),
            ] ) ?>

            <?= $form->field( $model, 'password' )->passwordInput( [
                'placeholder' => Yii::t( 'app', 'Password' ),
            ] )->label( Yii::t( 'app', 'Password' ) ) ?>
            <div class="form-group row">
                <div class="col-md-6 col-12 float-sm-left text-center text-sm-left"><a
                            href="<?= \yii\helpers\Url::toRoute( 'user/request-password-reset' ) ?>" class="card-link"><?=Yii::t('app','Forgot your password?')?></a></div>
                <div class="col-md-6 text-center text-sm-left ">
                    <?= $form->field( $model, 'rememberMe' )->checkbox( [
                        'template' => "<div class=\"col-md-12 text-center text-sm-right skin skin-flat \">{input} {label}{error}</div>\n",
                    ] )->label(
                        Yii::t( 'app', 'Remember Me' )
                    ) ?>
                </div>

            </div>
            <?= Html::submitButton( '<i class="ft-unlock"></i> ' . Yii::t( 'app', 'Login' ),
                [ 'class' => 'btn btn-info box-shadow-3 btn-block', 'name' => 'login-button' ] ) ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>





