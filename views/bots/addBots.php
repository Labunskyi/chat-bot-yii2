<?php
/**
 * @var $model    \app\models\forms\AddTelegramBotForm
 * @var $this     \yii\web\View
 * @var $form     ActiveForm
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

;

$this->title = Yii::t( 'app', 'Select bot platform' );

$this->params['breadcrumbs'] = false;
$link_fb_on = true;
$style_fb = '';
if ( !getenv( 'FB_AVAILABLE' ) ) {
    $link_fb_on = false;
    $style_fb = 'style="opacity: .5"';
}

?>

<div class="row">
    <div class="col-12 text-center">
        <?php if ( $baseAvailable ) { ?>
            <h1><?= Yii::t( 'app', 'Select your bot platform' ); ?></h1>
            <p><?= Yii::t( 'app', 'After creating the first bot, you can connect to it other bot platforms' ); ?></p>
        <?php } else { ?>
            <h1><?= Yii::t( 'app', 'Select your first bot platform' ); ?></h1>
            <p><?= Yii::t( 'app', 'After creating the first bot, you can connect to it other bot platforms' ); ?></p>
        <?php } ?>
    </div>
</div>

<div class="row">
    <div class="col-6 ">
        <div class="card">
            <a class="card-content block-after-click"
               href="<?= \yii\helpers\Url::toRoute( [ 'bots/add-telegram' ] ) ?>">
                <img class="card-img-top img-fluid" src="/img/platforms/telegram_logo.jpg" alt="Card image cap">
            </a>
        </div>
    </div>
    <div class="col-6 ">
        <div class="card">
            <a class="card-content block-after-click" href="<?= \yii\helpers\Url::toRoute( [ 'bots/add-viber' ] ) ?>">
                <img class="card-img-top img-fluid" src="/img/platforms/viber_logo.jpg" alt="Card image cap">
            </a>
        </div>
    </div>
</div>