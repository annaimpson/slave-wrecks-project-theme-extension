<?php
/**
 * The Template for displaying all single posts.
 */

$show_sidebar = MosaicTheme::get_option('single_sidebar');
$scheme = MosaicTheme::get_option('single_scheme');

require_once plugin_dir_path(__DIR__) . 'template-parts/header.php';
?>
<?php do_action('mosaic_top_of_post', ['single', get_post_type()]); ?>
<?php if (have_posts()):
    while (have_posts()):
        the_post();
    endwhile;
endif; ?>
<?php require_once plugin_dir_path(__DIR__) . 'template-parts/footer.php'; ?>
