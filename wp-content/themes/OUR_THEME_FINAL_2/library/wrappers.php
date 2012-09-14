<?php
// $style = 'post' or 'block' or 'vmenu' or 'simple'
function theme_wrapper($style, $args){
	$func_name = "theme_{$style}_wrapper";
	if (function_exists($func_name)) {
		call_user_func($func_name, $args);
	} else {
		theme_block_wrapper($args);
	}
}

function theme_post_wrapper($args = '') {
	$args = wp_parse_args($args, 
		array(
			'id' => '',
			'class' => '',
			'title' => '',
			'heading' => 'h2',
			'thumbnail' => '',
			'before' => '',
			'content' => '',
			'after' => ''
		)
	);
	extract($args);
	if (theme_is_empty_html($title) && theme_is_empty_html($content)) return;
	if ($id) {
		$id = ' id="' . $id . '"';
	}
	if ($class) {
		$class = ' ' . $class; 
	}
	?>
<div class="art-box art-post<?php echo $class; ?>"<?php echo $id; ?>>
	    <div class="art-box-body art-post-body">
	            <div class="art-post-inner art-article">
	            <?php
	                ob_start();
	                    if (!theme_is_empty_html($title)){
	                        echo '<'.$heading.' class="art-postheader">'.$title.'</'.$heading.'>';
	                    } 
	                $meta = ob_get_clean();    
	                if (strlen($meta) > 0) {
	                    echo '<div class="art-postmetadataheader">'.$meta.'</div>';
	                }
	                
	                echo $before;echo $thumbnail;
	                ?>
	                <div class="art-postcontent">
	                    <!-- article-content -->
	                    <?php echo $content; ?>
	                    <!-- /article-content -->
	                </div>
	                <div class="cleared"></div>
	                <?php
	                echo $after;
	            ?>
	            </div>
			<div class="cleared"></div>
	    </div>
	</div>
	
	<?php
}

function theme_simple_wrapper($args = '') {
	$args = wp_parse_args($args, 
		array(
			'id' => '',
			'class' => '',
			'title' => '',
			'heading' => 'div',
			'content' => '',
		)
	);
	extract($args);
	if (theme_is_empty_html($title) && theme_is_empty_html($content)) return;
	if ($id) {
		$id = ' id="' . $id . '"';
	}
	if ($class) {
		$class = ' ' . $class; 
	}
	echo "<div class=\"art-widget{$class}\"{$id}>";
	if ( !theme_is_empty_html($title)) echo '<'.$heading.' class="art-widget-title">' . $title . '</'.$heading.'>';
	echo '<div class="art-widget-content">' . $content . '</div>';
	echo '</div>';
}

function theme_block_wrapper($args) {
	$args = wp_parse_args($args, 
		array(
			'id' => '',
			'class' => '',
			'title' => '',
			'heading' => 'div',
			'content' => '',
		)
	);
	extract($args);
	if (theme_is_empty_html($title) && theme_is_empty_html($content)) return;
	if ($id) {
		$id = ' id="' . $id . '"';
	}
	if ($class) {
		$class = ' ' . $class; 
	}

	$begin = <<<EOL
<div class="art-box art-block{$class}"{$id}>
    <div class="art-box-body art-block-body">
EOL;
	$begin_title  = <<<EOL
<div class="art-bar art-blockheader">
    <$heading class="t">
EOL;
	$end_title = <<<EOL
</$heading>
</div>
EOL;
	$begin_content = <<<EOL
<div class="art-box art-blockcontent">
    <div class="art-box-body art-blockcontent-body">
EOL;
	$end_content = <<<EOL
		<div class="cleared"></div>
    </div>
</div>
EOL;
	$end = <<<EOL
		<div class="cleared"></div>
    </div>
</div>
EOL;
	echo $begin;
	if ($begin_title && $end_title && !theme_is_empty_html($title)) {
		echo $begin_title . $title . $end_title;
	}
	echo $begin_content;
	echo $content;
	echo $end_content;
	echo $end;	
}

function theme_vmenu_wrapper($args) {
	$args = wp_parse_args($args, 
		array(
			'id' => '',
			'class' => '',
			'title' => '',
			'heading' => 'div',
			'content' => '',
		)
	);
	extract($args);
	if (theme_is_empty_html($title) && theme_is_empty_html($content)) return;
	if ($id) {
		$id = ' id="' . $id . '"';
	}
	if ($class) {
		$class = ' ' . $class; 
	}

	$begin = <<<EOL
<div class="art-box art-block{$class}"{$id}>
    <div class="art-box-body art-block-body">
EOL;
	$begin_title  = <<<EOL

EOL;
	$end_title = <<<EOL

EOL;
	$begin_content = <<<EOL
<div class="art-box art-blockcontent">
    <div class="art-box-body art-blockcontent-body">
EOL;
	$end_content = <<<EOL
		<div class="cleared"></div>
    </div>
</div>
EOL;
	$end = <<<EOL
		<div class="cleared"></div>
    </div>
</div>
EOL;
	echo $begin;
	if ($begin_title && $end_title && !theme_is_empty_html($title)) {
		echo $begin_title . $title . $end_title;
	}
	echo $begin_content;
	echo $content;
	echo $end_content;
	echo $end;	
}
