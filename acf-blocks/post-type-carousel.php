<?php
/**
 * Post Type Carousel Block Template.
 *
 * @package pixel-flow
 */

$block_id = 'post-type-carousel-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
}

// Wrapper classes
$wrapper_class = 'post-type-carousel-block js-post-type-carousel bg-black text-white py-5';
if ( ! empty( $block['className'] ) ) {
	$wrapper_class .= ' ' . $block['className'];
}

// ACF Fields
$label     = get_field( 'label' ) ?: 'Agenda'; // "Agenda"
$title     = get_field( 'title' ) ?: 'Aankomende evenementen';
$date_format = get_field('date_format') ?: 'd M Y'; 
$text      = get_field( 'text' );
$cta_link  = get_field( 'cta_link' );
$post_type = get_field( 'post_type' ) ?: 'post';
$limit     = get_field( 'limit' ) ?: 6;

// Validate limit
if ( $limit > 10 ) {
	$limit = 10;
}

$post_term = get_post_type_object($post_type);

// Query
$args = [
	'post_type'      => $post_type,
	'posts_per_page' => $limit,
	'post_status'    => 'publish',
];
$query = new WP_Query( $args );
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $wrapper_class ); ?>">
	<div class="container">
		<div class="row align-items-end mb-5">
			<div class="col-lg-8">
				<?php if ( $label ) : ?>
					<h6 class="text-uppercase fw-bold mb-3" style="font-size: 0.9rem; letter-spacing: 1px; color: #fff; opacity: 0.8;">
						<?php echo esc_html( $label ); ?>
					</h6>
				<?php endif; ?>

				<?php if ( $title ) : ?>
					<h2 class="display-5 fw-bold mb-3">
						<?php echo esc_html( $title ); ?>
					</h2>
				<?php endif; ?>

				<?php if ( $text ) : ?>
					<p class="lead mb-0 text-white-50" style="max-width: 600px;">
						<?php echo html_entity_decode( $text ); ?>
					</p>
				<?php endif; ?>
			</div>
			
			<div class="col-lg-4 d-flex justify-content-lg-end mt-4 mt-lg-0 align-items-center gap-3">
				<?php if ( $cta_link ) :
                    get_template_part('components/button', null, [
                            'link' => [
                                    'url' => esc_url( $cta_link['url'] ),
                                    'title' => esc_html( $cta_link['title']),
                                    'target' => '_self'
                            ],
                            'variant' => 'secondary',
                            'class' => 'px-4 py-2 fw-medium'
                    ]);
                endif; ?>
			</div>
		</div>

		<!-- Carousel -->
		<div class="position-relative">
			<?php if ( $query->have_posts() ) : ?>
				<div class="swiper js-post-type-swiper postTypeSwiper-<?php echo esc_attr( $block['id'] ); ?> overflow-hidden">
					<div class="swiper-wrapper">
						<?php while ( $query->have_posts() ) : $query->the_post(); 
							
							$thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
							$categories = get_the_terms( get_the_ID(), 'category' );
							$cat_name = !empty($categories) ? $categories[0]->name : '';
							$location = get_field('location'); // Example meta
						?>
							<div class="swiper-slide h-auto">
								<div class="card h-100 bg-transparent border-0 text-white">
									<!-- Image Wrapper -->
									<div class="position-relative rounded-3 overflow-hidden mb-4" style="aspect-ratio: 4/3;">
										<?php if ( $thumb_url ) : ?>
											<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php the_title_attribute(); ?>" class="w-100 h-100 object-fit-cover transition-transform duration-500 hover-scale">
										<?php else : ?>
											<div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center">
												<span class="text-white-50"><i class="fa-solid fa-image fa-2xl"></i></span>
											</div>
										<?php endif; ?>
									</div>

									<!-- Content -->
									<div class="card-body p-0">
										<?php if ( $cat_name ) : ?>
											<div class="mb-2">
												<span class="badge bg-dark border border-secondary fw-normal px-3 py-2 rounded-2"><?php echo esc_html( $cat_name ); ?></span>
											</div>
										<?php endif; ?>

										<h4 class="card-title fw-bold mb-2">
											<a href="<?php the_permalink(); ?>" class="text-white text-decoration-none stretched-link">
												<?php the_title(); ?>
											</a>
										</h4>
										
										<?php if($location): ?>
											<div class="text-white-50 mb-3 small"><?php echo esc_html($location); ?></div>
										<?php endif; ?>

										<div class="card-text text-white-50 mb-4 clamp-2">
											<?php echo wp_trim_words( get_the_excerpt(), 15 ); ?>
										</div>

										<div class="d-flex align-items-center text-white fw-bold hover-translate">
											Bekijk <?= $post_term->labels->singular_name; ?>
											<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right ms-2" viewBox="0 0 16 16">
												<path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
											</svg>
										</div>
									</div>
								</div>
							</div>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
				</div>
                
                <!-- Navigation Button Wrapper -->
                <!-- Mobile: Flex container below swiper / in flow. Desktop: Absolute positioned buttons via CSS. -->
                <div class="swiper-nav-wrapper d-flex justify-content-end gap-3 mt-4 mt-lg-0">
                     <div class="swiper-button-prev js-swiper-button-prev swiper-button-prev-<?php echo esc_attr( $block['id'] ); ?> text-white"></div>
                     <div class="swiper-button-next js-swiper-button-next swiper-button-next-<?php echo esc_attr( $block['id'] ); ?> text-white"></div>
                </div>

			<?php else : ?>
				<div class="alert alert-info bg-transparent border-secondary text-white-50">
					Geen records gevonden.
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<style>
    /* Generics for nav buttons */
    #<?php echo esc_attr( $block_id ); ?> .swiper-button-prev,
    #<?php echo esc_attr( $block_id ); ?> .swiper-button-next {
        width: 44px;
        height: 44px;
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        background-color: rgba(0,0,0,0.5); /* contrast */
        cursor: pointer;
        z-index: 10;
        position: static; /* Default mobile: static in flow */
    }
    #<?php echo esc_attr( $block_id ); ?> .swiper-button-prev:after,
    #<?php echo esc_attr( $block_id ); ?> .swiper-button-next:after {
        font-size: 16px;
        font-weight: bold;
    }
    #<?php echo esc_attr( $block_id ); ?> .swiper-button-prev:hover,
    #<?php echo esc_attr( $block_id ); ?> .swiper-button-next:hover {
        background: #fff;
        color: #000 !important;
        border-color: #fff;
    }
    
    /* Desktop positioning */
    @media (min-width: 992px) {
        #<?php echo esc_attr( $block_id ); ?> .swiper-button-prev,
        #<?php echo esc_attr( $block_id ); ?> .swiper-button-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            margin-top: 0;
        }
    
        #<?php echo esc_attr( $block_id ); ?> .swiper-button-prev {
            left: -60px;
        }
        #<?php echo esc_attr( $block_id ); ?> .swiper-button-next {
            right: -60px;
        }
        
        /* Hide the wrapper margin/flow on desktop since buttons are absolute */
        #<?php echo esc_attr( $block_id ); ?> .swiper-nav-wrapper {
             margin-top: 0 !important;
             /* Ensure wrapper doesn't block clicks if it overlays anything, though it's div */
             pointer-events: none; 
        }
        #<?php echo esc_attr( $block_id ); ?> .swiper-button-prev,
        #<?php echo esc_attr( $block_id ); ?> .swiper-button-next {
             pointer-events: auto;
        }
    }
</style>
