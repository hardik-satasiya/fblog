// navigation tricks
var hs = hs || {};
(function($){
    $(document).ready(function() {

        // for height of scrollbar
        // var bigNav = true;
        hs.checkScrollForToggleHeight = debounce(function() {
            var st = $(document).scrollTop();
            if(st > $(".navbar").attr("shrink-on-scroll") ) {
                // if(bigNav) {
                    // bigNav = false;
                    $('.navbar[shrink-on-scroll]').addClass('navbar-onscroll');
                    $('.navbar[shrink-on-scroll]').removeClass('main-navbar-transparent');
                // }
            } else {
                // if( !bigNav ) {
                    // bigNav = true;
                    $('.navbar[shrink-on-scroll]').removeClass('navbar-onscroll');
                    $('.navbar[shrink-on-scroll]').addClass('main-navbar-transparent');
                // }
            }
        }, 1);


        // hide show scrollbar on scroll
        var prevScroll = 0;
        var direction = 1;
        hs.checkScrollForToggleNavbar = debounce(function() {

            var st = $(document).scrollTop();
            // 0 for up -- 1 for down
            if(prevScroll < st) {
                direction = 1;
            }
            else if(prevScroll > st) {
                direction = 0;
            }
            // console.log(prevScroll, st, direction);

            if(st > $(".navbar").attr("toggle-on-scroll") ) {
                if(direction === 0) {
                    // when we scroll up we show title bar
                    $('.navbar[toggle-on-scroll]').removeClass('nav_zero');
                }
                else {
                    // when we scroll down hide it
                    $('.navbar[toggle-on-scroll]').addClass('nav_zero');
                }
            } else {
                $('.navbar[toggle-on-scroll]').removeClass('nav_zero');
            }
            prevScroll = st;
        }, 1);

        // Navbar color change on scroll
        if($('.navbar[shrink-on-scroll]').length != 0){
            $(window).on('scroll', hs.checkScrollForToggleHeight);
        }
        if($('.navbar[toggle-on-scroll]').length != 0){
            $(window).on('scroll', hs.checkScrollForToggleNavbar);
        }

        hs.removeTopNotificaiton = function(event) {
            var current = event.target;
            $('body').removeClass('has-top-notification');
            $(current).parents('.top_notification').slideUp("fast", function() {
                // Animation complete.
                $(this).remove();
            });
        }

        // tirgger default events
        hs.checkScrollForToggleHeight();
        hs.checkScrollForToggleNavbar();
    });
})(jQuery);
