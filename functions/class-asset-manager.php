<?php

/**
 * Class AssetManager
 * Handles all asset registration, enqueuing, and optimization.
 * 
 * @package PixelFlow
 */
class AssetManager {
    
    /**
     * @var array Flags for conditional loading
     */
    private $assets_needed = [
        'swiper' => false,
        'aos' => false,
    ];

    /**
     * Constructor
     */
    public function __construct() {
        // Core asset hooks
        \PixelFlow\add_action('wp_enqueue_scripts', [$this, 'common_assets']);
        
        // Editor assets
        \PixelFlow\add_action('enqueue_block_editor_assets', [$this, 'enqueue_editor_assets']);
        
        // Resource hints & Preloading
        \PixelFlow\add_filter('wp_resource_hints', [$this, 'add_resource_hints'], 10, 2);
        \PixelFlow\add_action('wp_head', [$this, 'add_preload_tags'], 1);
        
        // Conditional analysis
        \PixelFlow\add_action('wp', [$this, 'analyze_page_content']);
    }

    /**
     * Analyze page content to determine which assets are needed.
     */
    public function analyze_page_content() {
        if (\PixelFlow\is_admin()) {
            return;
        }

        global $post;
        if (!is_a($post, 'WP_Post')) {
            return;
        }

        $content = $post->post_content;

        // Check for Swiper usage (e.g., hero-carousel, post-type-carousel blocks)
        if (
            \PixelFlow\has_block('acf/hero-carousel', $post) ||
            \PixelFlow\has_block('acf/post-type-carousel', $post) ||
            strpos($content, 'swiper-container') !== false ||
            strpos($content, 'swiper') !== false
        ) {
            $this->assets_needed['swiper'] = true;
        }

        // Check for AOS usage
        if (strpos($content, 'data-aos') !== false) {
            $this->assets_needed['aos'] = true;
        }
    }

    /**
     * Register and enqueue all scripts and styles.
     */
    public function common_assets() {
        // Deregister default jQuery to use our own version/CDN if preferred, 
        // but often best to stick with WP core unless specific version needed. 
        // Original code deregistered and re-registered, so we keep that logic.
        \PixelFlow\wp_deregister_script('jquery');
        \PixelFlow\wp_deregister_script('jquery-core');
        \PixelFlow\wp_deregister_script('jquery-migrate');

        // jQuery
        \PixelFlow\wp_register_script('jquery-core', 'https://code.jquery.com/jquery-3.5.1.min.js', [], null, false);
        \PixelFlow\wp_register_script('jquery-migrate', 'https://code.jquery.com/jquery-migrate-3.3.2.min.js', [], null, false);
        \PixelFlow\wp_register_script('jquery', false, ['jquery-core', 'jquery-migrate'], null, false);
        \PixelFlow\wp_enqueue_script('jquery');

        // Bootstrap (Bundle includes Popper) - Last updated in pixel-actions was 5.3.3
        \PixelFlow\wp_register_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', ['jquery'], null, true);
        \PixelFlow\wp_enqueue_script('bootstrap');

        // FontAwesome
        \PixelFlow\wp_register_style('fontawesome', get_template_directory_uri() . '/assets/font-awesome/css/all.min.css', [], '6.0.0');
        \PixelFlow\wp_enqueue_style('fontawesome');

        // Theme Core
        $app_js_ver = $this->smart_filemtime('/assets/js/app.js');
        \PixelFlow\wp_register_script('pixel-flow-app', get_template_directory_uri() . '/assets/js/app.js', ['jquery'], $app_js_ver, true);
        \PixelFlow\wp_enqueue_script('pixel-flow-app');

        $style_css_ver = $this->smart_filemtime('/style.css');
        \PixelFlow\wp_register_style('pixel-flow-app', get_template_directory_uri() . '/style.css', [], $style_css_ver);
        \PixelFlow\wp_enqueue_style('pixel-flow-app');

        // Conditional Loading
        // Swiper - CDN
        if ($this->assets_needed['swiper']) {
            \PixelFlow\wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', ['jquery'], null, true);
            \PixelFlow\wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], null);
        }

        // AOS
        if ($this->assets_needed['aos']) {
            \PixelFlow\wp_enqueue_script('aos', 'https://unpkg.com/aos@2.3.1/dist/aos.js', ['jquery'], null, true);
            \PixelFlow\wp_enqueue_style('aos', 'https://unpkg.com/aos@2.3.1/dist/aos.css', [], null);
        }
    }

    /**
     * Enqueue assets for the Block Editor.
     * We always load Swiper/AOS here to ensure previews work.
     */
    public function enqueue_editor_assets() {
        \PixelFlow\wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], null, true);
        \PixelFlow\wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], null);
        
        // Add AOS if widely used in blocks, otherwise optional in editor
        \PixelFlow\wp_enqueue_style('aos', 'https://unpkg.com/aos@2.3.1/dist/aos.css', [], null);
    }

    /**
     * Add resource hints (dns-prefetch, preconnect).
     */
    public function add_resource_hints($urls, $relation_type) {
        if ('preconnect' === $relation_type) {
            $urls[] = [
                'href' => 'https://cdn.jsdelivr.net',
                'crossorigin' => 'anonymous',
            ];
            $urls[] = [
                'href' => 'https://code.jquery.com',
                'crossorigin' => 'anonymous',
            ];
            $urls[] = [
                'href' => 'https://unpkg.com',
                'crossorigin' => 'anonymous',
            ];
        }
        return $urls;
    }

    /**
     * Add preload tags for critical assets.
     */
    public function add_preload_tags() {
        // Preload main stylesheet
        echo '<link rel="preload" href="' . \PixelFlow\get_template_directory_uri() . '/style.css" as="style">';
        
        // Preload pixel-flow-app.js if critical
        echo '<link rel="preload" href="' . \PixelFlow\get_template_directory_uri() . '/assets/js/app.js" as="script">';
    }

    /**
     * Helper: Smart filemtime for versioning.
     * Returns file modification time or version string.
     */
    private function smart_filemtime($relative_path) {
        $path = \PixelFlow\get_template_directory() . $relative_path;
        return file_exists($path) ? filemtime($path) : '1.0.0';
    }
}
