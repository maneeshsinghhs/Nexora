<?php
define('WP_INSTALLING', true);
$_SERVER['HTTP_HOST'] = 'localhost:8080';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
require '/var/www/html/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/upgrade.php';
if (!is_blog_installed()) {
    wp_install('Nexora IT', 'nexora_test', 'admin@example.com', true, '', wp_generate_password(32));
}
update_option('blogname', 'Nexora IT');
update_option('blogdescription', 'Thoughtful technology. Lasting impact.');
switch_theme('nexora-it');
$sample = json_decode(file_get_contents('/tmp/nexora-content.json'), true);
foreach ($sample as $entry) {
    $existing = get_page_by_path($entry['post_name'], OBJECT, $entry['post_type']);
    if (!$existing) { wp_insert_post($entry); }
}
$home = get_page_by_path('home');
$blog = get_page_by_path('blog');
update_option('show_on_front', 'page');
update_option('page_on_front', $home->ID);
update_option('page_for_posts', $blog->ID);
update_option('permalink_structure', '/%postname%/');
flush_rewrite_rules();
echo 'WordPress ' . get_bloginfo('version') . "; theme: " . get_stylesheet() . "; starter content installed.\n";
