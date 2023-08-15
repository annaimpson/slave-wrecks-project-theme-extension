<?php

class MosaicCustomShortcodes {
    /**
     * @var array
     */
    private $shortcode_slugs = [ 'mosaic_content' ];

    public function __construct() {
        add_action( 'init', [ $this, 'init' ] );
    }

    public function init() {
        $this->add_shortcodes( $this->shortcode_slugs );
    }

    /**
     * Custom shortcode to render content from admin WYSIWYG content settings
     *
     * Usage: [mosaic_content id="1"] or [mosaic_content id=1]
     *
     * @param array $attributes
     *
     * @return mixed|string|void
     * @see MosaicCustomSettings::shortcodes_settings_interface()
     */
    public function mosaic_content( $attributes ) {
        $shortcode_id = _::get( $attributes, 'id', '' );

        if ( empty( $shortcode_id ) ) {
            return 'Shortcode needs to be passed an attribute of "id".';
        }

        $shortcodes_settings = MosaicTheme::get_option( 'mosaic_sections_shortcodes', [] );
        $shortcode           = _::get( $shortcodes_settings, "{$shortcode_id}", [] );
        $shortcode_text      = _::get( $shortcode, 'shortcode_text', '' );

        return apply_filters( 'the_content', $shortcode_text );
    }

    /**
     * Dynamically add shortcodes
     *
     * @param array $shortcodes
     */
    public function add_shortcodes( $shortcodes ) {
        if ( empty( $shortcodes ) ) {
            return;
        }

        foreach ( $shortcodes as $shortcode ) {
            if ( empty( $shortcode ) || ! method_exists( $this, $shortcode ) ) {
                continue;
            }

            remove_shortcode( $shortcode );

            $callback = [ $this, $shortcode ];
            add_shortcode( $shortcode, $callback );
        }
    }
}

new MosaicCustomShortcodes();