$(document).ready(function () {
    AOS.init();

    // Initialize all blocks present on page load
    document.querySelectorAll('.js-hero-carousel').forEach(initHeroCarousel);
    document.querySelectorAll('.js-post-type-carousel').forEach(initPostTypeCarousel);
    initVideoBlock();
    initFaqBlock();
    initNavbarOverlay();
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
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
                1800: {
                    slidesPerView: 4,
                    spaceBetween: 30,
                }
            }
        });
    }
};

/**
 * Initialize Video Block
 * @param {HTMLElement} element 
 */
const initVideoBlock = (element = document) => {
    // Find wrappers within the element (or if element itself is wrapper)
    const wrappers = element.querySelectorAll ? element.querySelectorAll('.js-html5-video-wrapper') : [];

    wrappers.forEach(wrapper => {
        const video = wrapper.querySelector('video');
        const playBtn = wrapper.querySelector('.js-play-trigger');
        const block = wrapper.closest('.video-block');

        if (!video || !playBtn || !block) return;

        // Remove existing listeners to avoid duplicates in ACF preview re-renders
        // A simple way is to use a flag or just clone node, but here checking property might be enough
        if (wrapper.dataset.initialized) return;
        wrapper.dataset.initialized = "true";

        const togglePlay = (e) => {
            e.preventDefault();
            if (video.paused) {
                video.play();
                block.classList.add('is-playing');
            } else {
                video.pause();
                block.classList.remove('is-playing');
            }
        };

        playBtn.addEventListener('click', togglePlay);
        video.addEventListener('click', togglePlay);

        // Ensure state is correct if video ends
        video.addEventListener('ended', () => {
            block.classList.remove('is-playing');
        });
    });
};

/**
 * Initialize Navbar Overlay Logic
 */
const initNavbarOverlay = () => {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;

    // Create the overlay element if it doesn't exist
    if (!document.querySelector('.navbar-overlay')) {
        const overlay = document.createElement('div');
        overlay.classList.add('navbar-overlay');
        document.body.appendChild(overlay);
    }

    const body = document.body;

    // Use event delegation on the navbar container
    // Events fire on the .dropdown parent element and bubble up
    navbar.addEventListener('show.bs.dropdown', function () {
        body.classList.add('has-navbar-overlay');
    });

    navbar.addEventListener('hide.bs.dropdown', function () {
        // Checking for other open dropdowns after a tiny delay to allow class toggle
        setTimeout(() => {
            // Check if any dropdown menu currently has the 'show' class
            const openDropdowns = navbar.querySelectorAll('.dropdown-menu.show');
            if (openDropdowns.length === 0) {
                body.classList.remove('has-navbar-overlay');
            }
        }, 50);
    });
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

    window.acf.addAction('render_block_preview/type=video-block', function ($block) {
        initVideoBlock($block[0]);
    });

    window.acf.addAction('render_block_preview/type=faq-block', function ($block) {
        initFaqBlock($block[0]);
    });
}


/**
 * Initialize FAQ Block
 * @param {HTMLElement} element 
 */
const initFaqBlock = (element) => {
    const root = element || document;

    // 1. Accordion Logic
    const triggers = root.querySelectorAll('.faq-block__trigger');
    triggers.forEach(trigger => {
        if (trigger.dataset.initialized) return;
        trigger.dataset.initialized = "true";

        trigger.addEventListener('click', function (e) {
            e.preventDefault();
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            const targetId = this.getAttribute('aria-controls');
            const target = document.getElementById(targetId);
            const container = this.closest('.faq-block__list');

            // If opening, close others in the same container
            if (!isExpanded && container) {
                const others = container.querySelectorAll('.faq-block__trigger[aria-expanded="true"]');
                others.forEach(other => {
                    other.setAttribute('aria-expanded', 'false');
                    const otherTargetId = other.getAttribute('aria-controls');
                    const otherTarget = document.getElementById(otherTargetId);
                    if (otherTarget) {
                        otherTarget.hidden = true;
                        otherTarget.style.display = 'none';
                    }
                });
            }

            const newState = !isExpanded;
            this.setAttribute('aria-expanded', newState);

            if (target) {
                target.hidden = !newState;
                target.style.display = newState ? 'block' : 'none';
            }
        });
    });

    // 2. Tab Logic (Sidebar)
    // We implement a simple tab switcher in case Bootstrap JS is not available or for ACF Admin
    const tabNavs = root.querySelectorAll('.faq-block__nav');

    tabNavs.forEach(nav => {
        const tabs = nav.querySelectorAll('[role="tab"]');

        tabs.forEach(tab => {
            if (tab.dataset.tabInitialized) return;
            tab.dataset.tabInitialized = "true";

            tab.addEventListener('click', function (e) {
                e.preventDefault();

                // Handle Tab State
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                // Handle Content State
                const targetIdSelector = this.getAttribute('href') || this.dataset.bsTarget;
                if (!targetIdSelector) return;

                // Find content area within the same block context to be safe, or global
                // In ACF preview, IDs might be tricky, but assuming valid ID references:
                const targetId = targetIdSelector.replace('#', '');
                const targetContent = document.getElementById(targetId);

                if (targetContent) {
                    const parent = targetContent.closest('.tab-content');
                    if (parent) {
                        const allPanes = parent.querySelectorAll('.tab-pane');
                        allPanes.forEach(pane => {
                            pane.classList.remove('show', 'active');
                        });
                    }
                    targetContent.classList.add('show', 'active');
                }
            });
        });
    });
};