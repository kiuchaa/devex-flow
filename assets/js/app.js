$(document).ready(function () {
    // 1. Debounced AAS Refresh to prevent forced reflows
    let aosTimeout;
    const refreshAOS = () => {
        clearTimeout(aosTimeout);
        aosTimeout = setTimeout(() => {
            if (window.AOS) {
                AOS.refresh();
            }
        }, 150);
    };

    // Boot all blocks based on their ID prefixes first
    initBlocks(document, refreshAOS);

    // Initialize AOS after blocks are in place
    AOS.init({
        offset: 0, // Changed from -150 to 0 for more predictable triggering
        duration: 800,
        once: true
    });

    // Global utilities
    initNavbarOverlay();
    initNavbarSearch();

    // Refresh AOS when ANY images are loaded to ensure correct trigger points
    window.addEventListener('load', refreshAOS);

    document.querySelectorAll('img').forEach(img => {
        if (img.complete) {
            refreshAOS();
        } else {
            img.addEventListener("load", refreshAOS);
        }
    });

    // Also refresh after a small delay to catch any late layout shifts from carousels
    setTimeout(refreshAOS, 500);
});

/**
 * Block Dispatcher: Maps ID prefixes/types to initialization functions
 */
const initBlocks = (container = document, refreshFn = null) => {
    const blockRegistry = {
        'hero-carousel': (el) => initHeroCarousel(el, refreshFn),
        'post-type-carousel': (el) => initPostTypeCarousel(el, refreshFn),
        'video-block': initVideoBlock,
        'faq': initFaqBlock,
        'text-media': (el) => {
            initVideoBlock(el);
            initMediaPauseTriggers(el);
        }
    };

    // 1. Primary detection: data-pf-block attribute (most reliable)
    const blocksByAttr = (container === document)
        ? document.querySelectorAll('[data-pf-block]')
        : (container.dataset && container.dataset.pfBlock ? [container] : container.querySelectorAll('[data-pf-block]'));

    blocksByAttr.forEach(el => {
        const type = el.dataset.pfBlock;
        if (blockRegistry[type] && !el.dataset.pfInitialized) {
            blockRegistry[type](el);
            el.dataset.pfInitialized = "true";
        }
    });

    // 2. Secondary detection: ID Prefixes (fallback)
    Object.keys(blockRegistry).forEach(prefix => {
        const selector = `[id^="${prefix}"]`;
        let blocksById = [];

        if (container === document) {
            blocksById = document.querySelectorAll(selector);
        } else {
            if (container.id && container.id.startsWith(prefix)) {
                blocksById = [container];
            } else {
                blocksById = container.querySelectorAll(selector);
            }
        }

        blocksById.forEach(el => {
            if (!el.dataset.pfInitialized) {
                blockRegistry[prefix](el);
                el.dataset.pfInitialized = "true";
            }
        });
    });
};

/**
 * Initialize Hero Carousel Block
 * @param {HTMLElement} element 
 */
