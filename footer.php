    </main>

    <?php

    $footer = get_field('footer_instellingen', 'thema-instellingen');

    $alt_logo = $footer['alternatieve_logo'];
    $tagline = $footer['tagline'];
    $show_contact = $footer['contactgegevens'];

    ?>

    <footer class="site-footer py-5 mt-auto">
        <div class="container">
            <div class="row gy-4">
                <div class="col-12 col-lg-5 footer-brand-col">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo">
                        <?php 
                        $logo_id = get_field('logo', 'options');
                        if ($logo_id && !$alt_logo) :
                            echo wp_get_attachment_image($logo_id, 'medium', false, ['style' => 'height: 48px; width: auto;']);
                        else :
                            echo wp_get_attachment_image($alt_logo, 'medium', false, ['style' => 'height: 48px; width: auto;']);
                        endif; ?>
                    </a>
                    <p class="fw-bold mb-2"><?php bloginfo('name'); ?></p>
                    <?php if($tagline): ?>
                        <p class="footer-tagline mb-3">
                            <?= $tagline ?>
                        </p>
                    <?php endif; ?>
                    <?php
                    get_template_part('components/socials', null);

                    if($show_contact) {
                        get_template_part('components/contact', null);
                    }
                    ?>
                </div>

                <?php
                $locations = get_nav_menu_locations();
                $menu_id = isset($locations['footer_menu']) ? $locations['footer_menu'] : false;
                $menu_items = $menu_id ? wp_get_nav_menu_items($menu_id) : [];

                if (!empty($menu_items)) {
                    // Group items by parent to build hierarchy
                    $menu_tree = [];
                    foreach ($menu_items as $item) {
                        $menu_tree[$item->menu_item_parent][] = $item;
                    }

                    // Loop through top-level items (columns)
                    if (isset($menu_tree[0])) {
                        foreach ($menu_tree[0] as $column) {
                            ?>
                            <div class="col-6 col-md-4 col-lg-2 footer-links-col ms-auto">
                                <h5><?php echo esc_html($column->title); ?></h5>
                                <?php if (isset($menu_tree[$column->ID])) : ?>
                                    <ul>
                                        <?php foreach ($menu_tree[$column->ID] as $link) : ?>
                                            <li>
                                                <a href="<?php echo esc_url($link->url); ?>" target="<?php echo esc_attr($link->target ? $link->target : '_self'); ?>">
                                                    <?php echo esc_html($link->title); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                            <?php
                        }
                    }
                } else {
                    ?>
                    <div class="col-6 col-md-4 col-lg-2 footer-links-col">
                        <h5>Menu niet ingesteld</h5>
                        <ul>
                            <li><a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>">Instellen in CMS</a></li>
                        </ul>
                    </div>
                <?php } ?>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="copyright mb-2 mb-md-0">
                    &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Alle rechten voorbehouden.
                </div>
                <div class="legal-links">
                    <?php
                    $footer_bar_id = isset($locations['footer_bar']) ? $locations['footer_bar'] : false;
                    $footer_bar_items = $footer_bar_id ? wp_get_nav_menu_items($footer_bar_id) : [];

                    if (!empty($footer_bar_items)) :
                        foreach ($footer_bar_items as $item) :
                            ?>
                            <a href="<?php echo esc_url($item->url); ?>" target="<?php echo esc_attr($item->target ? $item->target : '_self'); ?>">
                                <?php echo esc_html($item->title); ?>
                            </a>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
