<?php 

/**
 *
 * search.php
 *
 * The search results template. Used when a search is performed.
 *
 */

get_header(); ?>
<div class="art-layout-wrapper">
    <div class="art-content-layout">
        <div class="art-content-layout-row">
            <div class="art-layout-cell art-content">
			<?php get_sidebar('top'); ?>
			<?php 
				if(have_posts()) {
				
					theme_post_wrapper(
			  			array('content' => '<h4 class="box-title">' . sprintf( __( 'Search Results for: %s', THEME_NS ), 
			  				'<span class="search-query-string">' . get_search_query() . '</span>' ) . '</h4>' 
			  			)
			  		);
				
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
					theme_404_content(
						array(
							'error_title' => __('Nothing Found', THEME_NS),
							'error_message' => __('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', THEME_NS)
						)
					);
					 
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
<?php get_footer(); ?>