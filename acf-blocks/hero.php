<?php
/**
 * Header Hero Block Template.
 *
 * @package pixel-flow
 */

// Block ID
$block_id = 'header-hero-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
    $block_id = $block['anchor'];
}

// Load ACF fields
$slogan = get_field( 'slogan' );
$title  = get_field( 'title' );
$text   = get_field( 'text' );
$button           = get_field( 'button' );
$secondary_button = get_field( 'secondary_button' );
$image            = get_field( 'image' );

// Wrapper classes
$wrapper_class = 'header-hero py-5 overflow-hidden';
if ( ! empty( $block['className'] ) ) {
    $wrapper_class .= ' ' . $block['className'];
}
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $wrapper_class ); ?> header-hero-component bg-white">
    <div class="container">
        <div class="row align-items-center gy-5">

            <!-- Content Column -->
            <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column align-items-start">
                <?php if ( $title ) : ?>
                    <h1 class="display-3 fw-bolder mb-4 ls-tight" data-aos="fade-up" data-aos-duration="1500">
                        <?php echo esc_html( $title ); ?>
                    </h1>
                <?php endif; ?>

                <?php if ( $text ) : ?>
                    <div class="lead text-secondary mb-5 pe-lg-5" style="max-width: 550px; font-weight: 400; line-height: 1.6;" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="250">
                        <?php echo wp_kses_post( $text ); ?>
                    </div>
                <?php endif; ?>

                <div class="d-flex flex-wrap gap-3" data-aos="fade-in" data-aos-duration="750" data-aos-delay="350">
                    <?php 
                    if ( $button ) {
                        get_template_part( 'components/button', null, [
                            'link'    => $button,
                            'variant' => 'primary',
                        ] );
                    }

                    if ( $secondary_button ) {
                        get_template_part( 'components/button', null, [
                            'link'    => $secondary_button,
                            'variant' => 'secondary',
                        ] );
                    }
                    ?>
                </div>
            </div>

            <!-- Image Column -->
            <div class="col-lg-6 order-1 order-lg-2 text-center text-lg-end">
                <?php if ( $image ) : ?>
                    <div class="position-relative d-inline-block" data-aos="fade-in" data-aos-duration="2000" data-aos-delay="550">
                        <img src="<?php echo esc_url( $image['url'] ); ?>"
                             alt="<?php echo esc_attr( $image['alt'] ); ?>"
                             class="img-fluid object-fit-cover header-hero-image"/>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>
