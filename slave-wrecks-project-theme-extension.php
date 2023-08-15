<?php
// TODO: Developers should be renaming PHP Classes to project appropriate names

/**
 * Plugin Name: Slave Wrecks Project Theme Extension
 * Description: Extends theme functionality
 * Version: 1.0.0
 * Author: Mosaic Strategies Group
 * Author URI: https://www.mosaicstg.com
 */
class SlaveWrecksThemeExtension {
    const VERSION = '1.0.0';

    /**
     * @var string
     */
    private $plugin_url;

    /**
     * @var string
     */
    private $plugin_dir;

    /**
     * @var string
     */
    private $scss_dir = '';

    /**
     * @var string
     */
    private $js_url;

    /**
     * @var string
     */
    private $image_dir;

    /**
     * @var string
     */
    private $image_url;

    /**
     * @var bool
     */
    private $gallery_buckets_rendered = FALSE;

    /**
     * Array of custom SCSS stylesheets that should be included.
     * They will automatically be injected / transpiled into the theme's
     * main stylesheet.
     *
     * NOTE: order is important - put the files in the order they should
     * appear in the transpiled SCSS.
     *
     * @var array
     */
    private $scss = [
    ];

    /**
     * @var array
     */
    private $script_tags = [
        'gallery',
        'custom',
    ];

    /**
     * Array of the WP and Mosaic Theme "filters" that should be leveraged.
     * Filters defined here are automatically "hooked", and run the class
     * method by the same name as the filter.
     *
     * To control priority or number of arguments, assign the array element
     * an optional array.  eg:
     * [
     *  'mosaic_additional_scss',
     *  'the_title'            => [10, 2], // priority 10, 2 arguments
     *  'mosaic_render_section'
     * ]
     *
     * @var array
     */
    private $filters = [
        'mosaic_additional_scss',
        'mosaic_sidebars_array',
        'mosaic_render_section'
    ];

    /**
     * Array of the WP and Mosaic Theme "actions" that should be leveraged.
     * Actions defined here are automatically "hooked", and run the class
     * method by the same name as the filter.
     *
     * To control priority or number of arguments, assign the array element
     * an optional array.  eg:
     * [
     *  'wp_enqueue_scripts',
     *  'mosaic_section_section_top' => [10, 2], // priority 10, 2 arguments
     *  'wp_footer'
     * ]
     *
     * @var array
     */
    private $actions = [
        'mosaic_custom_post_type_ready' => [ -99999, 1 ],
        'wp_enqueue_scripts',
        'mosaic_after_logo',
        'mosaic_section_section_top'    => [ 10, 2 ],
        'mosaic_section_section_bottom' => [ 10, 2 ],
        'mosaic_after_footer_sidebar',
        'mosaic_after_footer',
        'wp_footer',
        'swp_footer_menu',
    ];

    /**
     * Hook in Mosaic Sections Theme.
     * Includes this plugin's custom SCSS into the "core" SCSS, so it
     * is all transpiled into a single SCSS file.
     *
     * NOTE: this function should NOT need to be modified.
     *
     * @param array $files
     *
     * @return array
     */
    public function mosaic_additional_scss( $files ) {
        if ( empty( $this->scss ) ) {
            return $files;
        }

        foreach ( $this->scss as $file ) {
            $files[] = $this->scss_dir . $file;
        }

        return $files;
    }

    public function __construct() {
        $this->plugin_url = plugin_dir_url( __FILE__ );
        $this->plugin_dir = plugin_dir_path( __FILE__ );
        $this->scss_dir   = $this->plugin_dir . "scss/";
        $this->js_url     = $this->plugin_url . "js/";
        $this->image_dir  = $this->plugin_dir . 'img/';
        $this->image_url  = $this->plugin_url . 'img/';

        $this->add_hooks( $this->filters, 'filter' );
        $this->add_hooks( $this->actions );

        add_filter( 'mosaic_sections_theme_load_styles', [ $this, 'mosaic_sections_theme_load_styles' ] );
        add_filter( 'nav_menu_link_attributes', [ $this, 'nav_menu_link_attributes' ], 10, 4 );
        add_filter( 'nav_menu_css_class', [ $this, 'nav_menu_css_class' ], 10, 4 );

        add_action('wp_head', [$this, 'wp_head'], 9999999999);
        add_action('header_alert_bar', [$this, 'header_alert_bar']);
        add_action( 'swp_header_nav', [ $this, 'swp_header_nav' ] );
        add_action( 'swp_header_button', [ $this, 'swp_header_button' ] );
        add_action( 'swp_footer_logo', [ $this, 'swp_footer_logo' ] );
        add_action( 'swp_footer_address', [ $this, 'swp_footer_address' ] );
        add_action( 'swp_footer_social', [ $this, 'swp_footer_social' ] );
    }

