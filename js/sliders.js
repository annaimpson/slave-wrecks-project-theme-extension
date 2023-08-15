(function ($) {
  init();

  function prepareUI() {
    initiateSliders();
  }

  function initiateSliders() {
    // Top slider on the home page
    new Swiper('.home__hero-slider-wrap', {
      effect: 'fade',
      autoplay: {
        delay: 7000
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true
      }
    });

    new Swiper('.newsroom-media-slider__slides-wrap', {
      effect: 'fade',
      autoplay: {
        delay: 7000
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true
      }
    });

    new Swiper('.help-desk-slider__slides-wrap', {
      effect: 'fade',
      autoplay: {
        delay: 7000
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true
      }
    });

    new Swiper('.video-slider__slides-wrap', {
      effect: 'fade',
      autoplay: {
        delay: 7000
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true
      }
    });

    new Swiper('.sponsor-slider__swiper-body', {
      effect: 'fade',
      autoplay: {
        delay: 3000
      }
    });

    new Swiper('.our-history__slides-wrap', {
      slidesPerView: 1,
      spaceBetween: 10,
      slidesPerGroup: 1,
      grabCursor: true,
      loop: true,
      pagination: {
        el: '.swiper-pagination',
        renderBullet: function (index, className) {
          return (
            '<span class="' +
            className +
            '"><span class="our-history__slide-year"></span></span>'
          );
        }
      },
      breakpoints: {
        769: {
          slidesPerView: 'auto',
          spaceBetween: 0,
          pagination: {
            clickable: true
          },
          slideToClickedSlide: true
        }
      }
    });

    loadYearMetaDataToSwiper();
  }

  function loadYearMetaDataToSwiper() {
    const ourHistorySliders = document.querySelectorAll(
      '.our-history__slides-wrap'
    );

    if (!ourHistorySliders) {
      return;
    }

    for (let i = 0; i < ourHistorySliders.length; i++) {
      // Get slides of slider
      const slides = Array.from(
        ourHistorySliders[i].querySelectorAll(
          '.our-history__slide:not(.swiper-slide-duplicate)'
        )
      );
      // Get all pagination bullets of slider
      const paginationBullets = Array.from(
        ourHistorySliders[i].querySelectorAll('.swiper-pagination-bullet')
      );

      slides.forEach(function (slide, index) {
        // Get 'data-year' of slide
        const slideYear = slide.getAttribute('data-year');

        // Get associative bullet's year element wrapper
        const yearWrapper = paginationBullets[index].querySelectorAll(
          '.our-history__slide-year'
        )[0];

        yearWrapper.innerHTML = slideYear;
      });
    }
  }

  function init() {
    window.addEventListener('load', function () {
      prepareUI();
    });
  }
})(jQuery);
