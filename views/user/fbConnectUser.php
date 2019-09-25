<?php
/**
 * @var $model    \app\models\forms\AddTelegramBotForm
 * @var $this     \yii\web\View
 * @var $form     ActiveForm
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;;

$this->title = Yii::t('facebook', 'Connecting Facebook');

$this->params['breadcrumbs'][] = Yii::t('facebook', $this->title);
Yii::$app->session->set('rt',\yii\helpers\Url::to('bots/add-facebook-page'));

?>
<section>
    <div class="col-12 d-flex align-items-center justify-content-center">
        <div class="col-md-4 col-10 box-shadow-2 p-0">

            <div class="card-header border-0 text-center">
                <p class="m-b-md text text-center text-muted l-h">
                    <?=Yii::t('facebook', 'We\'ll need permissions to manage your Page\'s')?><br>
                    <?=Yii::t('facebook', 'messages in order to automate your replies.')?>
                </p>
                <a href="<?=\yii\helpers\Url::to(['user/auth', 'authclient' => 'facebook', 'enforce_https'=>1 , 'r'=>1]) ?>" class="btn btn-social btn-min-width mr-1 mb-1 btn-facebook block-after-click"><i class="la la-facebook"></i> <?=Yii::t('app', 'Login with Facebook')?></a></a>
            </div>
        </div>
    </div>
</section>