/*=========================================================================================
	File Name: table-components.js
	Description: Table Components js
	----------------------------------------------------------------------------------------
	Item Name: Modern Admin - Clean Bootstrap 4 Dashboard HTML Template
	Version: 1.0
	Author: Pixinvent
	Author URL: hhttp://www.themeforest.net/user/pixinvent
==========================================================================================*/

(function(window, document, $) {
// Bootstrap TouchSpin JS


'use strict';
  var $html = $('html');

    // Checkbox & Radio 1
    $('.icheck1 input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
    });

    // Checkbox & Radio 2
    $('.icheck2 input').iCheck({
        checkboxClass: 'icheckbox_minimal',
        radioClass: 'iradio_minimal',
    });

    // Square Checkbox & Radio
    $('.skin-square input').iCheck({
        checkboxClass: 'icheckbox_square-red',
        radioClass: 'iradio_square-red',
    });
    //Flat Checkbox & Radio
    $('.skin-flat input').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });
    // Polaris Checkbox & Radio
    $('.skin-polaris input').iCheck({
        checkboxClass: 'icheckbox_polaris',
        radioClass: 'iradio_polaris',
        increaseArea: '-10%'
    });
    // Futurico Checkbox & Radio
    $('.skin-futurico input').iCheck({
        checkboxClass: 'icheckbox_futurico',
        radioClass: 'iradio_futurico',
        increaseArea: '20%'
    });
    /*  Checkbox & Radio Styles Ends   */

})(window, document, jQuery);