    /**
     * Taps into the `nav_menu_link_attributes` WP filter hook that filters the HTML attributes applied to a menu
     * item’s anchor element.
     *
     * @param array    $atts
     * @param WP_Post  $item
     * @param stdClass $args
     * @param int      $depth
     *
     * @return array
     * @link https://developer.wordpress.org/reference/hooks/nav_menu_link_attributes/
     */
    public function nav_menu_link_attributes( $atts, $item, $args, $depth ) {
        if ( $depth > 0 ) {
            if ( 'primary' === $args->theme_location && 1 === $depth ) {
                $atts['class'] = 'header__nav-dropdown-link';
            }

            return $atts;
        }

        if ( 'primary' === $args->theme_location ) {
            if ( in_array( 'menu-item-has-children', $item->classes ) ) {
                $atts['class'] = 'header__nav-list-link header__nav-list-link--dropdown';
            } else {
                $atts['class'] = 'header__nav-list-link';
            }
        }

        if ( 'footer' === $args->theme_location ) {
            $atts['class'] = 'footer__nav-list-link';
        }

        return $atts;
    }

    /**
     * Taps into the `nav_menu_css_class` WP filter hook that filters the CSS classes applied to a menu item’s list
     * item element.
     *
     * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
     * @param WP_Post  $item    The current menu item.
     * @param stdClass $args    An object of wp_nav_menu() arguments.
     * @param int      $depth   Depth of menu item. Used for padding.
     *
     * @return array
     * @link https://developer.wordpress.org/reference/hooks/nav_menu_css_class/
     */
    public function nav_menu_css_class( $classes, $item, $args, $depth ) {
        if ( 'primary' === $args->theme_location ) {
            $classes[] = 'header__nav-list-item';
        }

        if ( 'footer' === $args->theme_location ) {
            $classes[] = 'footer__nav-list-item';
        }

        return $classes;
    }

    /**
     * @param bool $load
     *
     * @return bool
     */
    public function mosaic_sections_theme_load_styles($load) {
        // Allow sections theme styles to load
        // so that we can leverage section color schemes
        return $load;
    }

    /**
     * Load custom post types RIGHT after abstract MosaicCustomPostType class gets instantiated.
     * ALL custom post type MUST be required in the following function.
     */
    public function mosaic_custom_post_type_ready() {
    }

    public function wp_enqueue_scripts() {
        wp_enqueue_script( 'jquery' );

        foreach ( $this->script_tags as $script_tag ) {
            wp_register_script( 'mosaic-' . $script_tag, $this->js_url . 'mosaic.' . $script_tag . '.jquery.js', [ 'jquery' ], self::VERSION, TRUE );
        }

        wp_enqueue_style('glightbox', 'https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css');
        wp_enqueue_script('glightbox', 'https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js');
        wp_enqueue_style( 'swp-typekit', 'https://use.typekit.net/rgi3ctj.css' );
        wp_enqueue_style( 'swp-swiper-js', 'https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css' );
        wp_register_script ( 'swp', plugin_dir_url( __FILE__ ) . 'js/main.js', [ 'jquery' ], self::VERSION, TRUE );
        wp_register_script ( 'swp-smooth-scroll', plugin_dir_url( __FILE__ ) . 'js/smooth-scroll.js', [ 'jquery' ], self::VERSION, TRUE );
        wp_register_script ( 'swp-swiper-js', 'https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js', [], '', true );
    }

    public function mosaic_after_logo() {
    }

    /**
     * @param array $section
     * @param int   $section_index
     */
    public function mosaic_section_section_top( $section, $section_index ) {
    }

    /**
     * @param array $section
     * @param int   $section_index
     */
    public function mosaic_section_section_bottom( $section, $section_index ) {
    }

    public function mosaic_render_section( $section ) {
        $additional_classes = _::get( $section, 'additional_classes' );

        if ( FALSE !== stripos( $additional_classes, 'gallery-buckets' ) ) {
            $this->gallery_buckets_rendered = TRUE;
        }

        return $section;
    }

    public function mayday_header_button() {
        mosaic_get_sidebar( 'header_button', '', TRUE );
    }

    /**
     * @param array $sidebars
     *
     * @return array
     */
    public function mosaic_sidebars_array( $sidebars ) {
        return array_merge( $sidebars, [ 'header_button' => 'Header Button' ] );
    }

