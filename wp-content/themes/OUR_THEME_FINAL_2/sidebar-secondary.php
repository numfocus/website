<?php if ( !theme_dynamic_sidebar( 'secondary' ) ) : ?>
<?php $style = theme_get_option('theme_sidebars_style_secondary'); ?>
<?php $heading = theme_get_option('theme_'.(is_single()?'single':'posts').'_widget_title_tag'); ?>



<?php endif; ?>