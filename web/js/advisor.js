
var video = document.getElementById("myVideo");
var btn = document.getElementById("myBtn");
var mask = document.getElementById("mask");
video.pause();

function myFunction() {
    if (video.paused) {
        video.play();
        btn.classList.add("active");
        mask.style.opacity = 0;
    } else {
        video.pause();
        btn.classList.remove("active");
        mask.style.opacity = 1;
    }
}

$(".scroll-btn").click(function() {
    $('html, body').animate({
        scrollTop: $(".sec-7").offset().top
    }, 400);
});

    $(document).ready(function(){
        $('.sec-8__slider').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        arrows: false,
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 576,
                    settings: {
                        arrows: false,
                        dots: true,
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 450,
                    settings: {
                        arrows: false,
                        dots: true,
                        slidesToShow: 1,
                        centerMode: true,
                        centerPadding: '40px',
                    }
                }
            ]
        });
        $('.sec-3__items').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: false,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        arrows: false,
                        dots: true,
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 576,
                    settings: {
                        arrows: false,
                        dots: true,
                        slidesToShow: 1,
                        centerMode: true,
                        centerPadding: '40px',
                    }
                }
            ]
        });
        $('.sec-2__items ').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: false,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        arrows: false,
                        dots: true,
                        slidesToShow: 2,
                        centerMode: true,
                    }
                },
                {
                    breakpoint: 680,
                    settings: {
                        arrows: false,
                        dots: true,
                        slidesToShow: 1,
                        centerMode: true,
                    }
                }
            ]
        });
        $('.sec-5__wrapper').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: false,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        arrows: false,
                        dots: true,
                        centerMode: true,
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 576,
                    settings: {
                        arrows: false,
                        dots: true,
                        slidesToShow: 1,
                        centerMode: true,
                    }
                }
            ]
        });
    });


