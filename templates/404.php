<?php
/**
 * @package Mosaic Base Theme
 * @author  Mosaic Strategies Group (www.mosaicstg.com)
 */

require_once plugin_dir_path(__DIR__) . 'template-parts/header.php'; ?>
    <main class="contentwrapper">
        <section class="fourohfour__section-wrap">
            <article class="fourohfour__body-wrap">
                <h1 class="fourohfour__body-title">404</h1>
                <?php do_action('swp_fourohfour_copy'); ?>
            </article>
        </section>
    </main>
<?php require_once plugin_dir_path(__DIR__) . 'template-parts/footer.php'; ?>