<?php

function theme_strlen($str){
	if (function_exists('mb_strlen')){
		return mb_strlen($str);
	}
	return strlen($str);
}

function theme_strpos($source, $target){
	if (function_exists('mb_strpos')){
		return mb_strpos($source, $target);
	}
	return strpos($source, $target);
}

function theme_get_array_value($arr = array(), $key = null, $def = false){
	if (is_array($arr) && @isset($arr[$key])){
		return $arr[$key];
	}
	return $def;
}

function theme_is_empty_html($str){
	return (!is_string($str) || theme_strlen(str_replace(array('&nbsp;', ' ', "\n", "\r", "\t"), '', $str)) == 0);
}

function theme_is_vmenu_widget($id){
	return (strpos($id, 'vmenu') !== false);
}

function theme_trim_long_str($str, $len = 50, $sep = ' '){
	$words = explode($sep, $str);
	$wcount = count($words);
	while( $wcount > 0 && theme_strlen(join($sep, array_slice($words, 0, $wcount))) > $len) $wcount--;
	if ($wcount != count($words)) {
		$str = join($sep, array_slice($words, 0, $wcount)) . '&hellip;';
	}
	return $str;
}


function theme_get_current_url() {
	$pageURL = 'http';
	if (is_ssl()) {
		$pageURL .= 's';
	}
	$pageURL .= '://' . $_SERVER['SERVER_NAME'];
	if ($_SERVER['SERVER_PORT'] != '80') {
		$pageURL .= ':' . $_SERVER["SERVER_PORT"];
	} 
	$pageURL .= $_SERVER["REQUEST_URI"];
	return $pageURL;
}

function theme_remove_last_slash($url) {
	$len = theme_strlen($url);
	if ( $len > 0 && $url[$len-1] == '/') {
		$url =  substr($url, 0, -1);
	}
	return $url;
}

function theme_is_current_url($url) {
	// remove # anchor 
	if (strpos( $url, '#' )) {
		$url =  substr($url, 0, strpos( $url, '#'));
	}
	
	$url = theme_remove_last_slash($url);
	$cur = theme_remove_last_slash(theme_get_current_url());
	
	// compare
	return ($cur == $url);
}

function theme_prepare_attr($attr = array()) {
	$attr = wp_parse_args($attr);
	if (count($attr)  == 0) return '';
	$result = '';
	foreach($attr as $name => $value){
		if(empty($name) || empty($value)) continue;
		$result .= ' ' . strtolower($name) . '="' . esc_attr($value) . '"';
	}
	return $result;
}
