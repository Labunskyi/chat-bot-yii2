<?php
/**
 * @var $upModel  \app\models\forms\UploadAvatarForm
 * @var $model    \app\models\forms\EditForm
 * @var $this     \yii\web\View
 * @var $avatar   string
 * @var $form     ActiveForm
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\fileupload\FileUpload;
use yii\helpers\Url;
use app\models\User;

$this->title = Yii::t( 'app', 'Edit Profile' );

$this->params['breadcrumbs'][] = Yii::t( 'app', 'Edit Profile' );

$avatar = Yii::$app->user->identity->avatar ? Yii::$app->user->identity->avatar : User::PLACEHOLDER_AVATAR;

?>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?= Yii::t( 'app', 'Personal data' ) ?></h4>
                <a class="heading-elements-toggle"><i class="la la-ellipsis font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content collapse show">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-3 text-center">
                            <div class="choise-avatar">
                                <img src="<?= Url::to( '/img/avatar/' . $avatar ) ?>"
                                     class="rounded-circle img-border height-150 box-shadow-4">
                                <label for="uploadavatarform-avatar"><i class="fa fa-plus-circle"
                                                                        aria-hidden="true"></i></label>
                                <?= FileUpload::widget( [
                                    'model'         => $upModel,
                                    'attribute'     => 'avatar',
                                    'url'           => [ 'user/upload-avatar' ],
                                    'options'       => [ 'accept' => [ 'image/*' ], 'class' => '123' ],
                                    'clientOptions' => [ 'maxFileSize' => 2000000 ],
                                ] ); ?>
                            </div>
                        </div>
                    </div>

                    <?php $form = ActiveForm::begin( [
                        'id'      => 'form-edit',
                        'options' => [ 'enctype' => 'multipart/form-data' ],
                    ] ); ?>
                    <div class="row">
                        <?= $form->field( $model, 'lastName', [ 'options' => [ 'class' => 'form-group col-lg-4' ] ] )
                            ->textInput( [ 'value' => Yii::$app->user->identity->lastname ] ); ?>
                        <?= $form->field( $model, 'name', [ 'options' => [ 'class' => 'form-group col-lg-4' ] ] )
                            ->textInput( [ 'value' => Yii::$app->user->identity->name ] ); ?>
                        <?= $form->field( $model, 'surName', [ 'options' => [ 'class' => 'form-group col-lg-4' ] ] )
                            ->textInput( [ 'value' => Yii::$app->user->identity->surname ] ); ?>

                    </div>

                    <div class="row">
                        <?= $form->field( $model, 'phone', [ 'options' => [ 'class' => 'form-group col-lg-6' ] ] )
                            ->textInput( [ 'value' => Yii::$app->user->identity->phone ] ); ?>
                        <?= $form->field( $model, 'email', [ 'options' => [ 'class' => 'form-group col-lg-6' ] ] )
                            ->textInput( [ 'value' => Yii::$app->user->identity->email ] ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?= Yii::t( 'app', 'Connected social network' ) ?></h4>
                <a class="heading-elements-toggle"><i class="la la-ellipsis font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content collapse show">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <?php if ( \app\models\Auth::findOne( [ 'user_id' => Yii::$app->user->getId(), 'source' => 'facebook' ] ) ) {
                                Yii::$app->session->set( 'rt', 'user/edit' ) ?>
                                <div class="btn btn-social btn-min-width mr-1 mb-1 btn-outline-facebook"><i
                                            class="la la-facebook"></i> <?= Yii::t( 'app',
                                        'Connected with Facebook' ) ?></div>
                            <?php } elseif(getenv( 'FB_AVAILABLE' )) { ?>
                                <a href="<?= Url::to( [ 'user/auth', 'authclient' => 'facebook', 'enforce_https' => 1 ] ) ?>"
                                   class="btn btn-social btn-min-width mr-1 mb-1 btn-facebook"><i
                                            class="la la-facebook"></i> <?=Yii::t('app', 'Connect with Facebook')?></a>
                            <?php } ?>


                            <?php if ( \app\models\Auth::findOne( [ 'user_id' => Yii::$app->user->getId(), 'source' => 'telegram' ] ) ) { ?>
                                <div class="btn btn-social btn-min-width mr-1 mb-1 btn-outline-vimeo"><i
                                            class="fa fa-paper-plane"></i> <?= Yii::t( 'app',
                                        'Connected with Telegram' ) ?></div>
                            <?php }elseif(getenv( 'TG_AVAILABLE' )){ ?>
                                <script async src="https://telegram.org/js/telegram-widget.js?4"
                                        data-telegram-login="<?= getenv( 'LOGIN_BOT_TELEGRAM' ) ?>"
                                        data-size="medium"
                                        data-auth-url="<?= Url::toRoute( [ 'user/telegram-auth' ], true ) ?>"
                                        data-request-access="write">
                                </script>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div-->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?= Yii::t( 'app', 'Change password' ) ?></h4>
                <a class="heading-elements-toggle"><i class="la la-ellipsis font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content collapse show">
                <div class="card-body">
                    <div class="row">
                        <?= $form->field( $model, 'password', [ 'options' => [ 'class' => 'form-group col-lg-6' ] ] )
                            ->label( Yii::t( 'app', 'Password' ) )
                            ->passwordInput() ?>
                        <?= $form->field( $model, 'passwordRepeat',
                            [ 'options' => [ 'class' => 'password-repeat form-group col-lg-6' ] ] )
                            ->label( Yii::t( 'app', 'Confirm password' ) )
                            ->passwordInput() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-content collapse show">
                <div class="card-body">
                    <div class="form-horizontal">
                        <div class="form-group row">
                            <div class="col-12">
                                <?= Html::submitButton( '<i class="ft-save"></i> '.Yii::t( 'app', 'Save' ),
                                    [ 'class' => 'btn btn-success full-width', 'name' => 'save-edit' ] ) ?>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--div class="row">
    <div class="col-12 text-center">

        <div class="col-12">
            <p><?=Yii::t('app' ,'You can delete an account permanently')?></p>
        </div>
        <div class="col-12">
            <a href="<?=Url::toRoute('user/delete-account')?>"> <?= Yii::t( 'app', 'Delete account' ) ?></a>
        </div>
    </div>
</div-->
