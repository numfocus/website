<?php 

function theme_get_menu($args = '')
{
	$args = wp_parse_args( $args, array('source' => 'Pages', 'depth' => 0, 'menu' => null, 'class' => ''));
	$source = &$args['source'];
	$menu = &$args['menu'];
	$class = &$args['class'];
	if (function_exists('get_nav_menu_locations') && $menu != null && is_string($menu)) { // theme location
		$location = theme_get_array_value(get_nav_menu_locations(), $menu);
		if ($location) {
			$menu = wp_get_nav_menu_object($location);
			if($menu) {
				$source = 'Custom Menu';
				$class = implode(' ', array($class, 'menu-'.$menu->term_id));
			}
		} 
	}

	if ($source == 'Custom Menu' && function_exists('wp_nav_menu')  && $menu != null) {
		return theme_get_list_menu($args); 
	} 
	
	if ($source == 'Pages') {
		return theme_get_list_pages(array_merge(array('sort_column' => 'menu_order, post_title'), $args)); 
	}
	
	if ($source == 'Categories') {
		return theme_get_list_categories(array_merge(array('title_li'=> false), $args));
	}
}

/* custom menu */
function theme_get_list_menu($args = array()) {
	global $wp_query;
	$menu_items = wp_get_nav_menu_items($args['menu']->term_id);
	if(empty($menu_items)) return ''; 
	$home_page_id = (int) get_option('page_for_posts');
	$queried_object = $wp_query->get_queried_object();
	$queried_object_id = (int) $wp_query->queried_object_id;
    $active_ID  = null;
    
    $IdToKey = array();
    foreach ( (array) $menu_items as $key => $menu_item ) {
        $IdToKey[$menu_item->ID] = $key;
        if ($menu_item->object_id == $queried_object_id && 
        	(
        		( ! empty( $home_page_id ) && 'post_type' == $menu_item->type && $wp_query->is_home && $home_page_id == $menu_item->object_id ) ||
				( 'post_type' == $menu_item->type && $wp_query->is_singular ) ||
				( 'taxonomy' == $menu_item->type && ( $wp_query->is_category || $wp_query->is_tag || $wp_query->is_tax ))
			)
		) 
		{
			$active_ID = $menu_item->ID;
		} elseif ( 'custom' == $menu_item->object ) {
			if ( theme_is_current_url($menu_item->url)) {
				$active_ID = $menu_item->ID;
			}
		}
	}

	$current_ID = $active_ID;
    while ($current_ID && isset($IdToKey[$current_ID])) {
        $activeIDs[] = $current_ID;
        $current_item = &$menu_items[$IdToKey[$current_ID]];
        $current_item->classes[] = 'active';
        $current_ID = $current_item->menu_item_parent;
    }

	$sorted_menu_items = array();
	foreach ((array) $menu_items as $key => $menu_item) {
		$sorted_menu_items[$menu_item->menu_order] = wp_setup_nav_menu_item($menu_item);
	}

	$items = array();
	foreach ($sorted_menu_items as $el) {
		$id = $el->db_id;
		$title = $el->title;
		$classes = empty( $el->classes ) ? array() : (array) $el->classes;
		$active = in_array('active', $classes);	
		$items[] = new theme_MenuItem(array(
				'id' => $id,
				'active' => $active,
				'attr' => array(
					'title' => strip_tags(empty($el->attr_title) ? $title : $el->attr_title),
					'target' => $el->target,
					'rel' => $el->xfn,
					'href' => $el->url,
					'class' => join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $el))
				),
				'title' => $title,
				'parent' => $el->menu_item_parent
			)); 
	}
	
	$walker = new theme_MenuWalker();
	$items = $walker->walk($items, $args);
	$items = apply_filters('wp_nav_menu_items', $items, $args);
	return apply_filters('wp_nav_menu', $items, $args);
}

