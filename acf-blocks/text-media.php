<?php
/**
 * Text & Image Block Template.
 *
 * @package pixel-flow
 */

$block_id = 'text-image-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
}

$content = get_field('inhoud');
$options = get_field('opties');

// loading options
$orientation = $options['orientation'] ?: 'left';
$media_type = $options['beeldmateriaal_opties'];
$background_color = $options['achtergrondkleur'] ?: 'white';
$accent_color = $options['accent_kleur'] ?: 'primary';

// loading content
$title       = $content['title'];
$text        = $content['text'];
$button      = $content['button'];
$image       = $content['image'];
$video       = $content['video'];
$youtube     = $content['youtube'];
$vimeo       = $content['vimeo'];

// returns 'auto' and 'no-auto'
$autoplay    = $options['autoplay'] ?: 'auto';
$is_autoplay = ( $autoplay === 'auto' );

// $content['youtube'] and $content['vimeo'] will provide a full youtube url - embed that
// $content['video'] wll provide either a mp4 or mov file - show that in a video tag
$media       = $content[$media_type];

// Wrapper classes
$wrapper_class = 'b-text-image ' . ( $orientation === 'right' ? 'image-right' : 'image-left' ) . ' bg-' . $background_color;
if ( ! empty( $block['className'] ) ) {
	$wrapper_class .= ' ' . $block['className'];
}