    /**
     * Taps into the `header_alert_bar` WP action hook that fires in the custom header template part to render
     * alert bar toggle
     *
     * @see template-parts/header.php
     */
    public function header_alert_bar() {
        $general_extended_settings = MosaicTheme::get_option('general_extended', []);
        $alert_bar_toggle_checkbox = _::get($general_extended_settings, 'alert_bar_toggle_checkbox', false);
        $alert_bar_url = _::get($general_extended_settings, 'alert_bar_url', false);
        $alert_bar_text_translations = _::get($general_extended_settings, 'alert_bar_text', []);
        $alert_bar_text = _::get(
            $alert_bar_text_translations,
            _::get($general_extended_settings, 'alertbartext', '')
        );


        if (!$alert_bar_toggle_checkbox) {
            return;
        }
        echo '<div class="header__alert-bar-link-wrap" id="alertBar">';
        if (!empty($alert_bar_url)) {
            echo '<a href="' . $alert_bar_url . '" class="header__alert-bar-text">' .
                apply_filters('the_content', $alert_bar_text) .
                '</a>';
        } else {
            echo '<div class="header__alert-bar-text">' .
                apply_filters('the_content', $alert_bar_text) .
                '</div>';
        }
        echo '</div>';
    }

    public function swp_header_nav() {
        $args = [
            'theme_location' => 'primary',
            'container'      => '',
            'menu_class'     => 'header__nav-list-wrap',
            'title_li'       => FALSE,
            'depth'          => 3
        ];

        wp_nav_menu( $args );
    }

    public function swp_footer_logo() {
        mosaic_get_sidebar( 'footer_sidebar', 'footer__main-logo-wrap', TRUE );
    }

    public function swp_footer_address() {
        mosaic_get_sidebar( 'after_footer_sidebar', 'footer__address', TRUE );
    }

    public function wp_footer() {
        wp_print_scripts('mayday-swiper-js');
        wp_print_scripts('mayday');
        wp_print_scripts('mayday-smooth-scroll');
    }

    public function swp_footer_social() {
        $general_extended_settings = MosaicTheme::get_option('general_extended', []);
        $facebook                  = _::get( $general_extended_settings, 'facebook', '' );
        $instagram                   = _::get( $general_extended_settings, 'instagram', '' );
        $twitter                   = _::get( $general_extended_settings, 'twitter', '' );
        $tiktok                   = _::get( $general_extended_settings, 'tiktok', '' );

        $this->footer_social_media_link($facebook, 'facebook');
        $this->footer_social_media_link($instagram, 'instagram');
        $this->footer_social_media_link($twitter, 'twitter');
        $this->footer_social_media_link($tiktok, 'tiktok');
    }

    /**
     * Taps into the `sfg_footer_menu` WP action hook that fires in the footer template to render the footer menu
     *
     * @see template-parts/footer.php
     */
    public function swp_footer_menu() {
        $args = [
            'theme_location' => 'footer',
            'container' => '',
            'menu_class' => 'footer__nav-list-wrap',
            'title_li' => false,
            'depth' => 3
        ];

        wp_nav_menu($args);
    }

    /**
     * @param string $link
     * @param string $type
     * @return void
     */
    public function footer_social_media_link($link, $type) {
        if(!empty($link)) {
            echo '<li class="footer__social-list-item">';
            echo '<a class="footer__list-item-link" href="' . $link . '" target="_blank">';
            echo '<span class="mayday__helper-text">This link leads off-site.</span>';
            echo '<img class="footer__list-item-image" src="' . plugin_dir_url(__FILE__) . 'img/' . $type . '-pink.svg " alt="">';
            echo '</a>';
            echo '</li>';
        }
    }


    /**
     * Programmatically add WP 'action's or 'filter's
     *
     * @param array  $hooks
     * @param string $type
     */
    public function add_hooks( $hooks = [], $type = 'action' ) {
        if ( empty ( $hooks ) || ! is_array( $hooks ) ) {
            return;
        }

        if ( $type !== 'action' ) {
            $type = 'filter';
        }

        foreach ( $hooks as $hook => $args ) {
            $default_hook_args = [ 10, 1 ];

            // Check if hook has set 'priority' and 'parameters'
            if ( is_int( $hook ) && is_string( $args ) ) {
                $hook = $args;
                $args = $default_hook_args;
            }

            // Skip array entry if 'hook' does not have a respective valid callback function
            // Callback function name MUST be the same as the name of the 'hook'
            if ( ! method_exists( $this, $hook ) ) {
                continue;
            }

            $hook_callback = [ $this, $hook ];
            $priority      = $args[0];
            $parameters    = $args[1];

            call_user_func( "add_{$type}", $hook, $hook_callback, $priority, $parameters );
        }
    }

    public function wp_head() {

        $main_scss_file = $this->plugin_dir . 'css/main.css';

        if (!file_exists($main_scss_file)) {
            return;
        }

        // Load transpiled custom css after theme styles are loaded
        echo '<link type="text/css" rel="stylesheet" href="' . ($this->plugin_url . 'css/main.css') . '?ver=' . self::VERSION . '"/>';
    }
}

new SlaveWrecksThemeExtension();

require_once 'custom-settings.php';
require_once "custom-templates.php";
require_once 'custom-sections.php';
