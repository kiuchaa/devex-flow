<?php
/**
 * WooCommerce Hooks & Customizations
 * 
 * @package pixel-flow
 */

if ( ! class_exists( 'WooCommerce' ) ) {
    return;
}

/**
 * Remove default WooCommerce content wrappers
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Add custom Bootstrap wrappers globally
 */
add_action( 'woocommerce_before_main_content', function() {
    $container_class = ( is_shop() || is_product_category() || is_product_taxonomy() || is_product() ) ? 'container-fluid px-lg-5' : 'container';
    echo '<section class="woocommerce-page-wrapper py-5"><div class="' . esc_attr( $container_class ) . '">';
}, 5 );

/**
 * Step 1: Open the Row and Sidebar
 * Runs AFTER notices (10), result count (20), and ordering (30) to keep them full-width
 */
add_action( 'woocommerce_before_shop_loop', function() {
    if ( is_shop() || is_product_category() || is_product_taxonomy() ) {
        echo '<div class="row shop-main-row mt-4 w-100">';
        echo '<aside class="col-lg-3 shop-sidebar mb-4 mb-lg-0">';

        // Price Filter
        echo '<div class="shop-filter-section p-4 bg-white rounded shadow-sm mb-4">';
        echo '<h4 class="h5 mb-3 fw-bold">Prijs</h4>';
        echo '<form method="get" action="' . esc_url( preg_replace( '%\/page\/[0-9]+\/%', '/', $_SERVER['REQUEST_URI'] ) ) . '">';
        
        foreach ( $_GET as $key => $value ) {
            if ( $key !== 'min_price' && $key !== 'max_price' && $key !== 'paged' ) {
                echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">';
            }
        }

        echo '<div class="price-inputs d-flex align-items-center mb-3">';
        echo '<input type="number" name="min_price" class="form-control form-control-sm me-2" placeholder="Min €" value="' . ( isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '' ) . '">';
        echo '<span class="text-muted">–</span>';
        echo '<input type="number" name="max_price" class="form-control form-control-sm ms-2" placeholder="Max €" value="' . ( isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : '' ) . '">';
        echo '</div>';
        
        echo '<button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">Filteren</button>';
        
        if ( isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) ) {
            $reset_url = esc_url( remove_query_arg( array( 'min_price', 'max_price' ) ) );
            echo '<a href="' . $reset_url . '" class="d-block text-center mt-2 small text-muted text-decoration-none">Filter wissen</a>';
        }
        
        echo '</form>';
        echo '</div>';

        // Render Categories
        $terms = get_terms( array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
        ) );

        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            echo '<div class="shop-filter-section p-4 bg-white rounded shadow-sm mb-4">';
            echo '<h4 class="h5 mb-3 fw-bold">Categorieën</h4>';
            echo '<ul class="list-unstyled mb-0">';

            $shop_url = get_permalink( wc_get_page_id( 'shop' ) );
            $active_class = is_shop() && !is_product_category() ? 'text-primary fw-bold' : 'text-muted';
            echo '<li class="mb-2"><a href="' . esc_url( $shop_url ) . '" class="text-decoration-none ' . $active_class . '">Alle Producten</a></li>';

            foreach ( $terms as $term ) {
                $term_link = get_term_link( $term );
                $is_active = is_product_category( $term->slug );
                $active_class = $is_active ? 'text-primary fw-bold' : 'text-muted';

                echo '<li class="mb-2">';
                echo '<a href="' . esc_url( $term_link ) . '" class="text-decoration-none ' . $active_class . '">';
                echo esc_html( $term->name );
                echo ' <span class="small opacity-50">(' . $term->count . ')</span>';
                echo '</a>';
                echo '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
        
        echo '</aside>';
    }
}, 40 );

/**
 * Step 2: Open the Product Column
 */
add_action( 'woocommerce_before_shop_loop', function() {
    if ( is_shop() || is_product_category() || is_product_taxonomy() ) {
        echo '<main class="col-lg-9 shop-products-column">';
    }
}, 42 );

/**
 * Step 3: Close the Split
 */
add_action( 'woocommerce_after_shop_loop', function() {
    if ( is_shop() || is_product_category() || is_product_taxonomy() ) {
        echo '</main>'; // col-lg-9
        echo '</div>';   // row
    }
}, 20 );

add_action( 'woocommerce_after_main_content', function() {
    echo '</div></section>';
}, 50 );

/**
 * Wrap Cart, Checkout and My Account pages in a container
 * These pages are usually standard WordPress pages using page.php
 */
add_filter( 'the_content', function( $content ) {
    if ( is_cart() || is_checkout() || is_account_page() ) {
        // Only wrap if we are in the main loop to avoid wrapping snippets/widgets
        if ( in_the_loop() && is_main_query() ) {
            return '<div class="container py-5">' . $content . '</div>';
        }
    }
    return $content;
}, 10 );

/**
 * Remove sidebar from WooCommerce pages (optional, but often cleaner)
 */
// remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
