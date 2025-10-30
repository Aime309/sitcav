$(function () {

    // =================================
    // Fixed header
    // =================================
    window.addEventListener("scroll", function () {
        var topbar = document.querySelector(".header-fp");
        if (topbar) {
            if (window.scrollY >= 60) {
                topbar.classList.add("header-sticky");
            } else {
                topbar.classList.remove("header-sticky");
            }
        }
    });

    // Get the element
    let topBtn = document.querySelector(".top-btn");

    // On Click, Scroll to the page's top, replace 'smooth' with 'instant' if you don't want smooth scrolling
    topBtn.onclick = () => window.scrollTo({ top: 0, behavior: "smooth" });

    // On scroll, Show/Hide the btn with animation
    window.onscroll = () => topBtn.style.opacity = window.scrollY > 500 ? 1 : 0;



    $('.leadership-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: false,
        dots: false,
        margin: 30,
        rtl: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            992: {
                items: 3
            },
            1024: {
                items: 4
            }
        }
    })

    $('.testimonial-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: false,
        dots: true,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    })

    $(document).on('click', '.header-fp .navbar-nav .nav-link', function(){
        $(this).addClass('active').siblings().removeClass('active')
    })

});