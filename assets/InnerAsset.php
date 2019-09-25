<?php

namespace app\assets;

use yii\web\AssetBundle;

class InnerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Quicksand:300,400,500,700',
        '/css/line-awesome.min.css',
        '/app-assets/css/plugins/forms/checkboxes-radios.min.css',
        '/app-assets/vendors/css/forms/icheck/flat/_all.css',
        '/app-assets/vendors/css/forms/icheck/square/_all.css',
        '/app-assets/css/admin-custom.css',
        '/app-assets/css/vendors.min.css',
        '/app-assets/vendors/css/ui/prism.min.css',
        '/app-assets/css/app.min.css',
        '/app-assets/css/core/menu/menu-types/vertical-menu-modern.css',
        '/app-assets/css/core/colors/palette-gradient.min.css',
        '/app-assets/css/core/colors/palette-callout.min.css',
        '/app-assets/css/components.min.css',
        '/app-assets/css/pages/chat-application.css',
        '/app-assets/css/pages/users.min.css',
        '/app-assets/fonts/simple-line-icons/style.min.css',
        //'/app-assets/vendors/css/extensions/bootstrap-treeview.min.css',
        '//use.fontawesome.com/releases/v5.8.1/css/all.css',

    ];
    public $js = [
        '/js/custom.js',
        '/app-assets/vendors/js/ui/prism.min.js',
        '/app-assets/js/core/app-menu.min.js',
        '/app-assets/js/core/app.min.js',
        '/app-assets/js/scripts/customizer.min.js',
        '/app-assets/vendors/js/ui/blockUI.min.js',
        '/app-assets/vendors/js/extensions/sweetalert.min.js',
//        '/app-assets/vendors/js/forms/select/jquery.selectBoxIt.min.js',
//        '/app-assets/js/scripts/forms/select/form-selectBoxIt.min.js',
        '/app-assets/js/scripts/custom.js',
        '/js/plugins/iCheck/icheck.min.js',
        '/app-assets/js/scripts/forms/checkbox-radio.min.js',
        '/app-assets/js/scripts/tables/components/table-components.js',
        //'/app-assets/js/scripts/extensions/tree-view.min.js',


    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',

    ];
}