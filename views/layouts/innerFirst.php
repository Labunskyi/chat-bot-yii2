<?php

/**
 * @var $this            \yii\web\View
 * @var $content         string
 * @var $item            array
 * @var $pageEditor      string
 * @var $avatar          string
 */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use app\assets\InnerAsset;
use app\models\Pages;
use app\widgets\PidDetected;
use yii\helpers\Url;
use app\models\User;

InnerAsset::register( $this );

$avatar = Url::to( '@web/img/avatar/' . ( Yii::$app->user->identity->avatar ? Yii::$app->user->identity->avatar : User::PLACEHOLDER_AVATAR ) );
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode( $this->title ) ?></title>
    <?php $this->head() ?>
</head>
<body class="vertical-layout vertical-menu-modern 2-columns fixed-navbar  menu-expanded pace-done" data-open="click"
      data-menu="vertical-menu-modern" data-col="2-columns">
<?php $this->beginBody() ?>
<nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-shadow navbar-light navbar-brand-center">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mobile-menu d-md-none mr-auto"><a
                            class="nav-link nav-menu-main menu-toggle hidden-xs is-active" href="#"><i
                                class="ft-menu font-large-1"></i></a></li>
                <li class="nav-item mr-auto">
                    <a class="navbar-brand" href="<?= Url::home() ?>">
                        <img class="brand-logo" alt="modern admin logo"
                             src="<?= Url::to( '/app-assets/images/logo/logo-80x80.png' ) ?>">
                        <h3 class="brand-text"><?= Yii::$app->name ?></h3>
                    </a>
                </li>
                <li class="nav-item d-none d-md-block float-right"><a class="nav-link modern-nav-toggle pr-0"
                                                                      data-toggle="collapse"><i
                                class="toggle-icon ft-toggle-right font-medium-3 white"
                                data-ticon="ft-toggle-right"></i></a></li>
                <li class="nav-item d-md-none">
                    <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i
                                class="la la-ellipsis-v"></i></a>
                </li>
            </ul>
        </div>
        <div class="navbar-container content">
            <div class="collapse navbar-collapse" id="navbar-mobile">
                <ul class="nav navbar-nav mr-auto float-left">
                    <li class="nav-item dropdown">

                    </li>
                </ul>
                <ul class="nav navbar-nav float-left">
                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">

                <span class="mr-1"><?= Yii::t( 'app', 'Hello, ' ); ?>
                    <strong class="font-bold"><?= Yii::$app->user->identity->name . ' ' . Yii::$app->user->identity->lastname ?></strong>

                </span>
                            <span class="avatar avatar-online">
                  <img src="<?= $avatar ?>" alt="avatar"><i></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left"><a class="dropdown-item"
                                                                         href="<?= Url::to( [ 'user/edit' ] ) ?>"><i
                                        class="ft-user"></i> <?= Yii::t( 'app', 'Edit Profile' ) ?></a>
                            <div class="dropdown-divider"></div>
                            <?= Html::beginForm( [ '/user/logout' ], 'post' )
                            . Html::submitButton( '<i class="ft-power"></i> ' . Yii::t( 'app', 'Logout' ),
                                [ 'class' => 'dropdown-item' ] ) . Html::endForm() ?>
                        </div>
                    </li>
                    <li class="dropdown dropdown-language nav-item">
                        <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false"><i
                                    class="flag-icon flag-icon-<?= ( Yii::$app->language == 'en' ) ? 'gb' : Yii::$app->language ?>"></i><span
                                    class="selected-language"></span></a>
                        <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                            <a href="<?= Url::to( [
                                str_replace( [ '/ru', '/en' ], '', Yii::$app->request->url ), 'language' => 'en',
                            ] ) ?>" class="dropdown-item"><i class="flag-icon flag-icon-gb"></i> English</a>
                            <a href="<?= Url::to( [
                                str_replace( [ '/ru', '/en' ], '', Yii::$app->request->url ), 'language' => 'ru',
                            ] ) ?>" class="dropdown-item"><i class="flag-icon flag-icon-ru"></i> Русский</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
