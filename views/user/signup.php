<?php
/**
 * @var $this  yii\web\View
 * @var $form  yii\bootstrap\ActiveForm
 * @var $model app\models\forms\LoginForm
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'SignUp - ').Yii::$app->name;

\app\assets\InnerAsset::register($this);
?>

<div class="card border-grey border-lighten-3 px-1 py-1 m-0">
    <?=\app\widgets\AuthHead::authHead()?>
    <div class="card-header border-0 pb-0">
        <div class="card-title text-center">
            <img width="200px" src="/images/svg/logo.svg">
        </div>
        <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
            <span><?=Yii::t('app', 'Via social networks')?></span>
        </h6>
    </div>
    <div class="card-content">
        <div class="card-body text-center">
            <?=\app\widgets\AuthHead::authButtons()?>
        </div>
        <p class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-1">
            <span><?=Yii::t('app', 'OR via Email')?></span>
        </p>
        <div class="card-body pt-0">
            <?php $form = ActiveForm::begin( [
                'id'          => 'login-form',
                'layout'      => 'horizontal',
                'fieldConfig' => [
                    'template'     => "<fieldset class=\"form-group floating-label-form-group\">{label}{input}{error}</fieldset>\n",
                ],
            ] ); ?>

            <?= $form->field( $model, 'email' )->textInput(['placeholder' => Yii::t('app', 'Your Email')])->label( '' ) ?>
            <div class="form-group row">
                <div class="col-md-6 col-12 text-center text-sm-left"></div>
                <div class="col-md-6 col-12 float-sm-left text-center text-sm-right"><a href="<?=\yii\helpers\Url::toRoute('user/request-password-reset')?>" class="card-link"><?=Yii::t('app','Forgot your password?')?></a></div>
            </div>
            <?= Html::submitButton(  Yii::t('app', 'SignUp'), [ 'class' => 'btn btn-info btn-block', 'name' => 'signup-button' ] ) ?>

            <?php ActiveForm::end(); ?>
        </div>
        <p class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-1 mb-1">
            <span><?=Yii::t('app', 'If you already have an account')?></span>
        </p>
        <div class="card-body pt-0">
            <a href="<?=\yii\helpers\Url::toRoute('user/login')?>" class="btn btn-danger btn-block"><i class="ft-unlock"></i> <?=Yii::t('app', 'Login')?></a>
        </div>
    </div>
</div>