<?php if (!defined('ABSPATH')) { exit; } ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e('Skip to content', 'nexora-it'); ?></a>
<header class="site-header"><div class="container header-inner">
<div class="brand"><?php if (has_custom_logo()) { the_custom_logo(); } else { ?><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><span class="brand-mark" aria-hidden="true">n</span><?php echo esc_html(get_bloginfo('name')); ?><span class="brand-dot">.</span></a><?php } ?></div>
<button class="menu-toggle" aria-controls="primary-navigation" aria-expanded="false" hidden><?php esc_html_e('Menu', 'nexora-it'); ?></button>
<nav id="primary-navigation" aria-label="<?php esc_attr_e('Primary navigation', 'nexora-it'); ?>"><?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'menu_class' => 'nav-links', 'fallback_cb' => 'nexora_fallback_menu')); ?></nav>
<a class="button small header-cta" href="<?php echo esc_url(home_url('/#contact')); ?>"><?php esc_html_e('Let’s talk', 'nexora-it'); ?> <span aria-hidden="true">→</span></a>
</div></header>
