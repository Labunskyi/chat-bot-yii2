var amemoryApp = function() {
    function t() {
        var t = jQuery(window).scrollTop();
        if (jQuery(".navigation-service").length) i(t, jQuery(".service-stopper").offset().top), d(t);
        else if (jQuery(".navigation-casestudies").length) {
            n(t, jQuery(".casestudies-stopper").offset().top)
        } else e(t)
    }

    function e(t) {
        t > 1 ? jQuery(".desktop-nav").addClass("fixed") : jQuery(".desktop-nav").removeClass("fixed")
    }

    function i(t, e) {
        t > e ? jQuery(".navigation-service").addClass("fixed") : jQuery(".navigation-service").removeClass("fixed")
    }

    function n(t, e) {
        t > e ? jQuery(".navigation-casestudies").addClass("fixed") : jQuery(".navigation-casestudies").removeClass("fixed")
    }

    function r() {
        jQuery(".nav-toggle").on("click", function(t) {
            t.preventDefault(), jQuery(".nav-toggle button").removeClass("clicked"), jQuery(this).hasClass("main") ? (jQuery("body").toggleClass("case-menu-open"), jQuery("html").toggleClass("sub-nav-open")) : jQuery(this).hasClass("main") ? (jQuery("body").toggleClass("service-menu-open"), jQuery("html").toggleClass("sub-nav-open")) : (jQuery("body").toggleClass("menu-open"), jQuery("html").toggleClass("nav-open"), jQuery("body").removeClass("case-menu-open"), jQuery("body").removeClass("service-menu-open"), jQuery("html").removeClass("sub-nav-open")), jQuery(this).find("button").toggleClass("clicked")
        })
    }

    function a() {
        jQuery(".customer-boxes-wrapper").slick({
            dots: !0,
            draggable: !1,
            slidesToShow: 2,
            initialSlide: 1,
            autoplay: !1,
            autoplaySpeed: 4e3,
            speed: 500,
            arrows: !1,
            responsive: [{
                breakpoint: 500,
                settings: {
                    slidesToShow: 1
                }
            }]
        })
    }

    function l() {
        jQuery(".step-control-0").css("opacity", "1"), jQuery(".step").each(function(t) {
            jQuery(".step-control-" + t).mouseenter(function() {
                jQuery(".carousel").slick("slickGoTo", t, !1), jQuery(".step").css("opacity", .6), jQuery(this).css("opacity", "1")
            }), jQuery(".step-control-" + t).on("click", function() {
                jQuery(".carousel").slick("slickGoTo", t, !1), jQuery(".step").css("opacity", .6), jQuery(this).css("opacity", "1")
            })
        }), jQuery(".carousel").slick({
            dots: !1,
            draggable: !1,
            slidesToShow: 1,
            autoplay: !1,
            autoplaySpeed: 4e3,
            speed: 500,
            arrows: !1
        })
    }

    function c() {
        jQuery(".open-case").on("click", function(t) {
            t.preventDefault(), jQuery(this).parent().find(".second-content").slideToggle(), "Подробнее" == jQuery(this).html() ? (jQuery(this).html("Меньше"), jQuery(this).toggleClass("gradient", 500)) : (jQuery(this).html("Подробнее"), jQuery(this).toggleClass("gradient", 500)), x || p(jQuery(this).parent().find(".second-content"))
        })
    }

    function u() {
        jQuery(".scroll-link").on("click", function(t) {
            t.preventDefault(), p(jQuery(this).attr("href"))
        })
    }

    function d(t) {
        var e, i, n = jQuery("#product-intro").offset().top - 95,
            r = jQuery("#key-benefits").offset().top - 95,
            s = jQuery("#key-features").offset().top - 95,
            o = jQuery("#product-slider").offset().top - 95;
        i = jQuery("#plans").length ? (e = jQuery("#plans").offset().top - 95) + jQuery("#plans").outerHeight() : (e = o) + jQuery("#key-features").outerHeight(), t > n && t < r ? (jQuery(".navigation-service a").removeClass("active"), jQuery('a[href="#product-intro"]').addClass("active")) : t > r && t < s ? (jQuery(".navigation-service a").removeClass("active"), jQuery('a[href="#key-benefits"]').addClass("active")) : t > s && t < o ? (jQuery(".navigation-service a").removeClass("active"), jQuery('a[href="#key-features"]').addClass("active")) : t > o && t < e ? (jQuery(".navigation-service a").removeClass("active"), jQuery('a[href="#product-slider"]').addClass("active")) : t > e && t < i ? (jQuery(".navigation-service a").removeClass("active"), jQuery('a[href="#plans"]').addClass("active")) : jQuery(".navigation-service a").removeClass("active")
    }

    function p(t) {
        jQuery("html, body").animate({
            scrollTop: jQuery(t).offset().top - 80
        }, 500)
    }

    var x = jQuery(window).width() > 760;

    return {
        init: function() {
            var e = jQuery(window).width() > 760;
            t(), r(), u(), c(), (e ? l() : a());
        },
        resize: function() {
            var t = jQuery(window).width() > 760;
            jQuery("body").hasClass("main") && (t ? (jQuery(".customer-boxes-wrapper").hasOwnProperty("destroy") && jQuery(".customer-boxes-wrapper").slick("destroy"), l()) : (a(), jQuery(".carousel").hasOwnProperty("destroy") && jQuery(".carousel").slick("destroy")))
        },
        scroll: function() {
            t()
        }
    }
}();
jQuery(document).ready(amemoryApp.init), jQuery(window).resize(amemoryApp.resize), jQuery(window).scroll(amemoryApp.scroll);


