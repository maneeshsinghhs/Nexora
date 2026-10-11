<?php
if (!defined('ABSPATH') || post_password_required()) { return; }
if (have_comments()) { ?><section class="comments"><h2><?php esc_html_e('Conversation', 'nexora-it'); ?></h2><ol class="comment-list"><?php wp_list_comments(array('style' => 'ol', 'short_ping' => true)); ?></ol><?php the_comments_navigation(); ?></section><?php }
comment_form();
