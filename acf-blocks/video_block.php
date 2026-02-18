<?php
/**
 * Block Name: Video Block
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$id = 'video-block-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

$className = 'video-block section';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

// Get Fields
$inhoud = get_field('inhoud');
$opties = get_field('opties');

$titel = $inhoud['titel'] ?? '';
$tekst = $inhoud['tekst'] ?? '';
$knop = $inhoud['knop'] ?? '';
$video_url = $inhoud['video'] ?? '';
$youtube = $inhoud['youtube_url'] ?? '';

$type = $opties['beeldformaat'] ?? 'video';
$bg_color = $opties['achtergrondkleur'] ?? 'white';
$icon_color = $opties['icoon_kleur'] ?? 'primary';
$width = $opties['beeldbreedte'] ?? 'container';

$className .= ' bg-' . $bg_color;
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="container main-content text-center mb-5">
        <?php if($titel): ?>
            <h2 class="block-title mb-3"><?php echo esc_html($titel); ?></h2>
        <?php endif; ?>

        <?php if($tekst): ?>
            <div class="block-text mb-4" style="max-width: 700px; margin-left: auto; margin-right: auto;">
                <?php echo nl2br(esc_html($tekst)); ?>
            </div>
        <?php endif; ?>

        <?php if($knop): 
            $link_url = $knop['url'];
            $link_title = $knop['title'];
            $link_target = $knop['target'] ? $knop['target'] : '_self';

            get_template_part('components/button', null, [
                'link' => [
                    'url' =>  $link_url,
                    'title' => $link_title,
                    'target' => $link_target,
                ],
                'variant' => 'primary',
            ]);
            ?>
        <?php endif; ?>
    </div>
    <div class="video-container-wrapper <?php echo $width === 'full' ? 'w-100' : 'container'; ?>" data-aos="fade-up" data-aos-delay="250" data-aos-duration="1500" data-aos-offset="-750">
        <div class="video-holder ratio-16x9 position-relative overflow-hidden rounded-3 <?php echo $width === 'container' ? '' : 'rounded-0'; ?>">
            
            <?php if($type === 'video' && $video_url): ?>
                <div class="html5-video-wrapper w-100 h-100 js-html5-video-wrapper">
                    <video class="w-100 h-100 object-fit-cover js-html5-video" playsinline>
                        <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                    </video>
                    <button class="play-button-overlay position-absolute top-50 start-50 translate-middle border-0 bg-transparent js-play-trigger" aria-label="Play video">
                        <span class="icon-circle bg-white rounded-circle d-flex align-items-center justify-content-center shadow-lg" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-play text-<?php echo esc_attr($icon_color); ?>" style="font-size: 2rem; margin-left: 5px;"></i>
                        </span>
                    </button>
                    <!-- Pause Overlay (optional, initially hidden) -->
                    <button class="pause-button-overlay position-absolute top-50 start-50 translate-middle border-0 bg-transparent js-pause-trigger d-none" aria-label="Pause video">
                         <!-- Transparent overlay to allow clicking to pause, or show pause icon on hover -->
                    </button>
                </div>

            <?php elseif($type === 'youtube' && $youtube): ?>
                <div class="youtube-wrapper w-100 h-100">
                    <?php echo $youtube; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
