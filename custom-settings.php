<?php

class SlaveWrecksCustomSettings {
    const ADMIN_SETTINGS = "acg_options";

    /**
     * @var array
     */
    private $filters = [];

    /**
     * @var array
     */
    private $actions = [
        'mosaic_settings_custom_tabs',
        'mosaic_settings_custom_tab_views',
    ];

    public function __construct() {
//		$this->add_hooks( $this->filters, 'filter' );
//		$this->add_hooks( $this->actions );

        add_action( 'mosaic_settings_custom_tabs', [ $this, 'mosaic_settings_custom_tabs' ] );
        add_action( 'mosaic_settings_custom_tab_views', [ $this, 'mosaic_settings_custom_tab_views' ] );
    }

    /**
     * Adds custom tabs in the sections main admin settings screen
     *
     * Usage: do_action( 'mosaic_settings_custom_tabs' )
     * File Location: functions.php
     */
    public function mosaic_settings_custom_tabs() {
        ?>
        <a href="javascript:void(0);" data-tab="general-extended">General (extended)</a>
        <?php
    }

    /**
     * Adds custom settings views for it's respective custom tab in our main sections
     * theme admin settings
     *
     * Usage: do_action( 'mosaic_settings_custom_tabs_views', $options )
     * File Location: functions.php
     *
     * @param array $options
     */
    public function mosaic_settings_custom_tab_views( $options ) {
        $this->shortcodes_settings_interface( $options );
        $this->general_extended_settings_interface($options);
    }

    public function general_extended_settings_interface( $options ) {
        // This gets settings for 'general (extended)' ONLY
        $general_extended_settings = _::get( $options, 'general_extended', [] );
        $translations = pll_the_languages(['raw' => 1]);
        $alert_bar_toggle_checkbox = _::get($general_extended_settings, 'alert_bar_toggle_checkbox', false);
        $alert_bar_text = _::get($general_extended_settings, 'alert_bar_text', false);
        $alert_bar_url = _::get($general_extended_settings, 'alert_bar_url', false);
        $field_prefix              = self::ADMIN_SETTINGS . '[general_extended]';
        $facebook                  = _::get( $general_extended_settings, 'facebook', '' );
        $tiktok                   = _::get( $general_extended_settings, 'tiktok', '' );
        $instagram                   = _::get( $general_extended_settings, 'instagram', '' );
        $twitter                   = _::get( $general_extended_settings, 'twitter', '' );

        ?>
        <table class="form-table the-acg general-extended">
            <tr>
                <td colspan="2">
                    <h2>General (extended)</h2>
                </td>
            </tr>
            <tr>
                <td colspan="2"><h3>Alert Bar Toggle</h3></td>
            </tr>
            <tr>
                <th scope="row">Active</th>
                <td>
                    <input type="checkbox" name="<?php echo $field_prefix . '[alert_bar_toggle_checkbox]' ?>" <?php echo $alert_bar_toggle_checkbox ? 'checked' : ''; ?>/>
                </td>
            </tr>
            <?php foreach ($translations as $translation) {
                $alert_bar_text = _::get(
                    $general_extended_settings,
                    ['alert_bar_text', $translation['slug']],
                    _::get($general_extended_settings, 'alert_bar_text', '')
                );
                echo '<tr>';
                echo '<th scope="row">' . esc_html($translation['name']) . ' Alert Bar Text</th>';
                echo '<td>';
                wp_editor($alert_bar_text, esc_attr($this->generate_editor_id()), [
                    'textarea_name' => esc_attr($field_prefix . '[alert_bar_text][' . $translation['slug'] . ']'),
                    'editor_height' => 250
                ]);
                echo '</td>';
                echo '</tr>';
            } ?>
            <tr>
                <th scope="row">Alert Bar Link</th>
                <td><?php $this->generate_text_input( $field_prefix . '[alert_bar_url]', $alert_bar_url, 'Alert Bar Link' ); ?></td>
            </tr>
            <tr>
                <td colspan="2">
                    <h3>Social Media</h3>
                </td>
            </tr>
            <tr>
                <th scope="row">Facebook</th>
                <td><?php $this->generate_text_input( $field_prefix . '[facebook]', $facebook, 'Facebook' ); ?></td>
            </tr>
            <tr>
                <th scope="row">Instagram</th>
                <td><?php $this->generate_text_input( $field_prefix . '[instagram]', $instagram, 'Instagram' ); ?></td>
            </tr>
            <tr>
                <th scope="row">Twitter</th>
                <td><?php $this->generate_text_input( $field_prefix . '[twitter]', $twitter, 'Twitter' ); ?></td>
            </tr>
            <tr>
                <th scope="row">TikTok</th>
                <td><?php $this->generate_text_input( $field_prefix . '[tiktok]', $tiktok, 'TikTok' ); ?></td>
            </tr>
        </table>
        <?php
    }

