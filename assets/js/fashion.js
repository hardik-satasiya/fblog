$(document).ready(function() {
    if($("#slides-collection").length > 0) {
        $("#slides-collection").crossslider({
            containerWidth: 1170,
            // auto_roate: true
        });
    }

    $('.clicking').on('click',function(e) {
        if($(this).hasClass('click-big') ) {
            var that = this
            $(this).removeClass('click-big')
            setTimeout( function() {
                $(that).addClass('click-big');
            },20)
        }
        else {
            $(this).addClass('click-big');
        }

    })
});