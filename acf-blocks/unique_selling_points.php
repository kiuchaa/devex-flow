<?php
/**
 * Block Name: Unique Selling Points
 *
 * @package pixel-flow
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'unique-selling-points-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'unique-selling-points';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

$options = get_field('opties');
$background_color = $options['achtergrondkleur'];
$icon_color = $options['accent_kleur'];

$content = get_field('inhoud');
$usps = $content['usps'];
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?> bg-<?= $background_color ?>">
    <div class="container">
        <div class="row justify-content-center">
            <?php if( $usps ): ?>
                <?php foreach( $usps as $x => $usp ): ?>
                    <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                        <div class="usp-item text-center p-3 h-100" data-aos="fade-right" data-aos-delay="<?= $x * 500 ?>">
                            <div class="usp-icon mb-3 text-<?= $icon_color; ?>">
                                <?php if( $usp['icon_type'] === 'fontawesome' && !empty($usp['font_awesome_icoon']) ): ?>
                                    <?= $usp['font_awesome_icoon'] ?>
                                <?php elseif( $usp['icon_type'] === 'svg' && !empty($usp['svg_icon']) ): ?>
                                    <img src="<?php echo esc_url($usp['svg_icon']); ?>" alt="<?php echo esc_attr($usp['title']); ?> icon" class="svg-icon">
                                <?php endif; ?>
                            </div>
                            <h3 class="usp-title h5 mb-2"><?php echo esc_html($usp['title']); ?></h3>
                            <div class="usp-text small">
                                <?php echo wp_kses_post($usp['subtext']); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