    public function generate_text_input( $name, $value, $placeholder = '' ) {
        echo '<input type="text" name="' . $name . '" value="' . $value . '" placeholder="' . $placeholder . '"/>';
    }

    public function shortcodes_settings_interface( $options ) {
        $shortcodes_settings              = _::get( $options, 'mosaic_sections_shortcodes', [] );
        $shortcodes_settings_field_prefix = self::ADMIN_SETTINGS . '[mosaic_sections_shortcodes]';
        $shortcodes_count                 = 5;

        ?>
        <table class="form-table the-acg shortcodes">
            <tr>
                <td colspan="2">
                    <h3>Shortcodes</h3>
                </td>
            </tr>
            <?php
            for ( $i = 1; $i <= $shortcodes_count; $i++ ) {
                $shortcode_id   = $i;
                $shortcode      = _::get( $shortcodes_settings, $i, '' );
                $shortcode_text = _::get( $shortcode, 'shortcode_text', '' );
                $this->shortcode_settings_content(
                    $shortcode_id,
                    $shortcode_text,
                    $shortcodes_settings_field_prefix . '[' . $i . ']'
                );
            }
            ?>
        </table>
        <?php
    }

    public function shortcode_settings_content( $shortcode_id, $shortcode_text, $shortcode_settings_field_prefix = '' ) {
        ?>
        <tr>
            <th scope="row">
                <code>[mosaic_content id="<?php echo $shortcode_id; ?>"]</code>
            </th>
            <td>
                <?php
                wp_editor(
                    $shortcode_text,
                    $this->generate_editor_id(),
                    [
                        'textarea_name' => $shortcode_settings_field_prefix . '[shortcode_text]',
                        'editor_height' => 250
                    ] );
                ?>
            </td>
        </tr>
        <?php
    }

    /**
     * Small utility function to generate (hopefully) unique editor IDs
     * so that editors, when drawn, each have their own ID, and TinyMCE can
     * hook into them properly.
     *
     * @param string $word - can pass in other prefixes, such as "wplink".  Default is "editor"
     *
     * @return string
     */
    public function generate_editor_id( $word = 'editor' ) {
        return $word . '-' . substr( md5( rand() ), 0, 10 );
    }

    /**
     * Render a banner
     *
     * @param string $image
     * @param string $text
     * @param string $class
     */
    public function render_custom_banner( $image, $text = '', $class = '' ) {
        $background = '';

        if ( ! empty( $image ) ) {
            $background = ' style="background: white url(' . $image . ') center center / cover no-repeat;"';
        }

        ?>
        <div class="section-wrapper">
            <div class="mosaic-section <?php echo $class ?>" <?php echo $background; ?>>
                <div class="section-overlay"></div>
                <div class="sub-contents center">
                    <h1><?php echo $text; ?></h1>
                </div>
            </div>
        </div>
        <?php
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
}

new SlaveWrecksCustomSettings();