const initHeroCarousel = (element, refreshFn = null) => {
    if (typeof Swiper === 'undefined') {
        console.error('Swiper library not loaded for Hero Carousel block.');
        return;
    }

    const swiperContainer = element.querySelector('.js-hero-swiper');

    // Only init if swiper container exists and hasn't been initialized yet
    if (swiperContainer && !swiperContainer.swiper) {
        const swiper = new Swiper(swiperContainer, {
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

        // Refresh AOS after swiper init to account for height changes
        if (refreshFn) refreshFn();
        else if (window.AOS) AOS.refresh();

        // Pause/Play toggle logic
        const pauseBtn = element.querySelector('.js-hero-pause-trigger');
        if (pauseBtn) {
            pauseBtn.addEventListener('click', function () {
                const isPaused = pauseBtn.classList.contains('is-paused');
                if (isPaused) {
                    swiper.autoplay.start();
                    pauseBtn.classList.remove('is-paused');
                    pauseBtn.querySelector('.fa-pause').classList.remove('d-none');
                    pauseBtn.querySelector('.fa-play').classList.add('d-none');
                    pauseBtn.setAttribute('aria-label', 'Pause carousel');
                } else {
                    swiper.autoplay.stop();
                    pauseBtn.classList.add('is-paused');
                    pauseBtn.querySelector('.fa-pause').classList.add('d-none');
                    pauseBtn.querySelector('.fa-play').classList.remove('d-none');
                    pauseBtn.setAttribute('aria-label', 'Play carousel');
                }
            });
        }
    }
};

/**
 * Initialize Post Type Carousel Block
 * @param {HTMLElement} element 
 */
const initPostTypeCarousel = (element, refreshFn = null) => {
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

        // Refresh AOS after swiper init
        if (refreshFn) refreshFn();
        else if (window.AOS) AOS.refresh();
    }
};

/**
 * Initialize Video Block
 * @param {HTMLElement} element 
 */
const initVideoBlock = (element) => {
    // If element is the block itself, use it; otherwise find wrappers inside
    const wrappers = element.classList.contains('js-html5-video-wrapper') ? [element] : element.querySelectorAll('.js-html5-video-wrapper');

    wrappers.forEach(wrapper => {
        const video = wrapper.querySelector('video');
        const playBtn = wrapper.querySelector('.js-play-trigger');
        const block = wrapper.closest('.video-block') || wrapper.closest('.b-text-image');

        if (!video || !playBtn || !block) return;

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

        video.addEventListener('ended', () => {
            block.classList.remove('is-playing');
        });
    });
};

/**
 * Handle Pause/Play for background videos (HTML5, YouTube, Vimeo)
 * @param {HTMLElement} element 
 */
const initMediaPauseTriggers = (element) => {
    // 1. HTML5 Video
    element.querySelectorAll('.js-video-pause-trigger').forEach(btn => {
        btn.addEventListener('click', () => {
            const wrapper = btn.closest('.b-text-image__media-wrapper');
            const video = wrapper.querySelector('video');
            if (!video) return;

            if (video.paused) {
                video.play();
                btn.classList.remove('is-paused');
                btn.querySelector('.fa-pause').classList.remove('d-none');
                btn.querySelector('.fa-play').classList.add('d-none');
                btn.setAttribute('aria-label', 'Pause video');
            } else {
                video.pause();
                btn.classList.add('is-paused');
                btn.querySelector('.fa-pause').classList.add('d-none');
                btn.querySelector('.fa-play').classList.remove('d-none');
                btn.setAttribute('aria-label', 'Play video');
            }
        });
    });

    // 2. YouTube (via PostMessage API)
    element.querySelectorAll('.js-youtube-pause-trigger').forEach(btn => {
        btn.addEventListener('click', () => {
            const wrapper = btn.closest('.b-text-image__media-wrapper');
            const iframe = wrapper.querySelector('.js-youtube-iframe');
            if (!iframe) return;

            const isPaused = btn.classList.contains('is-paused');
            const command = isPaused ? 'playVideo' : 'pauseVideo';

            iframe.contentWindow.postMessage(JSON.stringify({
                event: 'command',
                func: command,
                args: []
            }), '*');

            if (isPaused) {
                btn.classList.remove('is-paused');
                btn.querySelector('.fa-pause').classList.remove('d-none');
                btn.querySelector('.fa-play').classList.add('d-none');
                btn.setAttribute('aria-label', 'Pause video');
            } else {
                btn.classList.add('is-paused');
                btn.querySelector('.fa-pause').classList.add('d-none');
                btn.querySelector('.fa-play').classList.remove('d-none');
                btn.setAttribute('aria-label', 'Play video');
            }
        });
    });

    // 3. Vimeo (via PostMessage API)
    element.querySelectorAll('.js-vimeo-pause-trigger').forEach(btn => {
        btn.addEventListener('click', () => {
            const wrapper = btn.closest('.b-text-image__media-wrapper');
            const iframe = wrapper.querySelector('.js-vimeo-iframe');
            if (!iframe) return;

            const isPaused = btn.classList.contains('is-paused');
            const command = isPaused ? 'play' : 'pause';

            iframe.contentWindow.postMessage(JSON.stringify({
                method: command
            }), '*');

            if (isPaused) {
                btn.classList.remove('is-paused');
                btn.querySelector('.fa-pause').classList.remove('d-none');
                btn.querySelector('.fa-play').classList.add('d-none');
                btn.setAttribute('aria-label', 'Pause video');
            } else {
                btn.classList.add('is-paused');
                btn.querySelector('.fa-pause').classList.add('d-none');
                btn.querySelector('.fa-play').classList.remove('d-none');
                btn.setAttribute('aria-label', 'Play video');
            }
        });
    });
};

/**
 * Initialize Navbar Overlay Logic
 */
const initNavbarOverlay = () => {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;

    const body = document.body;
    let overlay = document.querySelector('.navbar-overlay');

    // Create the overlay element if it doesn't exist
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.classList.add('navbar-overlay');
        document.body.appendChild(overlay);
    }

    // Toggle overlay for Bootstrap Dropdowns (Desktop & Mobile)
    navbar.addEventListener('show.bs.dropdown', () => body.classList.add('has-navbar-overlay'));
    navbar.addEventListener('hide.bs.dropdown', () => {
        setTimeout(() => {
            if (!navbar.querySelector('.dropdown-menu.show')) {
                body.classList.remove('has-navbar-overlay');
            }
        }, 50);
    });

    // Close on overlay click
    overlay.addEventListener('click', () => {
        const openDropdowns = navbar.querySelectorAll('.dropdown-toggle.show');
        openDropdowns.forEach(toggle => {
            const instance = bootstrap.Dropdown.getOrCreateInstance(toggle);
            if (instance) instance.hide();
        });

        // Also close mobile menu if open
        const navbarCollapse = navbar.querySelector('.navbar-collapse.show');
        if (navbarCollapse) {
            const collapseInstance = bootstrap.Collapse.getOrCreateInstance(navbarCollapse);
            if (collapseInstance) collapseInstance.hide();
        }
    });
};

