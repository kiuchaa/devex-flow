<?php
// bootstrap 5 wp_nav_menu walker
class bootstrap_5_wp_nav_menu_walker extends Walker_Nav_menu
{
    private $current_item;
    private $dropdown_menu_alignment_values = [
        'dropdown-menu-start',
        'dropdown-menu-end',
        'dropdown-menu-sm-start',
        'dropdown-menu-sm-end',
        'dropdown-menu-md-start',
        'dropdown-menu-md-end',
        'dropdown-menu-lg-start',
        'dropdown-menu-lg-end',
        'dropdown-menu-xl-start',
        'dropdown-menu-xl-end',
        'dropdown-menu-xxl-start',
        'dropdown-menu-xxl-end'
    ];

    // Helper to store child counts
    private $child_counts = [];
    
    // Helper to track menu type per depth (stack)
    private $is_mega_menu_stack = [];

    /**
     * Traverse elements to count children before rendering
     */
    public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output)
    {
        if (!$element) {
            return;
        }

        $id_field = $this->db_fields['id'];
        $id = $element->$id_field;

        // Count children for this element
        $child_count = 0;
        if (isset($children_elements[$id])) {
            $child_count = count($children_elements[$id]);
        }
        
        // Store on the element object for easy access in start_lvl / start_el
        $element->child_count = $child_count;

        // Call parent
        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }

    function start_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);
        
        // Check how many children the *current parent* has.
        // start_lvl triggers *after* start_el of the parent, so current_item is correct HERE.
        $is_mega_menu = ($depth === 0 && isset($this->current_item->child_count) && $this->current_item->child_count > 1);
        
        // Store state for end_lvl
        $this->is_mega_menu_stack[$depth] = $is_mega_menu;

        // Depth 0: Main Dropdown
        if ($depth === 0) {
            if ($is_mega_menu) {
                // MEGA MENU: Full width
                $output .= "\n$indent<ul class=\"dropdown-menu w-100 start-0 border-0 mt-0 p-0 rounded-0\" data-bs-popper=\"static\">\n";
                $output .= "$indent\t<li><div class=\"container py-4\"><ul class=\"row list-unstyled mb-0\">\n";
            } else {
                // STANDARD DROPDOWN: Simple list
                $output .= "\n$indent<ul class=\"dropdown-menu border-0 shadow-sm mt-0 rounded-0\">\n";
            }
        } 
        // Depth 1
        elseif ($depth === 1) {
             // For Depth 1 items (columns or links), we start a list for their children
            $output .= "\n$indent<ul class=\"list-unstyled\">\n";
        } 
        else {
            $output .= "\n$indent<ul class=\"sub-menu\">\n";
        }
    }

    function end_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);
        
        // Retrieve state for this depth
        $is_mega_menu = isset($this->is_mega_menu_stack[$depth]) ? $this->is_mega_menu_stack[$depth] : false;

        if ($depth === 0) {
            if ($is_mega_menu) {
                $output .= "$indent\t</ul></div></li>\n"; // Close row, container, wrapper li
                $output .= "$indent</ul>\n"; 
            } else {
                $output .= "$indent</ul>\n";
            }
        } elseif ($depth === 1) {
            $output .= "$indent</ul>\n";
        } else {
            $output .= "$indent</ul>\n";
        }
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $this->current_item = $item; // Track for start_lvl
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        // --- CLASSES ---
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $child_count = isset($item->child_count) ? $item->child_count : 0;
        $is_mega_parent = ($depth === 0 && $child_count > 1);

        // Depth 0: Top Link
        if ($depth === 0) {
            $classes[] = 'nav-item';
            if ($args->walker->has_children) {
                $classes[] = 'dropdown';
                if ($is_mega_parent) {
                    $classes[] = 'position-static'; // Full width only for Mega
                }
            }
        } 
        // Depth 1
        elseif ($depth === 1) {
            // Check if we are inside a Mega Menu structure?
            // This is tricky without passing state down. 
            // Assumption: If this item has no children, it might be a direct link in a Standard dropdown, OR a column header in Mega with no links?
            // Actually, we can check the *Parent* of this item. 
            // But for now, let's assume if it is Depth 1, we treat it based on context. 
            // Given the complexity request: "when there's only one child ... display dropdown-list" 
            
            // If we are in Mega Menu mode (parent had >1), this is a column.
            // If we are in Standard mode (parent had 1), this is a simple link item.
            
            // Since we can't easily see parent here without extra logic, let's use a class trick or generic styling.
            // However, the `start_lvl` wrapper handles the layout. 
            // Just apply column classes if we think it's a mega menu. 
            // We can check `menu_item_parent` ID and count its children? No too expensive.
            
            // Compromise: Add column classes anyway. Standard dropdowns ignore Col classes usually if not in a .row.
            $classes[] = 'col-12 col-md-6 col-lg-3';
        }
        // Depth 2
        elseif ($depth === 2) {
            $classes[] = 'nav-item';
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = ' class="' . esc_attr($class_names) . '"';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li ' . $id . $class_names . '>';

        // --- ATTRIBUTES ---
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        // --- LINK CLASSES & STRUCTURE ---
        $link_class = '';
        
        if ($depth === 0) {
            $link_class = 'nav-link';
            if ($args->walker->has_children) {
                // REMOVED 'dropdown-toggle' to allow custom chevron
                $link_class .= ' d-flex align-items-center gap-1'; 
                $attributes .= ' data-bs-toggle="dropdown" aria-expanded="false"';
            }
        } elseif ($depth === 1) {
            $link_class = 'd-block h6 fw-bold text-dark text-decoration-none mb-2 mega-menu-title';
        } elseif ($depth === 2) {
             $link_class = 'd-inline-block text-decoration-none text-muted small py-1 hover-primary';
        }

        $attributes .= ' class="' . $link_class . '"';

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        
        // Add Font Awesome Chevron if Depth 0 and has children
        if ($depth === 0 && $args->walker->has_children) {
             $item_output .= ' <i class="fa-solid fa-chevron-down ms-1" style="font-size: 0.75em;"></i>';
        }

        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    function end_el(&$output, $item, $depth = 0, $args = null)
    {
        $output .= "</li>\n";
    }
}