<?php do_action('mosaic_before_footer'); ?>

<footer class="ll__background--dark footer__body-wrap">
    <div class="ll__margins">
        <div class="footer__main-body">
            <div class="footer__main-nav-wrap">
                <?php do_action('lambda_legal_footer_nav'); ?>
            </div>
            <div class="footer__signup-wrap">
                <?php do_action('footer_email_signup'); ?>
            </div>
        </div>
        <div class="footer__sub-section-wrap">
            <div class="footer__sub-section-main-content">
                <ul class="footer__social-list-wrap">
                    <?php do_action('lambda_legal_footer_social'); ?>
                </ul>
                <?php do_action('footer_copyright'); ?>
            </div>
            <div class="footer__sub-section-icon-wrap">
                <?php do_action('footer_icon'); ?>
            </div>
        </div>
    </div>
</footer>
<?php
do_action('mosaic_after_footer');
/**
 * This function returns all of the content added via themes, plugins, and WP Core.
 * This should always live just before the closing body tag.
 */ wp_footer();
?>
<script>
    AOS.init({
        once: true,
    });
</script>
</body>
</html>
