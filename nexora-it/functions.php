<?php
if (!defined('ABSPATH')) { exit; }

function nexora_setup() {
    load_theme_textdomain('nexora-it', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array('height' => 64, 'width' => 240, 'flex-height' => true, 'flex-width' => true));
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('automatic-feed-links');
    add_theme_support('editor-styles');
    add_editor_style('assets/site.css');
    register_nav_menus(array('primary' => __('Primary navigation', 'nexora-it'), 'footer' => __('Footer navigation', 'nexora-it')));
}
add_action('after_setup_theme', 'nexora_setup');

function nexora_assets() {
    wp_enqueue_style('nexora', get_template_directory_uri() . '/assets/site.css', array(), '1.0.0');
    wp_enqueue_script('nexora', get_template_directory_uri() . '/assets/site.js', array(), '1.0.0', true);
    $color = sanitize_hex_color(get_theme_mod('nexora_accent', '#087f75')) ?: '#087f75';
    wp_add_inline_style('nexora', ':root{--accent:' . $color . ';}');
    if (is_singular() && comments_open() && get_option('thread_comments')) { wp_enqueue_script('comment-reply'); }
}
add_action('wp_enqueue_scripts', 'nexora_assets');

function nexora_defaults() {
    return array(
        'eyebrow' => 'YOUR PARTNER IN WHAT’S NEXT',
        'headline' => 'Technology that moves your business forward.',
        'intro' => 'From your first idea to your next big milestone, we build reliable digital solutions that help your business grow.',
        'email' => 'hello@example.com', 'phone' => '+1 (555) 010-2020',
        'address' => 'Your city, your country',
        'about_title' => 'Good technology starts with understanding your business.',
        'about_text' => 'We bring strategy, design, and engineering together to solve real business challenges. You get a collaborative team, clear communication, and solutions built for the long term.',
        'service_1_title' => 'Software development', 'service_1_text' => 'Purpose-built web and mobile applications that turn complex challenges into simple experiences.',
        'service_2_title' => 'Cloud & infrastructure', 'service_2_text' => 'Modern infrastructure, smooth migrations, and dependable systems that grow with your business.',
        'service_3_title' => 'Cybersecurity', 'service_3_text' => 'Practical security assessments and safeguards that help protect your people, data, and operations.',
        'service_4_title' => 'IT consulting', 'service_4_text' => 'A clear technology roadmap, expert guidance, and the right decisions for your next chapter.',
    );
}
function nexora_value($key) { $defaults = nexora_defaults(); return get_theme_mod('nexora_' . $key, $defaults[$key] ?? ''); }

function nexora_customize($manager) {
    $manager->add_section('nexora_content', array('title' => __('IT Company Content', 'nexora-it'), 'priority' => 30, 'description' => __('Replace the sample contact details before publishing. Homepage sections are edited here; additional pages use the block editor.', 'nexora-it')));
    foreach (nexora_defaults() as $key => $default) {
        $is_long = in_array($key, array('intro', 'about_text')) || str_ends_with($key, '_text');
        $manager->add_setting('nexora_' . $key, array('default' => $default, 'sanitize_callback' => $key === 'email' ? 'sanitize_email' : ($is_long ? 'sanitize_textarea_field' : 'sanitize_text_field')));
        $manager->add_control('nexora_' . $key, array('label' => ucwords(str_replace('_', ' ', $key)), 'section' => 'nexora_content', 'type' => $is_long ? 'textarea' : ($key === 'email' ? 'email' : 'text')));
    }
    $manager->add_setting('nexora_accent', array('default' => '#087f75', 'sanitize_callback' => 'sanitize_hex_color'));
    $manager->add_control(new WP_Customize_Color_Control($manager, 'nexora_accent', array('label' => __('Accent color (choose a dark shade for readable buttons)', 'nexora-it'), 'section' => 'nexora_content')));
}
add_action('customize_register', 'nexora_customize');

function nexora_fallback_menu() {
    echo '<ul class="nav-links">';
    foreach (array('services' => __('Services', 'nexora-it'), 'about' => __('About', 'nexora-it'), 'process' => __('Our approach', 'nexora-it'), 'insights' => __('Insights', 'nexora-it')) as $anchor => $label) {
        echo '<li><a href="' . esc_url(home_url('/#' . $anchor)) . '">' . esc_html($label) . '</a></li>';
    }
    echo '</ul>';
}

function nexora_contact() {
    $redirect = home_url('/');
    $finish = static function ($status) use ($redirect) { wp_safe_redirect(add_query_arg('contact', $status, $redirect) . '#contact'); exit; };
    foreach (array('nexora_nonce', 'website', 'name', 'email', 'message') as $field) {
        if (isset($_POST[$field]) && !is_string($_POST[$field])) { $finish('invalid'); }
    }
    if (!isset($_POST['nexora_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nexora_nonce'])), 'nexora_contact')) { $finish('error'); }
    if (!empty($_POST['website'])) { $finish('sent'); }
    $name = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));
    if (!$name || !is_email($email) || strlen($message) < 10 || strlen($message) > 5000 || strlen($name) > 120) { $finish('invalid'); }
    $key = 'nexora_mail_' . hash('sha256', ($_SERVER['REMOTE_ADDR'] ?? '') . wp_salt());
    if (get_transient($key)) { $finish('wait'); }
    set_transient($key, 1, MINUTE_IN_SECONDS);
    // The site's verified admin mailbox receives inquiries. Never trust a submitted recipient.
    $sent = wp_mail(get_option('admin_email'), __('New website inquiry', 'nexora-it'), "Name: $name\nEmail: $email\n\n$message", array('Reply-To: ' . $email));
    $finish($sent ? 'sent' : 'error');
}
add_action('admin_post_nexora_contact', 'nexora_contact');
add_action('admin_post_nopriv_nexora_contact', 'nexora_contact');
