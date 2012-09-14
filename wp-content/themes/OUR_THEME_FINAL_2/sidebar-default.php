<?php
if ( !theme_dynamic_sidebar( 'default' ) ) : ?>
<?php $style = theme_get_option('theme_sidebars_style_default'); ?>
<?php $heading = theme_get_option('theme_'.(is_single()?'single':'posts').'_widget_title_tag'); ?>

<?php ob_start();?>
      <?php get_search_form(); ?> 
<?php theme_wrapper($style, array('title' => __('Search', THEME_NS), 'heading' => $heading, 'content' => ob_get_clean())); ?>

<?php ob_start();?>
      <ul>
        <?php wp_list_categories('show_count=1&title_li='); ?>
      </ul>
<?php theme_wrapper($style, array('title' => __('Categories', THEME_NS), 'heading' => $heading, 'content' => ob_get_clean())); ?>

<?php ob_start();?><?php 
	echo theme_get_menu(array(
			'source' => theme_get_option('theme_vmenu_source'),
			'depth' => theme_get_option('theme_vmenu_depth'),
			'class' => 'art-vmenu'	
		)
	);
?>
<?php theme_wrapper('vmenu', array('title' => '', 'heading' => $heading, 'content' => ob_get_clean())); ?>

<?php endif; ?>