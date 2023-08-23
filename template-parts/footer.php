<?php do_action('mosaic_before_footer'); ?>

<footer class="swp__background--purple footer__body-wrap">
    <div class="footer__main-body">
        <div class="swp__margins">
            <div class="footer__main-nav-wrap">
                <?php do_action('swp_footer_nav'); ?>
            </div>
            <div class="footer__main-signup-wrap">
                <?php do_action('swp_footer_email_signup'); ?>
            </div>
        </div>
    </div>
    <div class="footer__logos-body-wrap">
        <div class="swp__margins">
            <?php do_action('swp_footer_logo'); ?>
        </div>
    </div>
    <div class="footer__social-body-wrap">
        <ul class="swp__margins footer__social-list-wrap">
            <?php do_action('swp_footer_social'); ?>
        </ul>
        <?php do_action('swp_footer_copyright'); ?>
    </div>
</footer>
<?php
do_action('mosaic_after_footer');
/**
 * This function returns all of the content added via themes, plugins, and WP Core.
 * This should always live just before the closing body tag.
 */ wp_footer();
?>
</body>
</html>