// Column ordering logic
// Always show image first on mobile (order-1), but swap on desktop (order-lg-1 / order-lg-2)
$content_order = ( $orientation === 'right' ) ? 'order-2 order-lg-1' : 'order-2 order-lg-2';
$image_order   = ( $orientation === 'right' ) ? 'order-1 order-lg-2' : 'order-1 order-lg-1';
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $wrapper_class ); ?>" data-pf-block="text-image">
	<div class="container">
		<div class="row align-items-center gy-5 gx-0 gx-md-5">
			<div class="col-lg-6 <?php echo esc_attr( $content_order ); ?>">
				<div class="b-text-image__content">
					<?php if ( $title ) : ?>
						<h2 class="display-5 fw-bolder mb-4 ls-tight" 
                            data-aos="fade-up" 
                            data-aos-duration="600"
                            data-aos-once="true"
                            data-aos-anchor="#<?php echo esc_attr( $block_id ); ?>">
							<?php echo esc_html( $title ); ?>
						</h2>
					<?php endif; ?>

					<?php if ( $text ) : ?>
						<div class="mb-4" 
                            data-aos="fade-up" 
                            data-aos-delay="150" 
                            data-aos-duration="600"
                            data-aos-once="true"
                            data-aos-anchor="#<?php echo esc_attr( $block_id ); ?>">
							<?php echo wp_kses_post( $text ); ?>
						</div>
					<?php endif; ?>

					<?php if ( $button ) : ?>
						<div class="b-text-image__button" 
                            data-aos="fade-up" 
                            data-aos-delay="300" 
                            data-aos-once="true"
                            data-aos-anchor="#<?php echo esc_attr( $block_id ); ?>">
							<?php
							get_template_part( 'components/button', null, [
								'link'    => $button,
								'variant' => $accent_color,
                                'size' => 'btn-lg'
							] );
							?>
						</div>
					<?php endif; ?>
				</div>
			</div>

            <?php
            // Aspect Ratio Mapping
            $ratio_map = [
                '16 bij 9' => 'ratio-16x9',
                '1 bij 1'  => 'ratio-1x1',
                '4 bij 3'  => 'ratio-4x3',
            ];
            $ratio_class = isset( $options['aspect_ratio'] ) && isset( $ratio_map[ $options['aspect_ratio'] ] )
                ? $ratio_map[ $options['aspect_ratio'] ]
                : 'ratio-16x9';
            ?>

			<div class="col-lg-6 <?php echo esc_attr( $image_order ); ?>">
				<?php if ( $media_type == 'image' && $image ) : ?>
					<div class="b-text-image__media-wrapper b-text-image__media-wrapper--image ratio <?php echo esc_attr( $ratio_class ); ?>">
						<img src="<?php echo esc_url( $image['url'] ); ?>" 
							 alt="<?php echo esc_attr( $image['alt'] ); ?>" 
							 class="object-fit-cover rounded-3 shadow w-100 h-100">
					</div>
                <?php elseif ( $media_type === 'video' && $video ) : ?>
                    <div class="b-text-image__media-wrapper b-text-image__media-wrapper--video position-relative rounded-3 shadow overflow-hidden ratio <?php echo esc_attr( $ratio_class ); ?>">
                        <video class="object-fit-cover w-100 h-100 js-html5-video" autoplay loop muted playsinline id="video-<?php echo esc_attr($block_id); ?>">
                            <source src="<?php echo esc_url( $video['url'] ); ?>" type="<?php echo esc_attr( $video['mime_type'] ); ?>">
                            Your browser does not support the video tag.
                        </video>
                        <button class="media-pause-toggle js-video-pause-trigger position-absolute bottom-0 end-0 m-3 border-0 bg-white rounded-circle d-flex align-items-center justify-content-center shadow" 
                                style="width: 40px; height: 40px; z-index: 10;" 
                                aria-label="Pause video">
                            <i class="fa-solid fa-pause text-dark"></i>
                            <i class="fa-solid fa-play text-dark d-none"></i>
                        </button>
                    </div>
                <?php elseif ( $media_type === 'youtube' && $youtube ) : ?>
                    <div class="b-text-image__media-wrapper b-text-image__media-wrapper--youtube position-relative rounded-3 shadow overflow-hidden ratio <?php echo esc_attr( $ratio_class ); ?>">
                        <?php
                        $video_id = '';
                        if ( preg_match( '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $youtube, $matches ) ) {
                            $video_id = $matches[1];
                        }

                        // Add query params for autoplay, mute, and JS API
                        $iframe_html = wp_oembed_get( $youtube ); 
                        
                        // Use regex to append params safely
                        if ( preg_match('/src="([^"]+)"/', $iframe_html, $match) ) {
                            $src = $match[1];
                            
                            $params = array(
                                'autoplay'       => 1,
                                'mute'           => 1,
                                'controls'       => 0,
                                'enablejsapi'    => 1,
                                'loop'           => 1,
                                'rel'            => 0,
                                'modestbranding' => 1,
                                'showinfo'       => 0
                            );

                            // Important: Playlist param is required for loop to work on single video
                            if ( $video_id ) {
                                $params['playlist'] = $video_id;
                            }

                            $new_src = add_query_arg( $params, $src );
                            $iframe_html = str_replace( $src, $new_src, $iframe_html );
                            
                            // Add class/ID
                            $iframe_html = str_replace( '<iframe', '<iframe id="yt-' . esc_attr($block_id) . '" class="js-youtube-iframe"', $iframe_html );
                        }
                        echo $iframe_html; 
                        ?>
                    </div>
                <?php elseif ( $media_type === 'vimeo' && $vimeo ) : ?>
                    <div class="b-text-image__media-wrapper b-text-image__media-wrapper--vimeo position-relative rounded-3 shadow overflow-hidden ratio <?php echo esc_attr( $ratio_class ); ?>">
                        <?php
                        $iframe_html = wp_oembed_get( $vimeo );
                        
                        if ( preg_match('/src="([^"]+)"/', $iframe_html, $match) ) {
                            $src = $match[1];
                            
                            $params = array(
                                'autoplay'   => 1,
                                'muted'      => 1,
                                'loop'       => 1,
                                'background' => 1,
                            );

                            $new_src = add_query_arg( $params, $src );
                            $iframe_html = str_replace( $src, $new_src, $iframe_html );
                            
                            $iframe_html = str_replace( '<iframe', '<iframe id="vimeo-' . esc_attr($block_id) . '" class="js-vimeo-iframe"', $iframe_html );
                        }
                        echo $iframe_html;
                        ?>
                        <button class="media-pause-toggle js-vimeo-pause-trigger position-absolute bottom-0 end-0 m-3 border-0 bg-white rounded-circle d-flex align-items-center justify-content-center shadow" 
                                style="width: 40px; height: 40px; z-index: 10;" 
                                aria-label="Pause video">
                            <i class="fa-solid fa-pause text-dark"></i>
                            <i class="fa-solid fa-play text-dark d-none"></i>
                        </button>
                    </div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>