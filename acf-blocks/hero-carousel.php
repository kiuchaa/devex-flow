<?php
/**
 * Hero Carousel Block Template.
 * Supports 'default' (Background) and 'image' (Split Content) variants.
 *
 * @package pixel-flow
 */

// Inside your block template file (e.g., blocks/my-block.php)
$block_title = $block['title'];

// Block ID
$block_id = 'hero-carousel-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
    $block_id = $block['anchor'];
}

// Load ACF fields

$content = get_field('content');

$title    = $content['title'];
$text     = $content['text'];
$button1  = $content['button_1'];
$button2  = $content['button_2'];
$gallery  = $content['gallery'];


$options = get_field('options');

$overlay_color = $options['overlay_kleur'];
$order = $options['volgorde'];
$variant = $options['variant'];

$is_split = ( $variant === 'image' );

// Wrapper classes
$wrapper_class = 'hero-carousel-block js-hero-carousel b-hero-carousel position-relative overflow-hidden';
if ( ! empty( $block['className'] ) ) {
    $wrapper_class .= ' ' . $block['className'];
}

// Apply variant specific classes
if ( $is_split ) {
    // Mimic hero.php classes
    $wrapper_class .= ' header-hero-component bg-white py-5';
} else {
    // Default / bg-image
    $wrapper_class .= ' split-content';
    if ($overlay_color == 'wit' ) {
        $wrapper_class .= ' white';
    }
    if ($order == 'omgedraaid') {
        $wrapper_class .= ' reverse';
    }
}

?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $wrapper_class ); ?>" style="<?php echo $is_split ? '' : 'min-height: 80vh; display: flex; align-items: center;'; ?>">

    <?php if ( $is_split ) : ?>
        <!-- ============================================= -->
        <!-- VARIANT: IMAGE (Split Content like Hero)      -->
        <!-- ============================================= -->
        <div class="container">
            <div class="row align-items-center gy-5">
                <?php
                // Determine column ordering
                $col_content = 'col-lg-6 order-2 order-lg-1';
                $col_image   = 'col-lg-6 order-1 order-lg-2';

                // In hero.php image is text-center text-lg-end.
                $col_image_classes = $col_image . ' text-center text-lg-end';

                if ( $order === 'omgedraaid' ) {
                    $col_content = 'col-lg-6 order-2 order-lg-2';
                    $col_image   = 'col-lg-6 order-1 order-lg-1';
                    $col_image_classes = $col_image . ' text-center text-lg-start';
                }
                ?>

                <!-- Content Column -->
                <div class="<?php echo esc_attr( $col_content ); ?> d-flex flex-column align-items-start">
                    <?php if ( $title ) : ?>
                        <h1 class="display-3 fw-bolder mb-4 ls-tight text-dark" data-aos="fade-up" data-aos-duration="1500">
                            <?php echo esc_html( $title ); ?>
                        </h1>
                    <?php endif; ?>

                    <?php if ( $text ) : ?>
                        <div class="lead text-secondary mb-5 pe-lg-5" style="max-width: 550px; font-weight: 400; line-height: 1.6;" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="250">
                            <?php echo wp_kses_post( $text ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex flex-wrap gap-3" data-aos="fade-in" data-aos-duration="750" data-aos-delay="500">
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

                <!-- Carousel Column -->
                <div class="<?php echo esc_attr( $col_image_classes ); ?>">
                    <?php if ( $gallery ) : ?>
                         <div class="position-relative d-inline-block w-100 rounded-3 overflow-hidden" 
                             style="max-width: 500px; aspect-ratio: 3/4; box-shadow: 0 10px 30px rgba(0,0,0,0.05);" 
                             data-aos="fade-in" data-aos-duration="2000" data-aos-delay="550">
                            <div class="swiper js-hero-swiper heroCarouselSwiper-<?php echo esc_attr( $block['id'] ); ?> w-100 h-100">
                                <div class="swiper-wrapper">
                                    <?php foreach ( $gallery as $image ) : ?>
                                        <div class="swiper-slide overflow-hidden">
                                            <img src="<?php echo esc_url( $image['url'] ); ?>"
                                                 alt="<?php echo esc_attr( $image['alt'] ); ?>"
                                                 class="w-100 h-100 object-fit-cover"/>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="swiper-pagination swiper-pagination-white position-absolute bottom-0 mb-3" style="z-index: 10;"></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>

    <?php else : ?>
        <!-- ============================================= -->
        <!-- VARIANT: BACKGROUND-IMAGE (Fullscreen)        -->
        <!-- ============================================= -->
        
        <?php if ( $gallery) : ?>
            <div class="swiper js-hero-swiper heroCarouselSwiper-<?php echo esc_attr( $block['id'] ); ?> position-absolute top-0 start-0 w-100 h-100" style="z-index: 1;">
                <div class="swiper-wrapper">
                    <?php foreach ( $gallery as $image ) : ?>
                        <div class="swiper-slide overflow-hidden">
                            <div class="w-100 h-100 bg-dark position-absolute top-0 start-0" style="opacity: 0.5; z-index: 2;"></div>
                            <img src="<?php echo esc_url( $image['url'] ); ?>"
                                 alt="<?php echo esc_attr( $image['alt'] ); ?>"
                                 class="w-100 h-100 object-fit-cover"/>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Content Overlay -->
        <div class="container position-relative py-5" style="z-index: 10;">
            <div class="row">
                <div class="col-lg-8 col-xl-7 <?php echo $order == 'omgedraaid' ? 'ms-auto order-last' : 'order-first'; ?>">
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

                        <div class="d-flex flex-wrap gap-3" data-aos="fade-in" data-aos-duration="750" data-aos-delay="500">
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

    <?php endif; ?>

</section>
