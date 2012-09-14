<?php 

/**
 *
 * shortcodes.php
 *
 * Used to add custom shortcodes.
 * 
 * To add custom shortcode please use the following code:
 * add_shortcode("my_shortcode_name", "my_shortcode_func");
 * function my_shortcode_func($atts) { // your code here... }
 * 
 * More detailed information about shortcodes: http://codex.wordpress.org/Shortcode_API
 * 
 */	

function theme_subscribe_rss(){
	return '<a class="button rss-subscribe" href="'. get_bloginfo('rss2_url') .'" title="'. __('RSS Feeds',THEME_NS) .'">'. __('RSS Feeds',THEME_NS) .'</a>';
}

// ads
function theme_advertisement($atts){
	extract(shortcode_atts(array(
		'code' => 1,
		'align' => 'left',
		'inline' => 0
	), $atts));
	$ad = theme_get_option('theme_ad_code_'.$code);
	if(!empty($ad)):
		$ad = '<div class="ad align'.esc_attr($align).'">'.$ad.'</div>';
		if(!$inline) $ad .= '<div class="cleared"></div>';
		return $ad;
	else:
		return '<p class="error"><strong>[ad]</strong> '.sprintf(__("Empty ad slot (#%s)!",THEME_NS),esc_attr($code)).'</p>';
	endif;
}

function theme_go_to_top(){
	return sprintf('<a class="button" href="#">'.__('Top',THEME_NS).'</a>');
}


// login
function theme_login_link(){
	if (is_user_logged_in()) return sprintf('<a class="login-link" href="%1$s">%2$s</a>',admin_url(),__('Site Admin', THEME_NS));
	else return sprintf('<a class="login-link" href="%1$s">%2$s</a>',wp_login_url(),__('Log in', THEME_NS));
}

// blog title
function theme_blog_title(){
	return '<span class="blog-title">' . get_bloginfo('name') . '</span>';
}

// validate xhtml
function theme_validate_xhtml(){
	return '<a class="button valid-xhtml" href="http://validator.w3.org/check?uri=referer" title="Valid XHTML">XHTML 1.1</a>';
}

// validate css
function theme_validate_css(){
	return '<a class="button valid-css" href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3" title="Valid CSS">CSS 3.0</a>';
}

// current year
function theme_current_year(){
	return date('Y');
}

add_shortcode('year', 'theme_current_year');
add_shortcode('rss', 'theme_subscribe_rss');
add_shortcode('ad', 'theme_advertisement');
add_shortcode('top', 'theme_go_to_top');
add_shortcode('login-link', 'theme_login_link');
add_shortcode('blog-title', 'theme_blog_title');
add_shortcode('xhtml', 'theme_validate_xhtml');
add_shortcode('css', 'theme_validate_css');

add_filter('widget_text', 'do_shortcode'); // Allow [SHORTCODES] in Widgets
?>