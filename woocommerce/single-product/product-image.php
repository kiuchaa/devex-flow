<?php
/**
 * Custom Single Product Image Gallery
 *
 * Replaces the default WooCommerce product gallery with a custom
 * implementation that supports:
 * - Single image display
 * - Multi-image gallery with thumbnail strip
 * - Lightbox with keyboard & swipe navigation
 * - Zoom on hover (desktop)
 *
 * @package pixel-flow
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product ) {
    return;
}

// Gather all gallery images
$gallery_image_ids = $product->get_gallery_image_ids();
$featured_image_id = $product->get_image_id();

// Build the full image array (featured first, then gallery)
$all_image_ids = [];
if ( $featured_image_id ) {
    $all_image_ids[] = $featured_image_id;
}
if ( ! empty( $gallery_image_ids ) ) {
    $all_image_ids = array_merge( $all_image_ids, $gallery_image_ids );
}

// Fallback: no images at all
if ( empty( $all_image_ids ) ) {
    $all_image_ids[] = 0; // Will trigger placeholder
}

$has_multiple = count( $all_image_ids ) > 1;
$total_images = count( $all_image_ids );
?>

<div class="pf-product-gallery <?php echo $has_multiple ? 'pf-product-gallery--multi' : 'pf-product-gallery--single'; ?>" data-total="<?php echo esc_attr( $total_images ); ?>">

    <?php
    // Sale badge
    if ( $product->is_on_sale() ) : ?>
        <span class="pf-product-gallery__badge">
            <?php
            if ( $product->is_type( 'variable' ) ) {
                echo esc_html__( 'Sale!', 'pixel-flow' );
            } else {
                $regular = (float) $product->get_regular_price();
                $sale    = (float) $product->get_sale_price();
                if ( $regular > 0 ) {
                    $percentage = round( ( ( $regular - $sale ) / $regular ) * 100 );
                    echo '-' . $percentage . '%';
                } else {
                    echo esc_html__( 'Sale!', 'pixel-flow' );
                }
            }
            ?>
        </span>
    <?php endif; ?>

    <!-- Main Image Viewport -->
    <div class="pf-product-gallery__main">
        <div class="pf-product-gallery__zoom-container" id="pf-zoom-container">
            <?php
            if ( $all_image_ids[0] === 0 ) {
                echo wc_placeholder_img( 'woocommerce_single' );
            } else {
                $full_src = wp_get_attachment_image_url( $all_image_ids[0], 'full' );
                $large_src = wp_get_attachment_image_url( $all_image_ids[0], 'woocommerce_single' );
                $alt = get_post_meta( $all_image_ids[0], '_wp_attachment_image_alt', true ) ?: $product->get_name();
                ?>
                <img
                    src="<?php echo esc_url( $large_src ); ?>"
                    data-full-src="<?php echo esc_url( $full_src ); ?>"
                    alt="<?php echo esc_attr( $alt ); ?>"
                    class="pf-product-gallery__main-image"
                    id="pf-main-image"
                    loading="eager"
                    decoding="async"
                />
            <?php } ?>
        </div>

        <?php if ( $has_multiple ) : ?>
            <!-- Navigation arrows on the main image -->
            <button class="pf-product-gallery__nav pf-product-gallery__nav--prev" aria-label="<?php esc_attr_e( 'Vorige afbeelding', 'pixel-flow' ); ?>" id="pf-nav-prev">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button class="pf-product-gallery__nav pf-product-gallery__nav--next" aria-label="<?php esc_attr_e( 'Volgende afbeelding', 'pixel-flow' ); ?>" id="pf-nav-next">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        <?php endif; ?>

        <!-- Lightbox trigger -->
        <button class="pf-product-gallery__expand" aria-label="<?php esc_attr_e( 'Vergroot afbeelding', 'pixel-flow' ); ?>" id="pf-expand-btn">
            <i class="fa-solid fa-expand"></i>
        </button>

        <?php if ( $has_multiple ) : ?>
            <!-- Image counter -->
            <span class="pf-product-gallery__counter">
                <span id="pf-counter-current">1</span> / <?php echo esc_html( $total_images ); ?>
            </span>
        <?php endif; ?>
    </div>

    <?php if ( $has_multiple ) : ?>
        <!-- Thumbnail Strip -->
        <div class="pf-product-gallery__thumbs" id="pf-thumbs">
            <?php foreach ( $all_image_ids as $index => $image_id ) :
                $thumb_src = wp_get_attachment_image_url( $image_id, 'thumbnail' );
                $large_src = wp_get_attachment_image_url( $image_id, 'woocommerce_single' );
                $full_src  = wp_get_attachment_image_url( $image_id, 'full' );
                $alt       = get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ?: $product->get_name();
                ?>
                <button
                    class="pf-product-gallery__thumb <?php echo $index === 0 ? 'pf-product-gallery__thumb--active' : ''; ?>"
                    data-index="<?php echo esc_attr( $index ); ?>"
                    data-large-src="<?php echo esc_url( $large_src ); ?>"
                    data-full-src="<?php echo esc_url( $full_src ); ?>"
                    aria-label="<?php echo esc_attr( sprintf( __( 'Afbeelding %d van %d', 'pixel-flow' ), $index + 1, $total_images ) ); ?>"
                >
                    <img src="<?php echo esc_url( $thumb_src ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy" decoding="async" />
                </button>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Lightbox Overlay -->
    <div class="pf-lightbox" id="pf-lightbox" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Afbeelding galerij', 'pixel-flow' ); ?>">
        <div class="pf-lightbox__backdrop"></div>

        <button class="pf-lightbox__close" aria-label="<?php esc_attr_e( 'Sluiten', 'pixel-flow' ); ?>" id="pf-lightbox-close">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <?php if ( $has_multiple ) : ?>
            <button class="pf-lightbox__nav pf-lightbox__nav--prev" aria-label="<?php esc_attr_e( 'Vorige', 'pixel-flow' ); ?>" id="pf-lightbox-prev">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button class="pf-lightbox__nav pf-lightbox__nav--next" aria-label="<?php esc_attr_e( 'Volgende', 'pixel-flow' ); ?>" id="pf-lightbox-next">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        <?php endif; ?>

        <div class="pf-lightbox__content" id="pf-lightbox-content">
            <img class="pf-lightbox__image" id="pf-lightbox-image" src="" alt="" />
        </div>

        <?php if ( $has_multiple ) : ?>
            <div class="pf-lightbox__counter">
                <span id="pf-lightbox-counter-current">1</span> / <?php echo esc_html( $total_images ); ?>
            </div>

            <!-- Lightbox Thumbnail Strip -->
            <div class="pf-lightbox__thumbs" id="pf-lightbox-thumbs">
                <?php foreach ( $all_image_ids as $index => $image_id ) :
                    $thumb_src = wp_get_attachment_image_url( $image_id, 'thumbnail' );
                    $full_src  = wp_get_attachment_image_url( $image_id, 'full' );
                    $alt       = get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ?: $product->get_name();
                    ?>
                    <button
                        class="pf-lightbox__thumb <?php echo $index === 0 ? 'pf-lightbox__thumb--active' : ''; ?>"
                        data-index="<?php echo esc_attr( $index ); ?>"
                        data-full-src="<?php echo esc_url( $full_src ); ?>"
                    >
                        <img src="<?php echo esc_url( $thumb_src ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy" decoding="async" />
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<script>
(function() {
    'use strict';

    const gallery = document.querySelector('.pf-product-gallery');
    if (!gallery) return;

    const totalImages   = parseInt(gallery.dataset.total, 10);
    const mainImage     = document.getElementById('pf-main-image');
    const zoomContainer = document.getElementById('pf-zoom-container');
    const expandBtn     = document.getElementById('pf-expand-btn');
    const thumbs        = document.querySelectorAll('.pf-product-gallery__thumb');
    const counterEl     = document.getElementById('pf-counter-current');

    // Lightbox elements
    const lightbox      = document.getElementById('pf-lightbox');
    const lbImage       = document.getElementById('pf-lightbox-image');
    const lbClose       = document.getElementById('pf-lightbox-close');
    const lbPrev        = document.getElementById('pf-lightbox-prev');
    const lbNext        = document.getElementById('pf-lightbox-next');
    const lbCounterEl   = document.getElementById('pf-lightbox-counter-current');
    const lbThumbs      = document.querySelectorAll('.pf-lightbox__thumb');

    // Gallery nav
    const navPrev       = document.getElementById('pf-nav-prev');
    const navNext       = document.getElementById('pf-nav-next');

    let currentIndex = 0;

    // Move lightbox to body so it covers the full viewport
    // (escapes any parent overflow:hidden or transform stacking contexts)
    if (lightbox) {
        document.body.appendChild(lightbox);
    }

    // Collect all image data from thumbnails
    const images = [];
    if (thumbs.length > 0) {
        thumbs.forEach(function(thumb) {
            images.push({
                large: thumb.dataset.largeSrc,
                full:  thumb.dataset.fullSrc
            });
        });
    } else if (mainImage) {
        images.push({
            large: mainImage.src,
            full:  mainImage.dataset.fullSrc || mainImage.src
        });
    }

    // ─── Gallery Navigation ───────────────────────────────────
    function goToImage(index) {
        if (index < 0) index = totalImages - 1;
        if (index >= totalImages) index = 0;
        currentIndex = index;

        if (mainImage && images[index]) {
            mainImage.style.opacity = '0';
            setTimeout(function() {
                mainImage.src = images[index].large;
                mainImage.dataset.fullSrc = images[index].full;
                mainImage.style.opacity = '1';
            }, 200);
        }

        // Update active thumbnail
        thumbs.forEach(function(t) { t.classList.remove('pf-product-gallery__thumb--active'); });
        if (thumbs[index]) thumbs[index].classList.add('pf-product-gallery__thumb--active');

        // Scroll active thumb into view
        if (thumbs[index]) {
            thumbs[index].scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        }

        // Update counter
        if (counterEl) counterEl.textContent = index + 1;
    }

    // Thumbnail click
    thumbs.forEach(function(thumb) {
        thumb.addEventListener('click', function() {
            goToImage(parseInt(this.dataset.index, 10));
        });
    });

    // Arrow nav
    if (navPrev) navPrev.addEventListener('click', function() { goToImage(currentIndex - 1); });
    if (navNext) navNext.addEventListener('click', function() { goToImage(currentIndex + 1); });

    // ─── Hover Zoom (Desktop) ─────────────────────────────────
    if (zoomContainer && mainImage && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
        zoomContainer.addEventListener('mousemove', function(e) {
            const rect = zoomContainer.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            mainImage.style.transformOrigin = x + '% ' + y + '%';
            mainImage.style.transform = 'scale(2)';
        });

        zoomContainer.addEventListener('mouseleave', function() {
            mainImage.style.transform = 'scale(1)';
            mainImage.style.transformOrigin = 'center center';
        });
    }

    // ─── Lightbox ─────────────────────────────────────────────
    function openLightbox(index) {
        if (typeof index === 'number') currentIndex = index;
        lightbox.classList.add('pf-lightbox--open');
        document.body.style.overflow = 'hidden';
        updateLightboxImage(currentIndex);
    }

    function closeLightbox() {
        lightbox.classList.remove('pf-lightbox--open');
        document.body.style.overflow = '';
    }

    function updateLightboxImage(index) {
        if (index < 0) index = totalImages - 1;
        if (index >= totalImages) index = 0;
        currentIndex = index;

        if (lbImage && images[index]) {
            lbImage.style.opacity = '0';
            lbImage.src = images[index].full;
            lbImage.onload = function() {
                lbImage.style.opacity = '1';
            };
        }

        // Update lightbox thumbnails
        lbThumbs.forEach(function(t) { t.classList.remove('pf-lightbox__thumb--active'); });
        if (lbThumbs[index]) {
            lbThumbs[index].classList.add('pf-lightbox__thumb--active');
            lbThumbs[index].scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        }

        if (lbCounterEl) lbCounterEl.textContent = index + 1;

        // Sync the main gallery too
        goToImage(index);
    }

    // Open lightbox
    if (expandBtn) expandBtn.addEventListener('click', function() { openLightbox(currentIndex); });
    if (mainImage) mainImage.addEventListener('click', function() { openLightbox(currentIndex); });

    // Close lightbox
    if (lbClose) lbClose.addEventListener('click', closeLightbox);
    lightbox.querySelector('.pf-lightbox__backdrop').addEventListener('click', closeLightbox);

    // Lightbox nav
    if (lbPrev) lbPrev.addEventListener('click', function(e) { e.stopPropagation(); updateLightboxImage(currentIndex - 1); });
    if (lbNext) lbNext.addEventListener('click', function(e) { e.stopPropagation(); updateLightboxImage(currentIndex + 1); });

    // Lightbox thumbnail click
    lbThumbs.forEach(function(thumb) {
        thumb.addEventListener('click', function() {
            updateLightboxImage(parseInt(this.dataset.index, 10));
        });
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!lightbox.classList.contains('pf-lightbox--open')) return;

        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') updateLightboxImage(currentIndex - 1);
        if (e.key === 'ArrowRight') updateLightboxImage(currentIndex + 1);
    });

    // Touch / Swipe support for lightbox
    let touchStartX = 0;
    let touchEndX = 0;
    const lbContent = document.getElementById('pf-lightbox-content');

    if (lbContent) {
        lbContent.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        lbContent.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchStartX - touchEndX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) {
                    updateLightboxImage(currentIndex + 1);
                } else {
                    updateLightboxImage(currentIndex - 1);
                }
            }
        }, { passive: true });
    }

    // Touch / Swipe support for main gallery
    if (zoomContainer) {
        let galleryTouchStartX = 0;
        zoomContainer.addEventListener('touchstart', function(e) {
            galleryTouchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        zoomContainer.addEventListener('touchend', function(e) {
            const galleryTouchEndX = e.changedTouches[0].screenX;
            const diff = galleryTouchStartX - galleryTouchEndX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) {
                    goToImage(currentIndex + 1);
                } else {
                    goToImage(currentIndex - 1);
                }
            }
        }, { passive: true });
    }

})();
</script>
