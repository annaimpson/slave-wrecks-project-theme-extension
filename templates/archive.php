<?php
/**
 * @package Alpha Channel Group Base Theme
 * @author  Alpha Channel Group (www.alphachannelgroup.com)
 */

$show_sidebar = MosaicTheme::get_option('archive_sidebar');
$scheme = MosaicTheme::get_option('archive_scheme');
MosaicPostDisplay::init('archive');

require_once plugin_dir_path(__DIR__) . 'template-parts/header.php';

//do_action('cfc_news_archive_hero');
//do_action('cfc_news_archive_body');

require_once plugin_dir_path(__DIR__) . 'template-parts/footer.php';
