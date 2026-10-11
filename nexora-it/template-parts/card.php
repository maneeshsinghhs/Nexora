<?php if (!defined('ABSPATH')) { exit; } ?>
<article <?php post_class('post-card'); ?>>
<a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>"><?php if (has_post_thumbnail()) { the_post_thumbnail('medium_large'); } else { ?><div class="post-placeholder" aria-hidden="true">→</div><?php } ?></a>
<div class="post-card-body"><p class="post-meta"><?php echo esc_html(get_the_date()); ?></p><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><?php the_excerpt(); ?><a class="text-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Read article', 'nexora-it'); ?> <span aria-hidden="true">→</span><span class="screen-reader-text">: <?php the_title(); ?></span></a></div></article>
