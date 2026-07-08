const b=document.getElementById('menuBtn');
const m=document.getElementById('mobileMenu');
if(b){b.onclick=()=>m.classList.toggle('hidden');}


$(document).ready(function () {

    function initPricingSlider() {

        if ($(window).width() < 768) {

            if (!$('.pricing-slider').hasClass('slick-initialized')) {

                $('.pricing-slider').slick({

                    slidesToShow: 1,
                    slidesToScroll: 1,

                    centerMode: true,
                    centerPadding: '28px',

                    arrows: false,
                    dots: true,

                    infinite: true,
                    speed: 500,

                    autoplay: false,

                    adaptiveHeight: false,

                    mobileFirst: true,

                    responsive: [
                        {
                            breakpoint: 768,
                            settings: "unslick"
                        }
                    ]

                });

            }

        } else {

            if ($('.pricing-slider').hasClass('slick-initialized')) {
                $('.pricing-slider').slick('unslick');
            }

        }

    }

    initPricingSlider();

    $(window).on('resize', function () {
        initPricingSlider();
    });

});


const faqItems = document.querySelectorAll(".faq-item");

faqItems.forEach(item => {

    const btn = item.querySelector(".faq-question");

    btn.addEventListener("click", () => {

        faqItems.forEach(other => {

            if(other !== item){

                other.classList.remove("active");
                other.querySelector(".faq-icon").textContent = "+";

            }

        });

        item.classList.toggle("active");

        const icon = item.querySelector(".faq-icon");

        icon.textContent = item.classList.contains("active") ? "−" : "+";

    });

});