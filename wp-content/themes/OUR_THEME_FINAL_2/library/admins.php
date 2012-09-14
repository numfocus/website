<?php

function theme_print_options() {
	global $theme_options;
?>
<div class="wrap">
	<div id="icon-themes" class="icon32"><br /></div>
	<h2><?php _e('Theme Options', THEME_NS); ?></h2>
<?php 
	if ( isset($_REQUEST['Submit']) )	{ 
		foreach ($theme_options as $value) {
			$id = theme_get_array_value($value, 'id');
			$val = stripslashes(theme_get_array_value($_REQUEST, $id));
			$type = theme_get_array_value($value, 'type');
			switch($type){
				case 'checkbox':
					$val = ($val  ? 1 : 0);
				break;
				case 'numeric':
					$val = (int) $val;
				break;
			}
			update_option( $id, $val); 
		}
		echo '<div id="message" class="updated fade"><p><strong>'. __('Settings saved.', THEME_NS) .'</strong></p></div>'."\n"; 
	} 
	if ( isset($_REQUEST['Reset']) )	{ 
	foreach ($theme_options as $value) {
		delete_option(theme_get_array_value($value, 'id')); 
	}
	echo '<div id="message" class="updated fade"><p><strong>'. __('Settings restored.', THEME_NS) . '</strong></p></div>'."\n";
	} 
	echo '<form method="post">'."\n";
	$in_form_table = false;
	foreach ($theme_options as $op) {
		$type = theme_get_array_value($op, 'type');
		$name = theme_get_array_value($op, 'name');
		$desc = theme_get_array_value($op, 'desc');
		if ($type == 'heading'){
			if ($in_form_table) {
				echo '</table>'."\n";
				$in_form_table = false;
			}
			echo '<h3>'.$name.'</h3>'."\n";
			if ($desc) {
				echo "\n".'<p class="description">'.$desc.'</p>'."\n";
			}
		} else {
			if (!$in_form_table) {
				echo '<table class="form-table">'."\n";
				$in_form_table = true;
			}
			echo '<tr valign="top">'."\n";
			echo '<th scope="row">'.$name.'</th>'."\n";
			echo '<td>'."\n";
			$id = theme_get_array_value($op, 'id');
			$val = theme_get_option($id);
			theme_print_option_control($op, $val);
			if ($desc) {
			echo '<span class="description">'.$desc.'</span>'."\n";
			}
			echo '</td>'."\n";
			echo '</tr>'."\n";
		}
	}
	if ($in_form_table) {
	echo '</table>'."\n";
	}
?>
	<p class="submit">
		<input name="Submit" type="submit" class="button-primary" value="<?php echo esc_attr(__('Save Changes', THEME_NS)) ?>" />
		<input name="Reset" type="submit" class="button-secondary" value="<?php echo esc_attr(__('Reset to Default', THEME_NS)) ?>" />
	</p>
	</form>
	</div>
<?php
}


function theme_print_option_control($op, $val){
	$id = theme_get_array_value($op, 'id');
	$type = theme_get_array_value($op, 'type');
	$options = theme_get_array_value($op, 'options');
	switch ( $type) {
		case 'numeric':
		echo '<input	name="'.$id.'" id="'.$id.'" type="text" value="'.absint($val).'" class="small-text" />'."\n";
		break;
		case 'select':
		echo '<select name="'.$id.'" id="'.$id.'">'."\n";
			foreach ($op['options'] as $key	=>	$option) { 
				$selected = ($val == $key ? ' selected="selected"' : '');
				echo '<option'.$selected.' value="'.$key.'">'.esc_html($option).'</option>'."\n"; 
			}
		echo '</select>'."\n";
		break;
		case 'textarea':
		echo '<textarea name="'.$id.'" id="'.$id.'" rows="10" cols="50" class="large-text code">'.esc_html($val).'</textarea><br />'."\n";
		break;
		case "radio":
			foreach ($op['options'] as $key=>$option) {
				$checked = ( $key == $val ? 'checked="checked"' : '');
				echo '<input type="radio" name="'.$id.'" id="'.$id.'" value="'.esc_attr($key).'" '.$checked.'/>'.esc_html($option).'<br />'."\n";
			}
		break;
		case "checkbox":
			$checked =	($val ? 'checked="checked" ' : ''); 
			echo '<input type="checkbox" name="'.$id.'" id="'.$id.'" value="1" '.$checked.'/>'."\n";
		break;
		default:
		$class = 'regular-text';
		if ($type == 'numeric'){
			$type = 'text';
			$class = 'small-text';
			$val = absint($val);
		}
		if ($type == 'widetext') {
			$class = 'large-text';
		}
		echo '<input	name="'.$id.'" id="'.$id.'" type="'.$type.'" value="'.esc_attr($val).'" class="'.$class.'" />'."\n";
		break;
	}
}

