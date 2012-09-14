<?php if ( !theme_dynamic_sidebar( 'top' ) ) : ?>
<?php $style = theme_get_option('theme_sidebars_style_top'); ?>
<?php $heading = theme_get_option('theme_'.(is_single()?'single':'posts').'_widget_title_tag'); ?>



<?php endif; ?>