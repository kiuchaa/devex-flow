<?php
/**
 * Hero Carousel Block Template.
 */

$content = get_field('content') ?: [];
$options = get_field('opties') ?: [];

$title    = $content['title'] ?? '';
$text     = $content['text']  ?? '';
$gallery  = $content['gallery'] ?? [];
$variant  = $options['variant'] ?? 'bg-image';
$order    = $options['volgorde'] ?? 'default';
$is_split = ( $variant === 'image' );

$block_id      = $block['anchor'] ?: 'hero-carousel-' . $block['id'];
$wrapper_class = 'hero-carousel-block js-hero-carousel b-hero-carousel position-relative overflow-hidden ' . ($block['className'] ?? '');

if ( $is_split ) {
    $wrapper_class .= " header-hero-component py-2 py-md-5 bg-{$options['achtergrondkleur']}";
} else {
    $wrapper_class .= ' split-content' . ($options['overlay_kleur'] === 'wit' ? ' white' : '') . ($order === 'omgedraaid' ? ' reverse' : '');
}

// Content container should use the entire container width when using background image
$content_width = $is_split ? 'col-lg-6' : 'col-lg-12';

$layouts = [
        'default' => [
                'col_content' => $content_width . ' order-2 order-lg-1 text-start align-items-start',
                'col_image'   => 'col-lg-6 order-1 order-lg-2 text-center text-lg-end',
                'justify'     => 'justify-content-start',
                'img_style'   => 'max-width: 500px; aspect-ratio: 3/4;'
        ],
        'omgedraaid' => [
                'col_content' => $content_width . ' order-2 order-lg-2 text-end align-items-end',
                'col_image'   => 'col-lg-6 order-1 order-lg-1 text-center text-lg-start',
                'justify'     => 'justify-content-end',
                'img_style'   => 'max-width: 500px; aspect-ratio: 3/4;'
        ],
        'center' => [
                'col_content' => 'col-lg-8 offset-lg-2 order-1 text-center align-items-center',
                'col_image'   => 'col-lg-10 offset-lg-1 order-2 mt-4 mt-lg-5 text-center',
                'justify'     => 'justify-content-center',
                'img_style'   => 'max-width: 100%; aspect-ratio: 16/9;'
        ]
];

$conf = $layouts[$order] ?? $layouts['default'];
$style_attr = $is_split ? '' : 'min-height: 80vh; display: flex; align-items: center;';
?>

    <section id="<?= esc_attr($block_id); ?>" class="<?= esc_attr($wrapper_class); ?>" style="<?= $style_attr; ?>" data-pf-block="hero-carousel">

        <?php if ( $is_split ) : ?>
            <div class="container">
                <div class="row align-items-center gy-2 gy-md-5">
                    <div class="<?= $conf['col_content']; ?> d-flex flex-column mb-4 mb-lg-0">
                        <?php if ($title): ?><h1 class="display-3 fw-bolder mb-2 mb-md-4 ls-tight" data-aos="fade-up"><?= esc_html($title); ?></h1><?php endif; ?>
                        <?php if ($text): ?><div class="lead mb-2 mb-md-5" style="line-height: 1.6;" data-aos="fade-up" data-aos-delay="250"><?= wp_kses_post($text); ?></div><?php endif; ?>

                        <div class="d-flex flex-wrap gap-3 <?= $conf['justify']; ?>" data-aos="fade-in" data-aos-delay="500">
                            <?php
                            if ($content['button_1']) get_template_part('components/button', null, ['link' => $content['button_1'], 'variant' => 'primary']);
                            if ($content['button_2']) get_template_part('components/button', null, ['link' => $content['button_2'], 'variant' => 'secondary']);
                            ?>
                        </div>
                    </div>

                    <div class="<?= $conf['col_image']; ?>">
                        <?php if ($gallery) : ?>
                            <div class="position-relative d-inline-block w-100 rounded-3 overflow-hidden shadow-sm" style="<?= $conf['img_style']; ?>" data-aos="fade-in">
                                <div class="swiper js-hero-swiper heroCarouselSwiper-<?= esc_attr($block['id']); ?> w-100 h-100">
                                    <div class="swiper-wrapper">
                                        <?php foreach ($gallery as $image) : ?>
                                            <div class="swiper-slide overflow-hidden">
                                                <img src="<?= esc_url($image['sizes']['large']); ?>" alt="<?= esc_attr($image['alt']); ?>" class="w-100 h-100 object-fit-cover"/>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="swiper-pagination swiper-pagination-white position-absolute bottom-0 mb-3" style="z-index: 10;"></div>
                                </div>

                                <?php if (count($gallery) > 1) : ?>
                                    <button class="hero-carousel__pause-toggle js-hero-pause-trigger position-absolute bottom-0 end-0 m-4 border-0 bg-transparent text-white"
                                            style="z-index: 99;"
                                            aria-label="Pause carousel">
                                        <i class="fa-solid fa-pause fa-xl"></i>
                                        <i class="fa-solid fa-play fa-xl d-none"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        <?php else : ?>
            <?php if ($gallery): ?>
                <div class="swiper js-hero-swiper heroCarouselSwiper-<?= esc_attr($block['id']); ?> position-absolute top-0 start-0 w-100 h-100" style="z-index:1;">
                    <div class="swiper-wrapper">
                        <?php foreach ($gallery as $image) : ?>
                            <div class="swiper-slide overflow-hidden">
                                <div class="w-100 h-100 bg-dark position-absolute top-0 start-0" style="opacity: 0.5; z-index: 2;"></div>
                                <img src="<?= esc_url($image['sizes']['medium_large']); ?>" alt="<?= esc_attr($image['alt']); ?>" class="w-100 h-100 object-fit-cover"/>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="container position-relative py-5" style="z-index: 10;">
                <div class="row">
                    <div class="col-lg-8 col-xl-7 <?= ($order === 'omgedraaid' ? 'ms-auto' : ($order === 'center' ? 'mx-auto' : '')); ?>">
                        <div class="hero-content text-white <?= ($conf['col_content']); ?>">
                            <?php if ($title): ?><h1 class="display-2 fw-bolder mb-4" data-aos="fade-up"><?= esc_html($title); ?></h1><?php endif; ?>
                            <?php if ($text): ?><div class="lead mb-5" data-aos="fade-up" data-aos-delay="250"><?= wp_kses_post($text); ?></div><?php endif; ?>
                            <div class="d-flex flex-wrap gap-3 <?= $conf['justify']; ?>" data-aos="fade-in" data-aos-delay="500">
                                <?php
                                if ($content['button_1']) get_template_part('components/button', null, ['link' => $content['button_1'], 'variant' => 'primary']);
                                if ($content['button_2']) get_template_part('components/button', null, ['link' => $content['button_2'], 'variant' => 'secondary']);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination swiper-pagination-white position-absolute bottom-0 mb-4" style="z-index: 11;"></div>
            
            <?php if (count($gallery) > 1) : ?>
                <button class="hero-carousel__pause-toggle js-hero-pause-trigger position-absolute bottom-0 end-0 m-4 border-0 bg-transparent text-white"
                        style="z-index: 99;"
                        aria-label="Pause carousel">
                    <i class="fa-solid fa-pause fa-xl"></i>
                    <i class="fa-solid fa-play fa-xl d-none"></i>
                </button>
            <?php endif; ?>
        <?php endif; ?>
    </section>
