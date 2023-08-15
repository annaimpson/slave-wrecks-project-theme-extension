<?php
/**
 * @package Mosaic Base Theme
 * @author  Mosaic Strategies Group (www.mosaicstg.com)
 */

$content_class = '';
$sidebar_active = '';

require_once plugin_dir_path(__DIR__) . 'template-parts/header.php';
if (have_posts()):
    while (have_posts()):
        the_post(); ?>
<section class="mayday__margins page__body-wrap">
	<!-- wysiwyg -->
	<div class="mayday__copy page__content-wrap">
		<?php the_content(); ?>
	</div>
</section>
<?php
    endwhile;
endif;
require_once plugin_dir_path(__DIR__) . 'template-parts/footer.php';
?>
