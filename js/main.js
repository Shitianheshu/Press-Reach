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


document.addEventListener("DOMContentLoaded", function () {

  // ========================================
  // DESKTOP
  // ========================================

  const mainImage = document.querySelector(".images .img.main");
  const sideImage = document.querySelector(".images .img.side");

  // ========================================
  // MOBILE
  // ========================================

  const mobileTopImage = document.querySelector(".hero-top-image");
  const mobileBottomImage = document.querySelector(".hero-bottom-image");


  // ========================================
  // SAME IMAGE PATHS FOR DESKTOP + MOBILE
  // ========================================

  const mainImages = [
    "./Image/banner-1.png",
    "./Image/banner-3.png"
  ];

  const sideImages = [
    "./Image/banner-2.png",
    "./Image/banner-4.png"
  ];


  // ========================================
  // SETTINGS
  // ========================================

  let currentIndex = 0;
  let isAnimating = false;

  const animationDuration = 1400;
  const imageChangeTime = 630;
  const secondImageDelay = 250;


  // ========================================
  // ANIMATE IMAGE
  // ========================================

  function animateImage(wrapper, images, delay) {

    if (!wrapper) return;

    const image = wrapper.querySelector("img");

    if (!image) return;


    // START BLUE LAYER

    setTimeout(function () {

      wrapper.classList.add("is-changing");

    }, delay);


    // CHANGE IMAGE WHEN BLUE LAYER COVERS IMAGE

    setTimeout(function () {

      image.src = images[currentIndex];

    }, delay + imageChangeTime);


    // REMOVE ANIMATION CLASS

    setTimeout(function () {

      wrapper.classList.remove("is-changing");

    }, delay + animationDuration);

  }


  // ========================================
  // CHANGE IMAGES
  // ========================================

  function changeImages() {

    if (isAnimating) return;

    isAnimating = true;


    // CHANGE INDEX 0 ↔ 1

    currentIndex = currentIndex === 0 ? 1 : 0;


    // =====================================
    // DESKTOP
    // =====================================

    animateImage(
      mainImage,
      mainImages,
      0
    );

    animateImage(
      sideImage,
      sideImages,
      secondImageDelay
    );


    // =====================================
    // MOBILE
    // =====================================

    animateImage(
      mobileTopImage,
      mainImages,
      0
    );

    animateImage(
      mobileBottomImage,
      sideImages,
      secondImageDelay
    );


    // =====================================
    // RESET
    // =====================================

    setTimeout(function () {

      isAnimating = false;

    }, animationDuration + secondImageDelay);

  }


  // FIRST CHANGE AFTER 3 SECONDS

  setTimeout(changeImages, 3000);


  // REPEAT EVERY 6 SECONDS

  setInterval(changeImages, 6000);

});
