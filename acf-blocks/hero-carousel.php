<?php
/**
 * Hero Carousel Block Template (Full Width Background Version).
 *
 * @package pixel-flow
 */

// Block ID
$block_id = 'hero-carousel-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
    $block_id = $block['anchor'];
}

// Load ACF fields
$title    = get_field( 'title' );
$text     = get_field( 'text' );
$button1  = get_field( 'button_1' );
$button2  = get_field( 'button_2' );
$gallery  = get_field( 'gallery' );

// Wrapper classes
$wrapper_class = 'hero-carousel-block b-hero-carousel position-relative overflow-hidden';
if ( ! empty( $block['className'] ) ) {
    $wrapper_class .= ' ' . $block['className'];
}
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $wrapper_class ); ?>" style="min-height: 80vh; display: flex; align-items: center;">
    
    <!-- Background Swiper -->
    <div class="swiper heroCarouselSwiper-<?php echo esc_attr( $block['id'] ); ?> position-absolute top-0 start-0 w-100 h-100" style="z-index: 1;">
        <div class="swiper-wrapper">
            <?php if ( $gallery ) : ?>
                <?php foreach ( $gallery as $image ) : ?>
                    <div class="swiper-slide overflow-hidden">
                        <div class="w-100 h-100 bg-dark position-absolute top-0 start-0" style="opacity: 0.5; z-index: 2;"></div>
                        <img src="<?php echo esc_url( $image['url'] ); ?>"
                             alt="<?php echo esc_attr( $image['alt'] ); ?>"
                             class="w-100 h-100 object-fit-cover"/>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Content Overlay -->
    <div class="container position-relative py-5" style="z-index: 10;">
        <div class="row">
            <div class="col-lg-8 col-xl-7">
                <div class="hero-content text-white">
                    <?php if ( $title ) : ?>
                        <h1 class="display-2 fw-bolder mb-4 ls-tight" data-aos="fade-up" data-aos-duration="1500">
                            <?php echo esc_html( $title ); ?>
                        </h1>
                    <?php endif; ?>

                    <?php if ( $text ) : ?>
                        <div class="lead mb-5 pe-lg-5" style="max-width: 600px; font-weight: 400; line-height: 1.6; opacity: 0.9;" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="250">
                            <?php echo wp_kses_post( $text ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex flex-wrap gap-3" data-aos="fade-in" data-aos-duration="750" data-aos-delay="350">
                        <?php 
                        if ( $button1 ) {
                            get_template_part( 'components/button', null, [
                                'link'    => $button1,
                                'variant' => 'primary',
                            ] );
                        }

                        if ( $button2 ) {
                            get_template_part( 'components/button', null, [
                                'link'    => $button2,
                                'variant' => 'secondary',
                            ] );
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Swiper Specific Navigation (Optional for background sliders) -->
    <div class="swiper-pagination swiper-pagination-white position-absolute bottom-0 mb-4" style="z-index: 11;"></div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Swiper !== 'undefined') {
            const swiper = new Swiper('.heroCarouselSwiper-<?php echo esc_attr( $block['id'] ); ?>', {
                loop: true,
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        }
    });
</script>