/* pages */
function theme_get_list_pages($args = array()) {
	global $wp_query;
	$pages = &get_pages($args);
	if (empty($pages)) return '';
	
	$IdToKey = array();
	$currentID = null;

	foreach ($pages as $key => $page)
	{
		$IdToKey[$page->ID] = $key;
	}

	if ($wp_query->is_page) {
		$currentID = $wp_query->get_queried_object_id();
	}

	$frontID = null;
	$blogID = null;
	
	if ('page' == get_option('show_on_front')) {

		$frontID = get_option('page_on_front');
		if ($frontID && isset($IdToKey[$frontID])) {
			$frontKey = $IdToKey[$frontID];
			$frontPage = $pages[$frontKey];
			unset($pages[$frontKey]);
			$frontPage->post_parent = 0;
			$frontPage->menu_order = 0;
			array_unshift($pages, $frontPage);
			$IdToKey = array();
			foreach ($pages as $key => $page)
			{
				$IdToKey[$page->ID] = $key;
			}
		}

		if (is_home()) {
			$blogID = get_option('page_for_posts');
			if ($blogID && isset($IdToKey[$blogID])) 
			{
				$currentID = $blogID;
			}
		}
	}

	$active_Id = $currentID;
	$activeIDs = array();
	while($active_Id && isset($IdToKey[$active_Id]))
	{
		$active = $pages[$IdToKey[$active_Id]];
		if ($active && $active->post_status == 'private') break;
		$activeIDs[] = $active->ID;
		$active_Id = $active->post_parent;
	}
	
	$items = array();
	if (theme_get_option('theme_menu_showHome') && ('page' != get_option('show_on_front') || (!get_option('page_on_front') && !get_option('page_for_posts'))))
	{	
		$title = theme_get_option('theme_menu_homeCaption');
		$active = is_home();
		$items[] = new theme_MenuItem(array(
				'id' => 'home',
				'active' => $active,
				'attr' => array('class' => ($active ? 'active' : ''), 'href' => get_home_url(), 'title' => strip_tags($title)),
				'title' => $title,
			)); 
	}
	foreach ($pages as $page) {
		$id = $page->ID;
		$title = $page->post_title;
		$active = in_array($id, $activeIDs); 
		$href = (($frontID && $frontID == $id) ? get_option('home') : get_page_link($id));
		$separator = theme_get_meta_option($id, 'theme_show_as_separator');
		if ($separator) {
			$href = '#';
		}
		$items[] = new theme_MenuItem(array(
				'id' => $id,
				'active' => $active,
				'attr' => array('class' => ($active ? 'active' : ''), 'href' => $href, 'title' => strip_tags($title)),
				'title' => $title,
				'parent' => $page->post_parent
			)); 
	}
	$walker = new theme_MenuWalker();
	return $walker->walk($items, $args);
}

/* categories */
function theme_get_list_categories($args = array()) {
	global $wp_query, $post;
	$categories = &get_categories($args);
	if (empty($categories)) return '';
	$IdToKey = array();
	foreach ($categories as $key => $category){
		$IdToKey[$category->term_id] = $key;
	}

	$currentID = null;
	if ($wp_query->is_category)
	{
		$currentID = $wp_query->get_queried_object_id();
	}

	$activeID = $currentID;
	$activeIDs = theme_get_category_branch($currentID, $categories, $IdToKey);
	if(theme_get_option('theme_menu_highlight_active_categories') && is_single()){
		foreach((get_the_category($post->ID)) as $cat) { 
			$activeIDs = array_merge($activeIDs, theme_get_category_branch($cat->term_id, $categories, $IdToKey));
		} 
	}
	$items = array();
	foreach ($categories as $category) {
		$id = $category->term_id;
		$title = $category->name;
		$desc = (($category->description) ?  $category->description : sprintf(__('View all posts in %s', THEME_NS), $title));
		$active = in_array($id, $activeIDs);
		$items[] = new theme_MenuItem(array(
				'id' => $id,
				'active' => $active,
				'attr' => array('class' => ($active ? 'active' : ''), 'href' => get_category_link($id), 'title' => strip_tags($desc)),
				'title' => $title,
				'parent' => $category->parent
			)); 
	}
	$walker = new theme_MenuWalker();
	return $walker->walk($items, $args);
}

//Helper, return array( 'id', 'parent_id', ... , 'root_id' )
function theme_get_category_branch($id, $categories, $IdToKey) {
	$result = array();
	while ($id && isset($IdToKey[$id])) {
		$result[] = $id;	
		$id = $categories[$IdToKey[$id]]->parent;
  	}
  	return $result;
}


/* menu item */
class theme_MenuItem {
	var $id;
	var $active;
	var $parent;
	var $attr;
	var $title;
	
	function theme_MenuItem($args = '') {
		$args = wp_parse_args($args, 
			array(
				'id' => '',
				'active' => false,
				'parent' => 0,
				'attr' => '',
				'title' => '',
			)
		);
		$this->id = $args['id'];
		$this->active = $args['active'];
		$this->parent = $args['parent'];
		$this->attr = $args['attr'];
		$this->title = $args['title'];
	}
	
