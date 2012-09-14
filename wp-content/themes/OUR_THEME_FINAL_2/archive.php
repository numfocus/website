<?php

/**
 *
 * archive.php
 *
 * The archive template. Used when a category, author, or date is queried.
 * Note that this template will be overridden by category.php, author.php, and date.php for their respective query types. 
 *
 * More detailed information about template’s hierarchy: http://codex.wordpress.org/Template_Hierarchy
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
				
					global $posts;
					$post = $posts[0];
					
					ob_start();
			
					if (is_category()) {
					
						echo '<h4>'. single_cat_title( '', false ) . '</h4>';
						echo category_description();
						
					} elseif( is_tag() ) {
					
						echo '<h4>'. single_tag_title('', false) . '</h4>';
						
					} elseif( is_day() ) {
					
						echo '<h4>'. sprintf(__('Daily Archives: <span>%s</span>', THEME_NS), get_the_date()) . '</h4>';
						
					} elseif( is_month() ) {
					
						echo '<h4>'. sprintf(__('Monthly Archives: <span>%s</span>', THEME_NS), get_the_date('F Y')) . '</h4>';
						
					} elseif( is_year() ) {
					
						echo '<h4>'. sprintf(__('Yearly Archives: <span>%s</span>', THEME_NS), get_the_date('Y')) . '</h4>';
						
					} elseif( is_author() ) {
					
						the_post();
						echo theme_get_avatar(array('id' => get_the_author_meta('user_email')));
						echo '<h4>'. get_the_author() . '</h4>';
						$desc = get_the_author_meta('description');
						if ($desc) echo '<div class="author-description">' . $desc . '</div>';
						rewind_posts();
						
					} elseif( isset($_GET['paged']) && !empty($_GET['paged']) ) {
					
						 echo '<h4>'. __('Blog Archives', THEME_NS) . '</h4>';
						 
					}
					theme_post_wrapper(array('content' => ob_get_clean(), 'class' => 'breadcrumbs'));
					
					/* Display navigation to next/previous pages when applicable */
					if (theme_get_option('theme_top_posts_navigation')) {
						theme_page_navigation();
					}
					
					/* Start the Loop */ 
					while (have_posts()) {
						the_post();
						get_template_part('content', get_post_format());
					}
						
					/* Display navigation to next/previous pages when applicable */
					if (theme_get_option('theme_bottom_posts_navigation')) {
						theme_page_navigation();
					}
						
				} else {  
					  
					theme_404_content();
					
				} 
			?>
			<?php get_sidebar('bottom'); ?>
              <div class="cleared"></div>
            </div>
            <div class="art-layout-cell art-sidebar1">
              <?php get_sidebar('default'); ?>
              <div class="cleared"></div>
            </div>
        </div>
    </div>
</div>
<div class="cleared"></div>
<?php get_footer();
