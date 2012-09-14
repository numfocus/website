<?php if ( !theme_dynamic_sidebar('bottom') ) : ?>
<?php $style = theme_get_option('theme_sidebars_style_bottom'); ?>
<?php $heading = theme_get_option('theme_'.(is_single()?'single':'posts').'_widget_title_tag'); ?>



<?php endif; ?>