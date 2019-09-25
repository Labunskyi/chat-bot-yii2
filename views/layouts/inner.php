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
use app\assets\InnerAsset;
use yii\helpers\Url;
use app\models\User;

InnerAsset::register( $this );

$avatar = Url::to( '@web/img/avatar/' . ( Yii::$app->user->identity->avatar ? Yii::$app->user->identity->avatar : User::PLACEHOLDER_AVATAR ) );
$baseAvailable = \app\models\Base::find()->where( [ 'user_id' => Yii::$app->user->getId() ] )->count();
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

<?php if ( $baseAvailable ){ ?>
<body class="vertical-layout vertical-menu-modern 2-columns fixed-navbar  menu-expanded pace-done <?= $this->params['body_class'] ?? '' ?>"
      data-open="click"
      data-menu="vertical-menu-modern" data-col="2-columns">
<?php }else{ ?>
<body class="vertical-layout 1-columns fixed-navbar  menu-expanded pace-done" data-open="click"
      data-menu="vertical-menu-modern" data-col="1-columns">
<?php } ?>

<style>
    .logo-wrap {
        width: 240px;
        text-align: center;
    }
    .logo-wrap img {
        max-width: 100px;
    }
    .left-menu-container, .main-menu .main-menu-content {
        margin: 0 !important;
        background: #2C303B;
    }
</style>

<?php $this->beginBody() ?>
<nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-brand-center navbar-dark">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mobile-menu d-md-none mr-auto">
                    <a class="nav-link nav-menu-main menu-toggle hidden-xs is-active" href="#">
                        <i class="ft-menu font-large-1"></i>
                    </a>
                </li>
                <li class="nav-item d-md-none">
                    <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile">
                        <i class="la la-ellipsis-v"></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="navbar-container content">
            <div class="collapse navbar-collapse" id="navbar-mobile">
                <ul class="nav navbar-nav mr-auto float-left">
                    <li class="nav-item mr-auto logo-wrap">
                        <a class="navbar-brand" href="#"><img class="brand-logo" alt="modern admin logo" src="/img/logo.png"></a>
                    </li>
                </ul>
                <ul class="nav navbar-nav float-left">
                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">

                <span class="mr-1"><?= Yii::t( 'app', 'Hello, ' ); ?>
                    <strong class="font-bold"><?= Yii::$app->user->identity->name . ' ' . Yii::$app->user->identity->lastname ?></strong>

                </span>
                            <span class="avatar avatar-online bots-list-avatars">
                  <img src="<?= $avatar ?>" alt="avatar"><i></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item"
                               href="<?= Url::to( [ 'user/edit' ] ) ?>"><i
                                        class="ft-user"></i> <?= Yii::t( 'app', 'Edit Profile' ) ?></a>
                            <div class="dropdown-divider"></div>
                            <?= Html::beginForm( [ '/user/logout' ], 'post' )
                            . Html::submitButton( '<i class="ft-power"></i> ' . Yii::t( 'app', 'Logout' ),
                                [ 'class' => 'dropdown-item' ] ) . Html::endForm() ?>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<?php if ( $baseAvailable ) { ?>
    <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow left-menu-container"
         data-scroll-to-active="true">
        <div class="main-menu-content">
            <?php if ( $bot = \app\models\Bots::getPlatformsSession()[0] ) { ?>
                <ul class="navigation navigation-main" id="main-menu-navigation"
                    data-menu="menu-navigation">

                    <li class="nav-item">
                        <a href="<?= Url::toRoute( [ 'bots/bot-view', 'base_id' => $bot->base_id ] ) ?>"><i
                                    class="la la-dashboard"></i> <span
                                    class="menu-title"><?= Yii::t( 'app',
                                    'Dashboard' ) ?></span></a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= Url::toRoute( [ 'customer/list', 'base_id' => $bot->base_id ] ) ?>"><i
                                    class="la la la-users"></i> <span
                                    class="menu-title"><?= Yii::t( 'app',
                                    'Customers' ) ?></span></a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= Url::toRoute( [ 'newsletter/list', 'base_id' => $bot->base_id ] ) ?>"><i class="la la-bullhorn"></i> <span
                                    class="menu-title"> <?= Yii::t( 'app',
                        'Newsletter' ) ?></span></a>
                    </li>
                    <li class="nav-item has-sub"><a href="#"><i class="la la-gear"></i> <span
                                    class="menu-title"><?= Yii::t( 'app',
                                    'Settings' ) ?></span></a>
                        <ul class="menu-content">
                            <li>
                                <a class="menu-item"
                                   href="<?= Url::toRoute( [ 'base/setting', 'base_id' => $bot->base_id ] ) ?>">
                                    <i class="la la-wrench"></i>
                                    <span class="menu-title"><?= Yii::t( 'app', 'General' ) ?></span>
                                </a>
                            </li>
                            <li>
                                <a class="menu-item"
                                   href="<?= Url::toRoute( [ 'menu/list', 'base_id' => $bot->base_id ] ) ?>">
                                    <i class="la la-th-list"></i>
                                    <span class="menu-title">
                                        <?= Yii::t( 'app', 'Main Menu' ) ?></span>
                                </a>
                            </li>
                            <li>
                                <a class="menu-item"
                                   href="<?= Url::toRoute( [ 'command/list', 'base_id' => $bot->base_id ] ) ?>">
                                    <i class="la la-outdent"></i>
                                    <span class="menu-title"><?= Yii::t( 'app', 'Commands' ) ?></span>
                                </a>
                            </li>
                            <li>
                                <a class="menu-item"
                                   href="<?= Url::toRoute( [ 'temp/index', 'base_id' => $bot->base_id ] ) ?>">
                                    <i class="la la-file-code-o"></i>
                                    <span class="menu-title"><?= Yii::t( 'app', 'Templates' ) ?></span>
                                </a>
                            </li>
                            <li class="nav-item has-sub"><a href="#ssп"><i class="la la-android"></i> <span
                                            class="menu-title"><?= Yii::t( 'app',
                                            'Bots' ) ?></span></a>
                                <ul class="menu-content">
                                    <?php $bots = \app\models\Bots::findAll( [ 'base_id' => $bot->base_id ] );
                                    foreach ( $bots as $bot ) { ?>
                                        <li>
                                            <a class="menu-item"
                                               href="<?= Url::toRoute( [ 'bots/setting', 'base_id' => $bot->base_id, 'bot_id' => $bot->id ] ) ?>">
                                                <i class="fab fa-<?= $bot->platform ?>"></i>
                                                <span class="menu-title">
                                                <?= $bot->first_name ?> (<?= $bot->getPlatformName() ?>)</span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            <?php } ?>
        </div>
    </div>
<?php } ?>
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