	function get_start($level){
		$class = theme_get_array_value($this->attr, 'class', '');
		$class = 'menu-item-' . $this->id . (strlen($class) > 0  ? ' ' : '') . $class;
		$this->attr['class'] = ($this->active ? 'active' : null);
		$title = strip_tags(apply_filters('the_title', $this->title, $this->id));
		if (theme_get_option('theme_menu_trim_title')) {
			$title = theme_trim_long_str($title, theme_get_option($level == 0 ? 'theme_menu_trim_len' : 'theme_submenu_trim_len'));
		}
		return str_repeat("\t", $level+1) 
				. '<li' . theme_prepare_attr(array('class' => $class)) . '>' 
				. '<a' . theme_prepare_attr($this->attr) . '>'  
				. $title
				. '</a>'. "\n";
	}
	
	function get_end($level){
		return str_repeat("\t", $level+1) . '</li>' . "\n";
	}
}

/* menu walker */
class theme_MenuWalker {
	
	var $child_Ids = array();
	var $IdToKey = array();
	var $level = 0;
	var $items;
	var $depth;
	var $args;
	var $class;
	
	function walk($items = array(), $args = '') {
		$args = wp_parse_args($args, array('depth' => 0, 'class' => ''));
		$this->items = &$items;
		$this->depth = (int)$args['depth'];
		$this->class = $args['class'];
		foreach($items as $key => $item) {
			$this->IdToKey[$item->id] = $key;
			if (!isset($this->child_Ids[$item->parent])) {
				$this->child_Ids[$item->parent] = array();
			}
			$parent = $item->parent;
			if (!$parent) $parent = 0;
			$this->child_Ids[$parent][] = $item->id;
		}
		
		$output = '';
		if (isset($this->child_Ids[0])) {
			$this->display($output, $this->child_Ids[0]);
		}
		if (theme_is_empty_html($output)) return '';
		return 	"\n" .'<ul' .theme_prepare_attr(array('class' => $this->class)) .'>' ."\n" .$output .'</ul>'."\n";
	}
	
	function display(&$output, $child_Ids) {
		if (!is_array($child_Ids)) return;
		$first = true;
		foreach($child_Ids as $child_Id){
			if (!isset($this->IdToKey[$child_Id])) continue;
			$item = $this->items[$this->IdToKey[$child_Id]];
			$output .= $item->get_start($this->level);
			if (
				($this->depth == 0 ||  $this->level < ($this->depth-1)) 
				&& isset($this->child_Ids[$item->id]) 
				&& (count($this->child_Ids[$item->id]) > 0)
			) {
				$this->level++;
				$output .= str_repeat("\t", $this->level) . '<ul' . theme_prepare_attr(array('class' => ($item->active ? 'active' : ''))) . '>' . "\n";
				$this->display($output, $this->child_Ids[$item->id]);
				$output .= str_repeat("\t", $this->level) . '</ul>' . "\n";
				$this->level--;
			}
			$output .= $item->get_end($this->level);
			$first = false;
		}
	}
	
}

// Not support old wp version
if (WP_VERSION < 3.0) return;
function theme_get_pages( $pages ) {
	if(is_admin()) return $pages;

	$excluded_option = get_option('theme_show_in_menu');
	if (!$excluded_option || !is_array($excluded_option)) return $pages;
	$excluded_ids = array();
	foreach ($excluded_option as $id => $show)
	{
		if(!$show) {
			$excluded_ids[] = $id;		
		}
	}
	$excluded_parent_ids = array();
	foreach ($pages as $page) {
		
		$title = theme_get_meta_option($page->ID, 'theme_title_in_menu');
		if ($title) {
			$page->post_title = $title;
		}
		
		if (in_array($page->ID, $excluded_ids)){
			$excluded_parent_ids[$page->ID] = $page->post_parent;
		}
	}

	$length = count($pages);
	for ( $i=0; $i<$length; $i++ ) {
		$page = & $pages[$i];
		if (in_array($page->post_parent, $excluded_ids)) {
			$page->post_parent = theme_get_array_value($excluded_parent_ids, $page->post_parent, $page->post_parent);
		}
		if (in_array($page->ID, $excluded_ids)) {
			unset( $pages[$i] );
		}
	}

	if ( ! is_array( $pages ) ) $pages = (array) $pages;
	$pages = array_values( $pages );

	return $pages;
}
add_filter('get_pages','theme_get_pages');