<?php

/**
 *
 * content*.php
 *
 * The post format template. You can change the structure of your posts or add/remove post elements here.
 * 
 * 'id' - post id
 * 'class' - post class
 * 'thumbnail' - post icon
 * 'title' - post title
 * 'before' - post header metadata
 * 'content' - post content
 * 'after' - post footer metadata
 * 
 * To create a new custom post format template you must create a file "content-YourTemplateName.php"
 * Then copy the contents of the existing content.php into your file and edit it the way you want.
 * 
 * Change an existing get_template_part() function as follows:
 * get_template_part('content', 'YourTemplateName');
 *
 */	

	global $post;
	/* Display navigation to next/previous pages when applicable */
	if (!empty( $post->post_parent ) ) {
		$return_link = '<a href="'. get_permalink( $post->post_parent ) .'" title="'.esc_attr(sprintf( __('Return to %s', THEME_NS), strip_tags(get_the_title($post->post_parent)))) .'" rel="gallery">'
			.sprintf( __( '<span class="meta-nav">&larr;</span> %s', THEME_NS ), get_the_title( $post->post_parent ) ) . '</a>';
		theme_page_navigation(array('next_link' => $return_link));
	}
	
	ob_start();
	
	if ( wp_attachment_is_image() ) {
		$attachments = array_values(get_children(array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID')));
		foreach ( $attachments as $k => $attachment ) {
			if ( $attachment->ID == $post->ID )
				break;
		}
		$k++;
		$next_attachment_url = '';
		// If there is more than 1 image attachment in a gallery
		if ( count( $attachments ) > 1 ) {
			if ( isset( $attachments[ $k ] ) )
				// get the URL of the next image attachment
				$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
			else
				// or get the URL of the first image attachment
				$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
		} else {
			// or, if there's only 1 image attachment, get the URL of the image
			$next_attachment_url = wp_get_attachment_url();
		}
		
		?>
		<p class="attachment center">
			<a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr( strip_tags(get_the_title()) ); ?>" rel="attachment">
				<?php
				$attachment_size = apply_filters( 'attachment_size', 600 );
				echo wp_get_attachment_image( $post->ID, array( $attachment_size, 9999 ) ); // filterable image width with, essentially, no limit for image height.
				?>
			</a>
		</p>
		<?php	
	} else {
		?>
			<p class="attachment center">
				<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php echo esc_attr(strip_tags( get_the_title()) ); ?>" rel="attachment">
					<?php echo basename( get_permalink() ); ?>
				</a>
			</p>
		<?php	
	}	

	echo theme_get_content();

	if ( wp_attachment_is_image() ) {
				$metadata = wp_get_attachment_metadata();
				echo '<p class="center">' . sprintf( __( 'Full size is %s pixels', THEME_NS),
					sprintf( '<a href="%1$s" title="%2$s">%3$s &times; %4$s</a>',
						wp_get_attachment_url(),
						esc_attr( __('Link to full-size image', THEME_NS) ),
						$metadata['width'],
						$metadata['height']
					)
				) . '</p>';
	}
	
	/* Display navigation to next/previous pages when applicable */
	theme_page_navigation(array('wrap' => false, 'prev_link' => theme_get_next_image_link(false), 'next_link' => theme_get_previous_image_link(false)));
	
	theme_post_wrapper(
		array(
				'id' => theme_get_post_id(), 
				'class' => theme_get_post_class(),
				'title' => '<a href="'.get_permalink($post->ID).'" rel="bookmark" title="'.strip_tags(get_the_title()).'">'.get_the_title().'</a>', 
        'heading' => theme_get_option('theme_'.(is_single()?'single':'posts').'_article_title_tag'),
				'before' => theme_get_metadata_icons('date,author,edit', 'header'),
				'content' =>ob_get_clean(),
		)
	);
?>