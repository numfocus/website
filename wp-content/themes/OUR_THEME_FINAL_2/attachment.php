<?php 

/**
 *
 * attachment.php
 *
 * Attachment template. Used when viewing a single attachment.
 *
 */

get_header(); ?>
<div class="art-layout-wrapper">
    <div class="art-content-layout">
        <div class="art-content-layout-row">
            <div class="art-layout-cell art-content">
			<?php get_sidebar('top'); ?>
			<?php 
				if (have_posts()){
					the_post();
					get_template_part('content', 'attachment');
					/* Display comments */
					if ( theme_get_option('theme_allow_comments')) {
						comments_template();
					}
				} else {
					theme_404_content();
				}
			?>				
			<?php get_sidebar('bottom'); ?>
              <div class="cleared"></div>
            </div>
        </div>
    </div>
</div>
<div class="cleared"></div>
<?php get_footer(); ?>