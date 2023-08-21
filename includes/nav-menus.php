<?php

class SlaveWrecksNavMenus {
    public function __construct() {
        add_action('swp_header_nav', [$this, 'swp_header_nav']);
    }

    /**
     * Taps into the `swp_header_nav` action hook created to render the nav items
     * set for the `primary` menu location.
     *
     * @return void
     * @see template-parts/footer.php
     */
    public function swp_header_nav() {
        $theme_locations = get_nav_menu_locations();

        $menu_obj = get_term($theme_locations['primary'], 'nav_menu');
        $menu_slug = $menu_obj->slug;
        $menu_items = wp_get_nav_menu_items($menu_slug);

        $menu_lists = $this->sanitize_nav_menu($menu_items);

        echo '<ul class="header__nav-list-wrap">';

        foreach ($menu_lists as $menu_nav_item) {
            echo '<li class="header__nav-list-item">';

            // Check if top level item has sub-nav items
            if (isset($menu_nav_item['child']) && $menu_nav_item['child']) {
                echo '<button class="header__nav-list-btn header__nav-list-btn--dropdown">';
                echo $menu_nav_item['title'];
                echo '</button>';

                echo '<div class="header__nav-dropdown-wrap">';
                echo '<button class="header__nav-dropdown-close-btn" aria-haspopup="dialog">';
                echo '<span class="header__nav-dropdown-close-btn-line" role="none"></span>';
                echo '<span class="header__nav-dropdown-close-btn-line" role="none"></span>';
                echo '<p class="header__nav-dropdown-close-tag">Close</p>';
                echo '</button>';

                echo '<ul class="header__nav-dropdown-list-wrap">';

                $sub_nav_items = $menu_nav_item['children'];

                // Render sub-nav items
                foreach ($sub_nav_items as $sub_nav_item) {
                    echo '<li class="header__nav-dropdown-item">';
                    echo '<a class="header__nav-dropdown-link" href="' .
                        $sub_nav_item['link'] .
                        '">' .
                        $sub_nav_item['title'] .
                        '</a>';
                    echo '</li>';
                }

                echo '</ul>';
                echo '</div>';
                echo '</li>';
            } else {
                echo '<a class="header__nav-list-btn header__nav-list-btn--dropdown" href="' .
                    $menu_nav_item['link'] .
                    '">' .
                    $menu_nav_item['title'] .
                    '</a>';
            }

            echo '</li>';
        }

        /**
         * Fires just before the closing of the main menu in the header.
         */
        do_action('swp_header_nav_before_menu_close');

        echo '</ul>';
    }

    /**
     *
     * Transform an array of nav items into an array of arrays to have sub nav items
     * nested within top level items
     *
     * @param array $nav_menu
     *
     * @return array
     *
     * @link https://developer.wordpress.org/reference/functions/wp_get_nav_menu_items/#comment-content-5238
     */
    public function sanitize_nav_menu($nav_menu) {
        $menu_lists = [];

        foreach ($nav_menu as $menu_item) {
            $id = $menu_item->ID;
            $title = $menu_item->title;
            $link = $menu_item->url;
            $menu_item_parent = $menu_item->menu_item_parent;

            // if menu item has no parent, means this is the top-menu.
            if (!$menu_item_parent) {
                $menu_lists[$id]['child'] = false;
                $menu_lists[$id]['id'] = $id;
                $menu_lists[$id]['title'] = $title;
                $menu_lists[$id]['link'] = $link;

                // add active field if current link and open url is same.
                if (get_permalink() === $link) {
                    $menu_lists[$id]['active'] = 'current-menu-item';
                }
            } else {
                // if parent menu is set, means this is 2nd level menu
                if (isset($menu_lists[$menu_item_parent])) {
                    $menu_lists[$menu_item_parent]['child'] = true;
                    $menu_lists[$menu_item_parent]['children'][$id]['child'] = false;
                    $menu_lists[$menu_item_parent]['children'][$id]['id'] = $id;
                    $menu_lists[$menu_item_parent]['children'][$id]['title'] = $title;
                    $menu_lists[$menu_item_parent]['children'][$id]['link'] = $link;

                    // add active field to current menu item and its parent menu item if current link and open url is same.
                    if (get_permalink() === $link) {
                        $menu_lists[$menu_item_parent]['active'] = 'current-menu-item';
                        $menu_lists[$menu_item_parent]['children'][$id]['active'] = 'current-menu-item';
                    }

                    $sub_parent = $menu_item_parent;
                } elseif (isset($menu_lists[$sub_parent][$menu_item_parent])) {
                    // if parent menu is set and their parent menu is also set, means this is 3rd level menu
                    $menu_lists[$sub_parent]['children'][$menu_item_parent]['child'] = true;
                    $menu_lists[$sub_parent]['children'][$menu_item_parent]['children'][$id]['id'] = $id;
                    $menu_lists[$sub_parent]['children'][$menu_item_parent]['children'][$id]['title'] = $title;
                    $menu_lists[$sub_parent]['children'][$menu_item_parent]['children'][$id]['link'] = $link;

                    // add active field to current menu item and its parent menu item if current link and open url is same.
                    if (get_permalink() === $link) {
                        $menu_lists[$sub_parent]['active'] = 'current-menu-item';
                        $menu_lists[$sub_parent]['children'][$menu_item_parent]['active'] = 'current-menu-item';
                        $menu_lists[$sub_parent]['children'][$menu_item_parent]['children'][$id]['active'] =
                            'current-menu-item';
                    }
                }
            }
        }

        return $menu_lists;
    }
}

new SlaveWrecksNavMenus();
