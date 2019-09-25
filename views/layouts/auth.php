<?php

/**
 * @var $this             \yii\web\View
 * @var $pages            Pages
 * @var $content          string
 * @var $items            array
 * @var $menuItemsRight   array
 * @var $theme            string
 * @var $i                integer
 */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\widgets\Menu;
use app\assets\AppAsset;
use app\models\Pages;
use yii\helpers\Url;

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
<body class="vertical-layout vertical-menu-modern 1-column menu-expanded blank-page blank-page  pace-done" style="background: #3c3c3c!important;"
      data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
<?php $this->beginBody() ?>



<div class="pace  pace-inactive">
    <div class="pace-progress" data-progress-text="100%" data-progress="99"
         style="transform: translate3d(100%, 0px, 0px);">
        <div class="pace-progress-inner"></div>
    </div>
    <div class="pace-activity"></div>
</div>
<!-- ////////////////////////////////////////////////////////////////////////////-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section class="flexbox-container">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <div class="col-md-4 col-12 box-shadow-2 p-0">
                        <?= Alert::widget() ?>
                        <?= $content ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>




<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
