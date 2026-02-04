$("document").ready(function () {
    AOS.init();

    // Initialize all blocks present on page load
    document.querySelectorAll('.js-hero-carousel').forEach(initHeroCarousel);
    document.querySelectorAll('.js-post-type-carousel').forEach(initPostTypeCarousel);
});

/**
 * Initialize Hero Carousel Block
 * @param {HTMLElement} element 
 */
const initHeroCarousel = (element) => {
    if (typeof Swiper === 'undefined') {
        console.error('Swiper library not loaded for Hero Carousel block.');
        return;
    }

    const swiperContainer = element.querySelector('.js-hero-swiper');

    // Only init if swiper container exists and hasn't been initialized yet
    if (swiperContainer && !swiperContainer.swiper) {
        new Swiper(swiperContainer, {
            loop: true,
            effect: 'fade',
            fadeEffect: { crossFade: true },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: swiperContainer.querySelector('.swiper-pagination'),
                clickable: true,
            },
        });
    }
};

/**
 * Initialize Post Type Carousel Block
 * @param {HTMLElement} element 
 */
const initPostTypeCarousel = (element) => {
    if (typeof Swiper === 'undefined') {
        console.error('Swiper library not loaded for Post Type Carousel block.');
        return;
    }

    const swiperContainer = element.querySelector('.js-post-type-swiper');
    const prevBtn = element.querySelector('.js-swiper-button-prev');
    const nextBtn = element.querySelector('.js-swiper-button-next');

    // Only init if swiper container exists and hasn't been initialized yet
    if (swiperContainer && !swiperContainer.swiper) {
        new Swiper(swiperContainer, {
            loop: false,
            slidesPerView: 1.1,
            spaceBetween: 20,
            navigation: {
                nextEl: nextBtn,
                prevEl: prevBtn,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2.2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                }
            }
        });
    }
};

/**
 * Initialize Blocks in ACF Admin Preview
 */
if (window.acf) {
    window.acf.addAction('render_block_preview/type=hero-carousel', function ($block) {
        initHeroCarousel($block[0]);
    });

    window.acf.addAction('render_block_preview/type=post-type-carousel', function ($block) {
        initPostTypeCarousel($block[0]);
    });
}