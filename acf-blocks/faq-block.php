<?php
/**
 * Block Name: FAQ Block
 * Description: A flexible MCQ/Accordion block with optional categorization.
 */

// Unique ID for accordion accessibility
$block_id = 'faq-' . $block['id'];

// Load values
$title = get_field('title');
$text = get_field('text');
$use_categories = get_field('use_categories');

$options = get_field('opties');
$bg_color = isset($options['achtergrondkleur']) ? $options['achtergrondkleur'] : 'white';
$icon_color = isset($options['accent_kleur']) ? $options['accent_kleur'] : 'primary';

?>

<section id="<?php echo esc_attr($block_id); ?>" class="faq-block bg-<?php echo esc_attr($bg_color); ?>" data-pf-block="faq">
    <div class="container">
        
        <?php if ($use_categories): ?>
             <!-- Categorized Layout (Sidebar Navigation) -->
            <?php if (have_rows('faq_categories')): ?>
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-8 text-center">
                        <?php if ($title): ?>
                            <h2 class="faq-block__title mb-3"><?php echo esc_html($title); ?></h2>
                        <?php endif; ?>
                        <?php if ($text): ?>
                            <div class="faq-block__text">
                                <?php echo wp_kses_post($text); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <!-- Sidebar Navigation -->
                    <div class="col-lg-3 mb-4 mb-lg-0">
                        <div class="list-group faq-block__nav" role="tablist">
                            <?php 
                            $cat_count = 0;
                            while (have_rows('faq_categories')): the_row(); 
                                $cat_title = get_sub_field('category_title');
                                $cat_slug = sanitize_title($cat_title);
                                $is_active = $cat_count === 0 ? 'active' : ''; // First item active
                            ?>
                                <button class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 rounded-3 mb-0 mb-lg-2 me-3 me-lg-0 px-3 py-3 <?php echo $is_active; ?>" 
                                        id="tab-<?php echo esc_attr($block_id . '-' . $cat_slug); ?>"
                                        data-bs-toggle="list"
                                        href="#content-<?php echo esc_attr($block_id . '-' . $cat_slug); ?>"
                                        role="tab"
                                        aria-controls="content-<?php echo esc_attr($block_id . '-' . $cat_slug); ?>">
                                    <span class="fw-semibold"><?php echo esc_html($cat_title); ?></span>
                                    <?php /* Optional chevron or icon if needed */ ?>
                                </button>
                                <?php $cat_count++; ?>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- Content Panel -->
                    <div class="col-lg-9">
                        <div class="tab-content" id="nav-tabContent-<?php echo esc_attr($block_id); ?>">
                            <?php 
                            $cat_count = 0;
                            while (have_rows('faq_categories')): the_row(); 
                                $cat_title = get_sub_field('category_title');
                                $cat_slug = sanitize_title($cat_title);
                                $is_active = $cat_count === 0 ? 'show active' : ''; // First item active
                            ?>
                                <div class="tab-pane fade <?php echo $is_active; ?>" 
                                     id="content-<?php echo esc_attr($block_id . '-' . $cat_slug); ?>" 
                                     role="tabpanel" 
                                     aria-labelledby="tab-<?php echo esc_attr($block_id . '-' . $cat_slug); ?>">
                                    
                                    <h3 class="h4 fw-bold mb-4"><?php echo esc_html($cat_title); ?></h3>

                                    <?php if (have_rows('category_faqs')): ?>
                                        <div class="faq-block__list border rounded-3 overflow-hidden bg-white">
                                            <?php while (have_rows('category_faqs')): the_row(); 
                                                $q = get_sub_field('question');
                                                $a = get_sub_field('answer');
                                                $item_id = $block_id . '-' . $cat_slug . '-' . get_row_index();
                                            ?>
                                                <div class="faq-block__item border-bottom last-border-0">
                                                    <button class="faq-block__trigger w-100 d-flex justify-content-between align-items-center px-4 py-3 bg-white border-0 text-start" 
                                                            type="button" 
                                                            aria-expanded="false" 
                                                            aria-controls="<?php echo esc_attr($item_id); ?>">
                                                        <span class="faq-block__question fw-semibold pe-3"><?php echo esc_html($q); ?></span>
                                                        <span class="faq-block__icon text-<?php echo esc_attr($icon_color); ?>">
                                                            <svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </span>
                                                    </button>
                                                    <div id="<?php echo esc_attr($item_id); ?>" class="faq-block__answer bg-light" hidden>
                                                        <div class="faq-block__answer-inner px-4 py-3 text-muted">
                                                            <?php echo wp_kses_post($a); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endwhile; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php $cat_count++; ?>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>

            <?php endif; ?>

        <?php else: ?>
            <!-- Standard View (No Sidebar, Split Layout) -->
            <div class="row">
                <!-- Left Column: Title & Intro -->
                <div class="col-lg-5 mb-5 mb-lg-0">
                    <div class="faq-block__header">
                        <?php if ($title): ?>
                            <h2 class="faq-block__title"><?php echo esc_html($title); ?></h2>
                        <?php endif; ?>
                        
                        <?php if ($text): ?>
                            <div class="faq-block__text">
                                <?php echo wp_kses_post($text); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Right Column: Accordion Content -->
                <div class="col-lg-7">
                    <div class="faq-block__content">
                         <?php if (have_rows('faqs')): ?>
                            <div class="faq-block__list border-topp">
                                <?php while (have_rows('faqs')): the_row(); 
                                    $q = get_sub_field('question');
                                    $a = get_sub_field('answer');
                                    $item_id = $block_id . '-' . get_row_index();
                                ?>
                                    <div class="faq-block__item border-bottomm">
                                        <button class="faq-block__trigger" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr($item_id); ?>">
                                            <span class="faq-block__question"><?php echo esc_html($q); ?></span>
                                            <span class="faq-block__icon text-<?php echo esc_attr($icon_color); ?>">
                                                <svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </span>
                                        </button>
                                        <div id="<?php echo esc_attr($item_id); ?>" class="faq-block__answer" hidden>
                                            <div class="faq-block__answer-inner">
                                                <?php echo wp_kses_post($a); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
    </div>
</section>
