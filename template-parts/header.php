<!DOCTYPE html>
<html <?php language_attributes(); ?> prefix="og: http://ogp.me/ns#">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
do_action('mosaic_after_body');
MosaicSocialMedia::facebook_sdk();

$site_name = get_bloginfo('name');
$logo_src_url = MosaicTheme::get_option('theme_logo');
$logo_link_url = MosaicTheme::get_option('logo_url');
$logo_link_url = $logo_link_url ? $logo_link_url : get_home_url();
$header_class = ['header'];
$header_class[] = MosaicTheme::get_option('fixed_header') ? 'sticky' : '';
$header_class[] = MosaicTheme::get_option('main_nav_position');
$header_class = trim(implode(' ', $header_class));

do_action('mosaic_before_page_content');
?>
<section class="header__skip-wrap" id="skip">
    <a class="header__skip-cta" href="#main-body">Skip to Main Content</a>
</section>
<div class="header__alert-bar-body">
    <?php do_action('header_alert_bar'); ?>
</div>
<header class="header__body-wrap" id="header">
    <section class="ll__margins header__nav-body-wrap">
        <div class="header__nav-body">
            <a class="header__logo-wrap" href="<?php echo $logo_link_url; ?>">
                <img class="header__logo" src="<?php echo $logo_src_url; ?>" alt="<?php echo $site_name; ?>"/>
            </a>
            <button class="header__menu-btn" id="open-nav" aria-haspopup="dialog">
                <span class="header__menu-btn-line" role="none"></span>
                <span class="header__menu-btn-line" role="none"></span>
                <span class="header__menu-btn-line" role="none"></span>
            </button>
            <nav class="header__nav-content-wrap" aria-label="menu" role="dialog" aria-modal="true" id="nav">
                <?php do_action('lambda_legal_header_nav'); ?>
                <?php do_action('header_donate_button'); ?>
                <?php do_action('lambda_legal_mobile_secondary_nav'); ?>
            </nav>
        </div>
    </section>
</header>
<a class="main-body__anchor" id="main-body"></a>