jQuery(document).ready(function() {

    /*****************/
    /*   Scroll to   */
    /*****************/

    var elementsForScroll = 'a[to="us"], a[to="form"], a[to="blog"], a[to="case"], a[to="work"], a[to="botinfo"]';
    jQuery(elementsForScroll).click(function(e) {
        e.preventDefault();
        var href = jQuery(this).attr('to');
        jQuery('html,body').stop().animate({
            scrollTop: jQuery('[go="' + href + '"]').offset().top - 150
        }, 1000);
    });

    jQuery(".pill-nav").each(function() {
        jQuery(this).click(function() {
            jQuery(this).parent().find('.click-to-scroll').trigger('click');
        });
    });

    // var elementsForScrollSmallDevise = 'a[to="pills-tab-1"], a[to="pills-tab-2"], a[to="pills-tab-3"], div[to="pills-tab-4"]';
    // if (jQuery(window).width() < 768) {
    //     jQuery(elementsForScrollSmallDevise).click(function(e) {
    //         e.preventDefault();
    //         var href = $(this).attr('to');
    //         jQuery('html,body').stop().animate({
    //             scrollTop: jQuery('[go="' + href + '"]').offset().top - 40
    //         }, 1000);
    //     });
    // }


    /***************************************/
    /*   Send order to telegram and email  */
    /***************************************/

    jQuery( "form" ).on( "submit", function( event ) {
        event.preventDefault();
        jQuery.ajax({
            method: "POST",
            url: "https://kcolin.bot-allert.site/callback.php",
            data: jQuery( this ).serialize()+'&url=Со страницы: '+location.href
        });
        jQuery.ajax({
            method: "POST",
            url: "mail.php",
            data: jQuery( this ).serialize()+'&url=Со страницы: '+ location.href + '&index=1'
        });
        alert('Заявка принята! Скоро с вами свяжеться консультант.');
        jQuery( this ).find('input').val('');
    });

    jQuery( 'a.signup-link' ).click( function( event ) {
        event.preventDefault();
        var email = jQuery( '.fancy-input input' );
        jQuery.ajax({
            method: "POST",
            url: "mail.php",
            data: 'email=' +  email.val()+'&url=Со страницы: '+ location.href + '&index=2'
        });
        alert('Заявка принята! Скоро с вами свяжеться консультант.');
        email.val('');
    });

});
