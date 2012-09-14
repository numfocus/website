<?php

// comments
if(!function_exists('wp_list_comments')) { // WP 2.7- only check	
	function theme_legacy_comments($file) {	
		return TEMPLATEPATH.'/legacy-comments.php';	
	}
	add_filter('comments_template', 'theme_legacy_comments');
}

// widgets
function theme_vmenu_widget($args) { // for wp < 3.0
	extract($args);
	$source = theme_get_option('theme_vmenu_source');
	echo $before_widget;
	echo $before_title . __($source, THEME_NS) . $after_title;
	echo theme_get_menu(array(
			'source' => $source,
			'depth' => theme_get_option('theme_vmenu_depth'),
			'class' => 'art-vmenu'
		));
	echo $after_widget;
}

// init widgets
function artWidgetsInit(){
	if ( function_exists('register_sidebar_widget') ) {
		register_sidebar_widget('vmenu', 'vmenu_widget');
	}
}
add_action('widgets_init', 'artWidgetsInit');



// functions
if (!function_exists('get_home_url')) {
	function get_home_url() {
		return get_option('home');
	}
}

if (!function_exists('_n')) {
	function _n( $single, $plural, $number, $domain = 'default' ) {
		return __($plural, $domain);
	}
}

if (!function_exists('_x')) {
	function _x() {
		$args = func_get_args();
		$what = array_shift($args); 
		$args[0] = $what.'|'.$args[0];
		return call_user_func_array('_c', $args);
	}
}

if (!function_exists('esc_url'))
{
	function esc_url( $url, $protocols = null, $context = 'display' ) {
		$args = func_get_args();
		return call_user_func_array('clean_url', $args);
	}
}

if (!function_exists('theme_get_widget_style')) {
	function theme_get_widget_style($id, $style = null)
	{
		if(theme_is_vmenu_widget($id)) return 'vmenu';
		$result = 'default'; 
		if ($style != null) {
			if (!in_array($style,array('block', 'post', 'simple'))) {
				$style = 'block';		
			}
			if($result == 'default') { 
				$result = $style;
			}
		}
		return $result;
	}
}

if (!function_exists('get_the_date')) {
	function get_the_date($format = 'F jS, Y') {
		return get_the_time(__($format, THEME_NS));
	}
}

if (!function_exists('esc_attr')) {
	function esc_attr( $text ) {
		return attribute_escape($text);
	}
} 

if (!function_exists('esc_html')) {
	function esc_html( $text ) {
		return wp_specialchars($text);
	}
}
 
if (!function_exists('get_the_author_meta')) {
	function get_the_author_meta($field = '', $user_id = false) {
		if (!user_id) {
			global $authordata;
		} else {
			$authordata = get_userdata($user_id);
		}
		
		$field = strtolower($field);
		$user_field = 'user_' . $field;
		
		if ( 'id' == $field ) {
			$value = isset($authordata->ID) ? (int) $authordata->ID : 0;
		} elseif (isset($authordata->$user_field)) {
			$value = $authordata->$user_field;
		} else {
			$value = isset($authordata->$field) ? $authordate->$field : '';
		}
		
		return apply_filters('get_the_author_' . $field, $value, $user_id);
	}
}

if (!function_exists('get_search_form')) {
	function get_search_form() {
		$search_form_template = theme_locate_template(array('searchform.php'));
		if ( '' == $search_form_template ) return;
		require($search_form_template);
	}
}


if (!function_exists('theme_locate_template')) {
	function theme_locate_template($template_names, $load = false, $require_once = true ) {
		if ( !is_array($template_names) )
			return '';
		
		$located = '';
		foreach ( $template_names as $template_name ) {
			if ( !$template_name )
				continue;
			if ( file_exists(STYLESHEETPATH . '/' . $template_name)) {
				$located = STYLESHEETPATH . '/' . $template_name;
				break;
			} else if ( file_exists(TEMPLATEPATH . '/' . $template_name) ) {
				$located = TEMPLATEPATH . '/' . $template_name;
				break;
			}
		}
		
		if ( $load && '' != $located )
			theme_load_template( $located, $require_once );
		
		return $located;
	}
}

if (!function_exists('theme_load_template')) {
	function theme_load_template( $_template_file, $require_once = true ) {
		global $posts, $post, $wp_did_header, $wp_did_template_redirect, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

		if ( is_array( $wp_query->query_vars ) )
			extract( $wp_query->query_vars, EXTR_SKIP );

		if ( $require_once )
			require_once( $_template_file );
		else
			require( $_template_file );
	}	
}

if (!function_exists('get_template_part')) {
	function get_template_part( $slug, $name = null ) {
		do_action( "get_template_ptheme_{$slug}", $slug, $name );
		
		$templates = array();
		if ( isset($name) )
			$templates[] = "{$slug}-{$name}.php";
		
		$templates[] = "{$slug}.php";
		
		theme_locate_template($templates, true, false);
	}
}

if (!function_exists('get_previous_posts_link')) {
	function get_previous_posts_link($label)
	{
		ob_start();
		previous_posts_link($label);
		return ob_get_clean();
	}
}

if (!function_exists('get_next_posts_link')) {
	function get_next_posts_link($label)
	{
		ob_start();
		next_posts_link($label);
		return ob_get_clean();
	}
}
