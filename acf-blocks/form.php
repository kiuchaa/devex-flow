<?php
/**
 * Block Name: Formulier
 *
 * This is the template that displays the Formulier block.
 */

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'formulier';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}

// Get the fields
$content_group = get_field('content'); 
$options_group = get_field('options');

// Content sub-fields
$title                 = $content_group['title'] ?? '';
$text                  = $content_group['text'] ?? '';
$ninja_form            = $content_group['ninja_form'] ?? null;
$show_contact          = $content_group['standaard_gegevens'] ?? false;

// Options sub-fields
$layout       = $options_group['layout'] ?? 'two-columns';
$bg_color     = $options_group['background_color'] ?? 'white';
$accent_kleur = $options_group['accent_kleur'] ?? 'primary';
$width        = $options_group['width'] ?? 'container';

// Apply background color class
$class_name .= ' bg-' . esc_attr($bg_color);
$class_name .= ' formulier--layout-' . $layout;

// Determine text color based on background for contrast
if (in_array($bg_color, ['white', 'info'])) {
    $class_name .= ' text-dark';
} else {
    $class_name .= ' text-white';
}

// Container class
$container_class = 'container';
if ($width === 'container-small') {
    $container_class = 'container container--small';
} elseif ($width === 'full-width') {
    $container_class = 'container-fluid';
}

?>

<section class="<?php echo esc_attr( $class_name ); ?>">
    <div class="<?php echo esc_attr( $container_class ); ?> formulier__container">
        <?php if ($layout === 'two-columns') : ?>
            <div class="row align-items-center">
                <div class="col-12 col-lg-5 mb-5 mb-lg-0">
                    <div class="formulier__intro">
                        <?php if ( $title ) : ?>
                            <h2 class="formulier__title"><?php echo esc_html( $title ); ?></h2>
                        <?php endif; ?>
                        
                        <?php if ( $text ) : ?>
                            <div class="formulier__text mb-5">
                                <?php echo wp_kses_post( $text ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if($show_contact):
                            get_template_part('components/socials', null);

                            get_template_part('components/contact', null);
                        endif; ?>
                    </div>
                </div>
                <div class="col-12 col-lg-6 offset-lg-1">
                    <div class="formulier__form-wrapper">
                        <?php 
                        $this_form_id = null;
                        if ( is_object($ninja_form) ) {
                            $vars = get_object_vars($ninja_form);
                            $first = reset($vars);
                            $this_form_id = is_array($first) ? ($first['id'] ?? null) : $first;
                        } elseif ( is_array($ninja_form) ) {
                            if ( isset($ninja_form['id']) ) {
                                $this_form_id = $ninja_form['id'];
                            } elseif ( isset($ninja_form[0]) ) {
                                $this_form_id = is_array($ninja_form[0]) ? ($ninja_form[0]['id'] ?? null) : $ninja_form[0];
                            }
                        } else {
                            $this_form_id = $ninja_form;
                        }
                        ?>
                        <?php if ($this_form_id && function_exists('Ninja_Forms')) : ?>
                            <?php Ninja_Forms()->display($this_form_id); ?>
                        <?php else : ?>
                            <p><?php _e('Selecteer een Ninja Form.', 'devex'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <div class="formulier__content text-center mb-5">
                        <?php if ( $title ) : ?>
                            <h2 class="formulier__title"><?php echo esc_html( $title ); ?></h2>
                        <?php endif; ?>
                        
                        <?php if ( $text ) : ?>
                            <div class="formulier__text">
                                <?php echo wp_kses_post( $text ); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="formulier__form-wrapper">
                        <?php 
                        $this_form_id = null;
                        if ( is_object($ninja_form) ) {
                            $vars = get_object_vars($ninja_form);
                            $first = reset($vars);
                            $this_form_id = is_array($first) ? ($first['id'] ?? null) : $first;
                        } elseif ( is_array($ninja_form) ) {
                            if ( isset($ninja_form['id']) ) {
                                $this_form_id = $ninja_form['id'];
                            } elseif ( isset($ninja_form[0]) ) {
                                $this_form_id = is_array($ninja_form[0]) ? ($ninja_form[0]['id'] ?? null) : $ninja_form[0];
                            }
                        } else {
                            $this_form_id = $ninja_form;
                        }
                        ?>
                        <?php if ($this_form_id && function_exists('Ninja_Forms')) : ?>
                            <?php Ninja_Forms()->display($this_form_id); ?>
                        <?php else : ?>
                            <p><?php _e('Selecteer een Ninja Form.', 'devex'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
