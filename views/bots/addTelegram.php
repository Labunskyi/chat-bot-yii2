<?php
/**
 * @var $model    \app\models\forms\AddTelegramBotForm
 * @var $this     \yii\web\View
 * @var $form     ActiveForm
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Base;

$this->title = Yii::t('app', 'Add Telegram Bot');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Select platform'), 'url'=> ['bots/add']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-12 text-center">
        <h1><?=Yii::t( 'app', 'Fill in the fields to activate the bot' );?></h1>
        <p><?=Yii::t( 'app', 'After creating the first bot, you can connect to it other platforms' );?></p>
<!--        <div class="progress" style="height: 20px;">-->
<!--            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="10" aria-valuemin="10" aria-valuemax="100" style="width:40%;"></div>-->
<!--        </div>-->
    </div>
</div>

<section class="flexbox-container">
    <div class="col-12 d-flex align-items-center justify-content-center">
        <div class="col-md-5 col-12 box-shadow-2 p-0">
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
                    [ 'class' => 'btn mb-1 btn-success btn-md btn-block ', 'name' => 'save-edit' ] ) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="col-md-5 col-12 box-shadow-2 p-0">
            <div class="card-header border-0 text-left">
                <?=Yii::t('app','<h3> How to get API token Telegram? </h3>
                <p> 1. Go to bot: <a href="//t.me/BotFather" target="_blank"> @BotFather </a> </p>
                <p> 2. Click the button: <b> START </b> (If you have previously created bots, go to step 3) </p>
                <p> 3. Write bot: <code>/newbot</code> </p>
                <p> 4. The bot will ask you what to call the new bot. Think and write. </p>
                <p> 5. Next you need to enter the bot\'s nickname, so that it ends with the word <code> bot </code> </p>
                <p> 6. <b> The bot has been created! </b> Copy the resulting API KEY and paste it into the form </p>')?>
            </div>
        </div>
    </div>
</section>

