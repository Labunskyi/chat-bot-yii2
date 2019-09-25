$(document).ready(function() {
    $('body').on('click','a.chngst',function(e){
        e.preventDefault();

        if($(this).hasClass('need_status_10'))
            var status = 10;
        if($(this).hasClass('need_status_0'))
            var status = 0;

        var id = $(this).attr('href');
        var obj = $(this);

        $.ajax({
            url: '/user/change-status',
            type: 'post',
            data: {
                'status': status,
                'id': id,
            },
            success: function (result) {
                if (result) {
                    obj.replaceWith(result);
                } else {
                    alert('error change status');
                }
            }
        });

    });

});