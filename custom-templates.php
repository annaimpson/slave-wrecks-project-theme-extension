<?php

class MosaicCustomTemplates {

    /**
     * @var WP_Post
     */
    private $post = NULL;

    /**
     * @var string
     */
    private $plugin_path;

    private $page_templates = [
        '404.php' => '404',
    ];

    private $archive_templates = [
        'post' => 'archive.php',
    ];

    private $single_templates = [
        'post' => 'single.php',
    ];

    private $taxonomy_templates = [
    ];

    public function __construct() {
        $this->plugin_path = plugin_dir_path( __FILE__ );

        $filters = [
            'theme_page_templates' => [ 10, 3 ],
            'template_include',
            'archive_template',
            'single_template',
            'taxonomy_template',
            'search_template'
        ];

        $this->add_hooks( $filters, 'filter' );

        add_filter('404_template', [$this, '_404_template'], 10, 3);
    }

    /**
     * Taps into the 'template_include' that allows loading a custom template or
     * to see which template is being loaded
     *
     * @param string $template
     *
     * @return string
     */
    public function template_include( $template ) {
        global $post;

        if ( empty( $post ) ) {
            return $template;
        }

        $post_template = get_post_meta( $post->ID, '_wp_page_template', TRUE );

        if (
            'default' === $post_template ||
            'index.php' === $post_template ||
            'page-home.php' === $post_template ||
            'page-home-with-sidebar.php' === $post_template
        ) {
            $file = $this->plugin_path . "templates/{$post_template}";

            if ('default' === $post_template) {
                $file = $this->plugin_path . 'templates/page.php';
            }

            if (file_exists($file)) {
                return $file;
            }
        }

        // Check if current post/page template exists in registered custom templates
        if ( array_key_exists( $post_template, $this->page_templates ) ) {
            $file = $this->plugin_path . "templates/{$post_template}";

            //Only open file if it DOES exist
            if ( file_exists( $file ) ) {
                return $file;
            }
        }

        return $template;
    }

    /**
     * Append an entry into the associative array that is used to display the 'Templates'
     * dropdown on the backend of a page
     *
     * @param array        $page_templates
     * @param WP_Theme     $theme
     * @param WP_Post|null $post
     *
     * @return array
     */
    public function theme_page_templates( $page_templates, $theme, $post ) {
        $page_templates = array_merge( $page_templates, $this->page_templates );

        return $page_templates;
    }

    /**
     * Taps into 'archive_template' filter which allows setting a new
     * template for when WP tries to retrieve 'archive.php' or 'archive-{type}.php' templates
     *
     * @param string $template
     *
     * @return string $template
     */
    public function archive_template( $template ) {
        return $this->override_template( $template, 'archive', $this->archive_templates );
    }

    /**
     * Taps into 'single_template' filter which allows setting a news
     * template for when WP tries to retrieve 'single.php' or 'single-{type}.php' templates
     *
     * @param string $template
     *
     * @return string $template
     */
    public function single_template( $template ) {
        global $post;
        $this->post = $post;

        return $this->override_template( $template, 'single', $this->single_templates );
    }

    /**
     * Taps into 'taxonomy_template' filter which allows overwriting the template being loaded
     * when a 'taxonomy.php' or 'taxonomy-{slug}.php' is trying to be lifted
     *
     * @param string $template
     *
     * @return string $template
     */
    public function taxonomy_template( $template ) {
        return $this->override_template( $template, 'taxonomy', $this->taxonomy_templates );
    }

    /**
     * Taps into 'search_template' filter which allows overriding the template that displays
     * the results of the search
     *
     * @param string $template
     *
     * @return string $template
     */
    public function search_template( $template ) {
        $temp = $this->plugin_path . "templates/search.php";

        if ( file_exists( $temp ) ) {
            $template = $temp;
        }

        return $template;
    }

    /**
     * DRY-er approach.  Use a single loop for all of the different template overrides.
     *
     * @param string $template
     * @param string $type
     * @param array  $overrides
     *
     * @return string
     */
    private function override_template( $template, $type, $overrides ) {
        if ( empty( $overrides ) ) {
            return $template;
        }

        foreach ( $overrides AS $post_type => $custom_template ) {
            if ( $this->is_type_match( $post_type, $type ) ) {
                $temp = $this->plugin_path . "templates/{$custom_template}";

                if ( file_exists( $temp ) ) {
                    $template = $temp;
                } else {
                    echo "<!-- WARNING: {$type} template override for {$post_type} specified, but file '{$custom_template}' does not exist in plugin templates folder. -->" . PHP_EOL;
                }
            }
        }

        return $template;
    }


    /**
     * Taps into '404_template' filter which allows overriding the template that displays
     * the 404 template
     *
     * @param string $template
     *
     * @return string $template
     */
    public function _404_template($template, $type, $templates) {
        $temp = $this->plugin_path . 'templates/404.php';

        if (file_exists($temp)) {
            $template = $temp;
        }

        return $template;
    }

    /**
     * Determines which type of "type match" to execute, and compares.
     * Handles "archive", "single", and "taxonomy"
     *
     * @param string $check_type
     * @param string $type
     *
     * @return bool
     */
    private function is_type_match( $check_type, $type ) {
        if ( 'archive' == $type ) {
            return is_post_type_archive( $check_type ) || ( $check_type == get_post_type() );
        }

        if ( 'single' == $type ) {
            return ( $check_type == $this->post->post_type );
        }

        if ( 'taxonomy' == $type ) {
            return is_tax( [ $check_type ] );
        }

        echo "<!-- Undefined type patch post type '{$type}' -->" . PHP_EOL;
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

        foreach ( $hooks AS $hook => $args ) {
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
}

new MosaicCustomTemplates();
