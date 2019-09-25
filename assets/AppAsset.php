<?php

namespace app\assets;

use yii\web\AssetBundle;


class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/style_vendor.min.css',
        '/css/style.css',
        '/app-assets/fonts/flag-icon-css/css/flag-icon.min.css',
    ];
    public $js = [
        '/app-assets/js/scripts/popover/popover.min.js',
        '/js/libs.min.js',
        '/js/app.js',
    ];
    public $jsOptions = [
        //'async' => 'async',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}