/**
 * Initialize Blocks in ACF Admin Preview
 */
if (window.acf) {
    window.acf.addAction('render_block_preview', function ($block) {
        initBlocks($block[0], () => {
            if (window.AOS) AOS.refresh();
        });

        // Initialize/Refresh global utilities
        if (typeof AOS !== 'undefined') {
            AOS.init();
            AOS.refresh();
        }
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

/**
 * Initialize Navbar Search Logic
 */
const initNavbarSearch = () => {
    const trigger = document.querySelector('.js-search-trigger');
    const searchBar = document.querySelector('.js-navbar-search');
    const closeBtn = document.querySelector('.js-search-close');
    const inputField = document.querySelector('#navbar-search-input');

    if (!trigger || !searchBar) return;

    const openSearch = (e) => {
        if (e) e.preventDefault();
        searchBar.classList.add('active');
        document.body.classList.add('has-search-active');
        setTimeout(() => {
            if (inputField) inputField.focus();
        }, 300);
    };

    const closeSearch = (e) => {
        if (e) e.preventDefault();
        searchBar.classList.remove('active');
        document.body.classList.remove('has-search-active');
        if (inputField) inputField.value = '';
    };

    trigger.addEventListener('click', openSearch);
    if (closeBtn) closeBtn.addEventListener('click', closeSearch);

    // Close on Escape key press
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && searchBar.classList.contains('active')) {
            closeSearch();
        }
    });

    // Close if clicking outside the search bar but not the trigger itself (Desktop & Overlay)
    document.addEventListener('click', (e) => {
        if (searchBar.classList.contains('active') && !searchBar.contains(e.target) && !trigger.contains(e.target)) {
            closeSearch();
        }
    });
};