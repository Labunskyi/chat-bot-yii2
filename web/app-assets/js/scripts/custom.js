$(document).ready(function () {
    // $('.block-page').on('click', function () {
    //     $.blockUI({
    //         message: '<div class="ft-refresh-cw icon-spin font-medium-2"></div>',
    //         timeout: 0, //unblock after 2 seconds
    //         overlayCSS: {
    //             backgroundColor: '#FFF',
    //             opacity: 0.8,
    //             cursor: 'wait'
    //         },
    //         css: {
    //             border: 0,
    //             padding: 0,
    //             backgroundColor: 'transparent'
    //         }
    //     });
    // });

    $('form.block-after-submit').on('beforeSubmit', function(e){
        $.blockUI({
            message: '<div class="ft-refresh-cw icon-spin font-medium-2"></div>',
            timeout: 0, //unblock after 2 seconds
            overlayCSS: {
                backgroundColor: '#FFF',
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
    });

    $('.block-after-click').on('click', function(e){
        $.blockUI({
            message: '<div class="ft-refresh-cw icon-spin font-medium-2"></div>',
            timeout: 0, //unblock after 2 seconds
            overlayCSS: {
                backgroundColor: '#FFF',
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
    });

    $('.block-element').on('click', function() {
        var sidebar = $('.block-sidebar');
        var table = $('.block-table');
        $(sidebar).block({
            message: '<div class="ft-refresh-cw icon-spin font-medium-2"></div>',
            timeout: 9999, //unblock after 2 seconds
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
        $(table).block({
            message: '<div class="ft-refresh-cw icon-spin font-medium-2"></div>',
            timeout: 9999, //unblock after 2 seconds
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
    });

});