// Not support old wp version
if (WP_VERSION < 3.0) return;
 


function theme_add_meta_boxes() {
    add_meta_box( 'theme_meta_box',
                  __('Theme Options', THEME_NS),
                  'theme_print_page_meta_box',
                  'page',
                  'side',
                  'low'
                 );
    add_meta_box( 'theme_meta_box',
                  __('Theme Options', THEME_NS),
                  'theme_print_post_meta_box',
                  'post',
                  'side',
                  'low'
                 );
}

/* Prints meta box content */
function theme_print_page_meta_box($post) {
	global $theme_page_meta_options;
	theme_print_meta_box($post, $theme_page_meta_options);
}

function theme_print_post_meta_box($post) {
	global $theme_post_meta_options;
	theme_print_meta_box($post, $theme_post_meta_options);
}

function theme_print_meta_box($post, $meta_options) {
    // Use nonce for verification
    wp_nonce_field('theme_meta_options', 'theme_meta_noncename');
	if (!isset($post)) return;
	foreach ($meta_options as $option) {
		$id = theme_get_array_value($option, 'id');
		$name = theme_get_array_value($option, 'name');
		$desc = theme_get_array_value($option, 'desc');
		$value = theme_get_meta_option($post->ID, $id);
		$necessary = theme_get_array_value($option, 'necessary');
		if ($necessary && !current_user_can($necessary)) continue;
        echo '<p class="meta-options"><label class="selectit" for="'.$id.'"><strong>'.$name .'</strong></label><br />';
		theme_print_option_control($option, $value);
		if ($desc) {
			echo '<em>'.$desc.'</em>';
		}
		echo'</p>';
    }
}



// post metadata
/* When the post is saved, saves our data */
function theme_save_post($post_id) {
	global $theme_post_meta_options, $theme_page_meta_options;
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times

    if (!isset($_POST['theme_meta_noncename']) || !wp_verify_nonce($_POST['theme_meta_noncename'], 'theme_meta_options' )) {
        return $post_id;
    }

    // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
    // to do anything
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return $post_id;
	
	$meta_options = null;//posts
	if ( 'page' == $_POST['post_type']) {
		// Check permissions
		if (!current_user_can( 'edit_page', $post_id ) ){
			return $post_id;
		}
		$meta_options = $theme_page_meta_options;
	}
	
	if ( 'post' == $_POST['post_type']) {
		$meta_options = $theme_post_meta_options;
	}
	
	if (!$meta_options) return $post_id;
	// OK, we're authenticated: we need to find and save the data
	foreach ($meta_options as $value) {
		$id = theme_get_array_value($value, 'id');
		$val = stripslashes(theme_get_array_value($_REQUEST, $id));
		$type = theme_get_array_value($value, 'type');
		$necessary = theme_get_array_value($value, 'necessary');
		if ($necessary && !current_user_can($necessary)) continue;
		switch($type){
			case 'checkbox':
				$val = ($val  ? 1 : 0);
			break;
			case 'numeric':
				$val = (int) $val;
			break;
		}
		theme_set_meta_option($post_id, $id, $val); 
	}
	return $post_id;
}