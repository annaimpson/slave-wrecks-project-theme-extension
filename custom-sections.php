<?php

class SlaveWrecksCustomSections
{
    private $sections = [];
    /**
     * @var MosaicHomeTemplateInterface $admin
     */
    private $admin;

    public function __construct()
    {
        $this->add_filters();

        $this->sections = [

        ];
    }

    public function add_filters()
    {
        $filters = [
            'mosaic_register_sections',
        ];

        foreach ($filters as $filter) {
            if (method_exists($this, $filter)) {
                add_filter($filter, [$this, $filter]);
            }
        }

        add_filter('mosaic_admin_section', [$this, 'mosaic_admin_section'], 10, 2);
    }

    public function mosaic_register_sections($sections)
    {
        return array_merge($sections, $this->sections);
    }

    public function mosaic_admin_section($data, $type)
    {
        $section = _::find($this->sections, ['name' => $type]);
        $title = _::get($section, 'button_text');

        if (!empty($title)) {
            $data['title'] = $title;
        }

        return $data;
    }

    /**
     * @param string $label
     * @param array $data
     * @param bool $include_new_window
     */
    public function generate_link_input($label, $data, $include_new_window = true)
    {
        $wplink_id = _::get($data, 'wplink_id', '');
        $field_prefix = _::get($data, 'field_prefix', '');
        $name = _::get($data, 'button_url_name', '');
        $button_url = _::get($data, 'button_url', '');
        $for = _::get($data, 'button_url_for', '');

        echo '<p class="section-url-input"><label>' . $label . '</label>';
        echo '<input type="text" class="url-input" placeholder="Button URL" id="' .
            $wplink_id .
            '" name="' .
            $name .
            '" value="' .
            esc_attr($button_url) .
            '"/>
	        <a href="javascript:void(0);" class="button button-small section-wplink" data-wplink-id="' .
            $wplink_id .
            '"><span class="dashicons dashicons-admin-links"></span></a>';

        if ($include_new_window) {
            $checked = checked(_::get($data, 'button_new_window', false), 'on', false);
            $name = $field_prefix . '[button_new_window]';

            echo '<p><input type="checkbox" ' .
                $checked .
                ' name="' .
                $name .
                '" id="' .
                $for .
                '"><label for="' .
                $for .
                '" class="checkbox-label">Open in New Window</label></p>';
        }
    }

}

new SlaveWrecksCustomSections();
