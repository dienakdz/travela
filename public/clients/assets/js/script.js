/*-----------------------------------------------------------------------------------
    Template Name: Ravelo - Travel & Tour Booking HTML Template
    Template URI: https://webtend.net/demo/html/ravelo/
    Author: WebTend
    Author URI:  https://webtend.net/
    Version: 1.0

    Note: This is Main JS File.
-----------------------------------------------------------------------------------
	CSS INDEX
	===================
    ## Header Style
    ## Dropdown menu
    ## Submenu
    ## Menu Hidden Sidebar
    ## Search Box
    ## Sidebar Menu
    ## Video Popup
    ## Testimonial Slider
    ## Destination Carousel
    ## Client Logo Carousel
    ## Hot Deals Carousel
    ## Gallery Slider
    ## Product Slider
    ## Gallery Popup
    ## Instagram Gallery
    ## SkillBar
    ## Fact Counter
    ## Destinations Filter
    ## Price Range Fliter
    ## Hover Content
    ## Scroll to Top
    ## Nice Select
    ## AOS Animation
    ## Preloader
    
-----------------------------------------------------------------------------------*/

(function ($) {

    "use strict";

    $(document).ready(function () {

        // ## Header Style and Scroll to Top
        function headerStyle() {
            if ($('.main-header').length) {
                var windowpos = $(window).scrollTop();
                var siteHeader = $('.main-header');
                var scrollLink = $('.scroll-top');
                if (windowpos >= 250) {
                    siteHeader.addClass('fixed-header');
                    scrollLink.fadeIn(300);
                } else {
                    siteHeader.removeClass('fixed-header');
                    scrollLink.fadeOut(300);
                }
            }
        }
        headerStyle();
        
        
        // ## Dropdown menu
        var mobileWidth = 992;
        var navcollapse = $('.navigation li.dropdown');

        navcollapse.hover(function () {
            if ($(window).innerWidth() >= mobileWidth) {
                $(this).children('ul').stop(true, false, true).slideToggle(300);
                $(this).children('.megamenu').stop(true, false, true).slideToggle(300);
            }
        });
        
        // ## Submenu Dropdown Toggle
        if ($('.main-header .navigation li.dropdown ul').length) {
            $('.main-header .navigation li.dropdown').append('<div class="dropdown-btn"><span class="far fa-angle-down"></span></div>');

            //Dropdown Button
            $('.main-header .navigation li.dropdown .dropdown-btn').on('click', function () {
                $(this).prev('ul').slideToggle(500);
                $(this).prev('.megamenu').slideToggle(800);
            });
            
            //Disable dropdown parent link
            $('.navigation li.dropdown > a').on('click', function (e) {
                e.preventDefault();
            });
        }
        
        //Submenu Dropdown Toggle
        if ($('.main-header .main-menu').length) {
            $('.main-header .main-menu .navbar-toggle').click(function () {
                $(this).prev().prev().next().next().children('li.dropdown').hide();
            });
        }
        
         
        // ## Menu Hidden Sidebar Content Toggle
        if($('.menu-sidebar').length){
            //Show Form
            // $('.menu-sidebar').on('click', function(e) {
            //     e.preventDefault();
            //     $('body').toggleClass('side-content-visible');
            // });
            //Hide Form
            $('.hidden-bar .inner-box .cross-icon,.form-back-drop,.close-menu').on('click', function(e) {
                e.preventDefault();
                $('body').removeClass('side-content-visible');
            });
            //Dropdown Menu
            $('.fullscreen-menu .navigation li.dropdown > a').on('click', function() {
                $(this).next('ul').slideToggle(500);
            });
        }
         
        
        // ## Search Box
		$('.nav-search > button').on('click', function () {
			$('.nav-search form').toggleClass('hide');
		});
        
        
        // Hide Box Search WHEN CLICK OUTSIDE
		if ($(window).width() > 767){
			$('body').on('click', function (event) {
				if ($('.nav-search > button').has(event.target).length == 0 && !$('.nav-search > button').is(event.target)
					&& $('.nav-search form').has(event.target).length == 0 && !$('.nav-search form').is(event.target)) {
					if ($('.nav-search form').hasClass('hide') == false) {
						$('.nav-search form').toggleClass('hide');
					};
				}
			});
		}
        
        
        // ## Sidebar Menu
        if ($('.sidebar-menu li.dropdown ul').length) {
            $('.sidebar-menu li.dropdown').append('<div class="dropdown-btn"><span class="far fa-angle-down"></span></div>');

            //Dropdown Button
            $('.sidebar-menu li.dropdown .dropdown-btn').on('click', function () {
                $(this).prev('ul').slideToggle(500);
                $(this).prev('.megamenu').slideToggle(800);
                $(this).parent('li.dropdown').toggleClass('active');
            });
            
            //Disable dropdown parent link
            $('.sidebar-menu li.dropdown > a').on('click', function (e) {
                e.preventDefault();
                $(this).next('ul').slideToggle(500);
                $(this).parent('li.dropdown').toggleClass('active');
            });
        }
        
  
        // ## Video Popup
        if ($('.video-play').length) {
            $('.video-play').magnificPopup({
                type: 'video',
            });
        }

        
        // ## Testimonial Slider
        if ($('.testimonials-active').length) {
            $('.testimonials-active').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                infinite: false,
                speed: 400,
                arrows: false,
                dots: false,
                focusOnSelect: true,
                autoplay: false,
                autoplaySpeed: 5000,
            });
        }

        
        // ## Destination Carousel
        if ($('.destination-active').length) {
            $('.destination-active').slick({
                infinite: true,
                speed: 400,
                arrows: false,
                dots: true,
                focusOnSelect: true,
                autoplay: true,
                autoplaySpeed: 5000,
                slidesToShow: 5,
                slidesToScroll: 2,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 375,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    }
                ]
            });
        }
        
        
        // ## Client Logo Carousel
        if ($('.client-logo-active').length) {
            $('.client-logo-active').slick({
                infinite: true,
                speed: 400,
                arrows: false,
                dots: false,
                focusOnSelect: true,
                autoplay: true,
                autoplaySpeed: 5000,
                slidesToShow: 5,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                ]
            });
        }
        
 
        // ## Hot Deals Carousel
        if ($('.hot-deals-active').length) {
            $('.hot-deals-active').slick({
                infinite: true,
                speed: 400,
                arrows: false,
                dots: true,
                focusOnSelect: true,
                autoplay: true,
                autoplaySpeed: 5000,
                slidesToShow: 3,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        

        // ## Gallery Slider
        if ($('.gallery-slider-active').length) {
            $('.gallery-slider-active').slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                infinite: true,
                speed: 400,
                arrows: false,
                dots: true,
                centerMode: true,
                focusOnSelect: true,
                autoplay: true,
                autoplaySpeed: 5000,
                responsive: [
                    {
                        breakpoint: 1600,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 650,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        
        
        // ## Product Slider
        if ($('.product-slider').length) {
            $('.product-slider').slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                infinite: true,
                speed: 400,
                arrows: false,
                dots: true,
                centerMode: false,
                focusOnSelect: true,
                autoplay: true,
                autoplaySpeed: 5000,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 500,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        
        
        // ## Gallery Popup
        $('.gallery a').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
            },
        });
        
        
        // ## Instagram Gallery 
        $('.instagram-item').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
            },
        });
        
        
        // ## SkillBar
        if ($('.skillbar').length) {
            $('.skillbar').appear(function () {
                $('.skillbar').skillBars({
                    from: 0,
                    speed: 4000,
                    interval: 100,
                });
            });
        }
        
        
         /* ## Fact Counter + Text Count - Our Success */
        if ($('.counter-text-wrap').length) {
            $('.counter-text-wrap').appear(function () {
                
                var $t = $(this),
                    n = $t.find(".count-text").attr("data-stop"),
                    r = parseInt($t.find(".count-text").attr("data-speed"), 10);

                if (!$t.hasClass("counted")) {
                    $t.addClass("counted");
                    $({
                        countNum: $t.find(".count-text").text()
                    }).animate({
                        countNum: n
                    }, {
                        duration: r,
                        easing: "linear",
                        step: function () {
                            $t.find(".count-text").text(Math.floor(this.countNum));
                        },
                        complete: function () {
                            $t.find(".count-text").text(this.countNum);
                        }
                    });
                }

            }, {
                accY: 0
            });
        }
        
        
        // ## Destinations Filter
        $('.destinations-active').imagesLoaded(function () {
			var items = $('.destinations-active').isotope({
				itemSelector: '.item',
				percentPosition: true,
			});
			// items on button click
			$('.destinations-nav').on('click', 'li', function () {
				var filterValue = $(this).attr('data-filter');
				items.isotope({
					filter: filterValue
				});
			});
			// menu active class
			$('.destinations-nav li').on('click', function (event) {
				$(this).siblings('.active').removeClass('active');
				$(this).addClass('active');
				event.preventDefault();
			});
		});
        
        
        // ## Price Range Fliter jQuery UI
        if ($('.price-slider-range').length) {
            $(".price-slider-range").slider({
                range: true,
                min: 0,
                max: 25000000,
                step: 500000,
                values: [0, 20000000],
                slide: function (event, ui) {
                    $("#price").val(ui.values[0].toLocaleString('vi-VN') + " vnđ" + " - " + ui.values[1].toLocaleString('vi-VN') + " vnđ");
                }
            });
            $("#price").val(
                $(".price-slider-range").slider("values", 0).toLocaleString('vi-VN') + 
                " - " + 
                $(".price-slider-range").slider("values", 1).toLocaleString('vi-VN') + " vnđ"
            );
        }
        
        
        
        // ## Hover Content
        $('.hover-content').hover(
            function(){
                $(this).find('.inner-content').slideDown();
            }, function() {
                $(this).find('.inner-content').slideUp();
            }
        );
        
        
        // ## Scroll to Top
        if ($('.scroll-to-target').length) {
            $(".scroll-to-target").on('click', function () {
                var target = $(this).attr('data-target');
                // animate
                $('html, body').animate({
                    scrollTop: $(target).offset().top
                }, 1000);

            });
        }
        
        
        // ## Nice Select
        $('select').niceSelect();
        
        
        // ## AOS Animation
        AOS.init();
        
 
    });
    
    
    /* ==========================================================================
       When document is resize, do
    ========================================================================== */

    $(window).on('resize', function () {
        var mobileWidth = 992;
        var navcollapse = $('.navigation li.dropdown');
        navcollapse.children('ul').hide();
        navcollapse.children('.megamenu').hide();

    });


    /* ==========================================================================
       When document is scroll, do
    ========================================================================== */

    $(window).on('scroll', function () {

        // Header Style and Scroll to Top
        function headerStyle() {
            if ($('.main-header').length) {
                var windowpos = $(window).scrollTop();
                var siteHeader = $('.main-header');
                var scrollLink = $('.scroll-top');
                if (windowpos >= 100) {
                    siteHeader.addClass('fixed-header');
                    scrollLink.fadeIn(300);
                } else {
                    siteHeader.removeClass('fixed-header');
                    scrollLink.fadeOut(300);
                }
            }
        }

        headerStyle();

    });

    /* ==========================================================================
       When document is loaded, do
    ========================================================================== */

    $(window).on('load', function () {

        // ## Preloader
        function handlePreloader() {
            if ($('.preloader').length) {
                $('.preloader').delay(200).fadeOut(500);
            }
        }
        handlePreloader();
        
    });

})(window.jQuery);
