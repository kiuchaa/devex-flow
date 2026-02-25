<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'single-product-custom-layout', $product ); ?>>

    <div class="row g-lg-5 gy-5">
        <div class="col-lg-6" data-aos="fade-right">
            <div class="product-gallery-wrapper position-relative w-100">
                <?php
                /**
                 * Custom product gallery with zoom, lightbox and thumbnails.
                 * Replaces default woocommerce_before_single_product_summary hooks.
                 */
                wc_get_template( 'single-product/product-image.php' );
                ?>
            </div>
        </div>

        <div class="col-lg-6" data-aos="fade-left">
            <div class="summary entry-summary product-info-wrapper sticky-lg-top w-100" style="z-index: 10;">
                <?php
                /**
                 * Hook: woocommerce_single_product_summary.
                 *
                 * @hooked woocommerce_template_single_title - 5
                 * @hooked woocommerce_template_single_rating - 10
                 * @hooked woocommerce_template_single_price - 10
                 * @hooked woocommerce_template_single_excerpt - 20
                 * @hooked woocommerce_template_single_add_to_cart - 30
                 * @hooked woocommerce_template_single_meta - 40
                 * @hooked woocommerce_template_single_sharing - 50
                 * @hooked WC_Structured_Data::generate_product_data() - 60
                 */
                do_action( 'woocommerce_single_product_summary' );
                ?>

                <!-- Modern Product features section -->
                <div class="product-benefits mt-5" data-aos="fade-up" data-aos-delay="200">
                    <div class="benefit-item d-flex align-items-center mb-3">
                        <div class="benefit-icon me-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-truck-fast text-primary"></i>
                        </div>
                        <div class="benefit-text">
                            <span class="d-block fw-bold small">Gratis verzending</span>
                            <span class="d-block text-muted x-small">Vanaf €50,- besteding</span>
                        </div>
                    </div>
                    <div class="benefit-item d-flex align-items-center mb-3">
                        <div class="benefit-icon me-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-rotate-left text-primary"></i>
                        </div>
                        <div class="benefit-text">
                            <span class="d-block fw-bold small">30 dagen bedenktijd</span>
                            <span class="d-block text-muted x-small">Niet goed, geld terug</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
