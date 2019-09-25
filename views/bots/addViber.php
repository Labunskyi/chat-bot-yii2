<?php
/**
 * @var $model    \app\models\forms\AddTelegramBotForm
 * @var $this     \yii\web\View
 * @var $form     ActiveForm
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Base;

$this->title = Yii::t('app', 'Add Viber Bot');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Select platform'), 'url'=> ['bots/add']];
$this->params['breadcrumbs'][] = $this->title

?>

<section class="flexbox-container">
    <div class="col-12 d-flex align-items-center justify-content-center">
        <div class="col-md-5 col-10 box-shadow-2 p-0">
            <div class="card-header border-0 text-center">
                <?php $form = ActiveForm::begin( [
                    'id'      => 'form-edit',
                    'options' => [ 'class' => 'block-after-submit' ],
                ] ); ?>
                <?= $form->field( $model, 'token' ); ?>
                <?php if( count(Base::getBases()) != 1 ){ ?>
                    <?= $form->field( $model, 'base_id' )->dropDownList( Base::getBases() ); ?>
                <?php } ?>
                <?= Html::submitButton( Yii::t('app', 'Save'),
                    [ 'class' => 'btn mb-1 btn-success btn-md btn-block', 'name' => 'save-edit' ] ) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="col-md-5 col-12 box-shadow-2 p-0">
            <div class="card-header border-0 text-left">
                <?=Yii::t('app','<h3> How to get Viber Bot Token? </h3>
                <p> 1. Go and login to the site: <a href="https://partners.viber.com/" target="_blank"> https://partners.viber.com/ </a> </p >
                <p> 2. After registering \ authorization in the left menu, find the button "Create Bot Account" </p>
                <p> 3. Fill in all fields of the form. </p>
                <p> 4. After successful creation, find the "Token" field at the bottom of the page, copy and paste to us. </p>')?>
            </div>
        </div>
    </div>
</section>