<?php

/**
 *
 * smiley.php
 *
 * Used to add custom shortcodes.
 * 
 * The smilies template. Used to output smilies in post comments.
 * To enable Smilies please go to the WordPress Admin panel -> Appearance -> Theme Options -> Comments -> Use smilies in comments.
 * 
 * More detailed information about smilies: http://codex.wordpress.org/Using_Smilies
 * 
 */


function theme_get_smilies_js(){
	ob_start(); ?>
<script type="text/javascript" language="javascript">
/* <![CDATA[ */
    function grin(tag) {
    	var myField;
    	tag = ' ' + tag + ' ';
        if (document.getElementById('comment') && document.getElementById('comment').type == 'textarea') {
    		myField = document.getElementById('comment');
    	} else {
    		return false;
    	}
    	if (document.selection) {
    		myField.focus();
    		sel = document.selection.createRange();
    		sel.text = tag;
    		myField.focus();
    	}
    	else if (myField.selectionStart || myField.selectionStart == '0') {
    		var startPos = myField.selectionStart;
    		var endPos = myField.selectionEnd;
    		var cursorPos = endPos;
    		myField.value = myField.value.substring(0, startPos)
    					  + tag
    					  + myField.value.substring(endPos, myField.value.length);
    		cursorPos += tag.length;
    		myField.focus();
    		myField.selectionStart = cursorPos;
    		myField.selectionEnd = cursorPos;
    	}
    	else {
    		myField.value += tag;
    		myField.focus();
    	}
    }
/* ]]> */
</script>
<?php 
	return ob_get_clean();
}

function theme_get_smilies() {
$smilies = array(
	':?:' => 'icon_question.gif',
	':razz:' => 'icon_razz.gif',
	':sad:' => 'icon_sad.gif',
	':evil:' => 'icon_evil.gif',
	':!:' => 'icon_exclaim.gif',
	':smile:' => 'icon_smile.gif',
	':oops:' => 'icon_redface.gif',
	':grin:' => 'icon_biggrin.gif',
	':eek:' => 'icon_surprised.gif',
	':shock:' => 'icon_eek.gif',
	':???:' => 'icon_confused.gif',
	':cool:' => 'icon_cool.gif',
	':lol:' => 'icon_lol.gif',
	':mad:' => 'icon_mad.gif',
	':twisted:' => 'icon_twisted.gif',
	':roll:' => 'icon_rolleyes.gif',
	':wink:' => 'icon_wink.gif',
	':idea:' => 'icon_idea.gif',
	':arrow:' => 'icon_arrow.gif',
	':neutral:' => 'icon_neutral.gif',
	':cry:' => 'icon_cry.gif',
	':mrgreen:' => 'icon_mrgreen.gif'
);
$result = '';
foreach( $smilies as $tag => $icon ) {
	$result .= '<a href="javascript:grin(\''.$tag.'\')"><img class="wp-smiley" src="'.get_bloginfo("wpurl").'/wp-includes/images/smilies/'.$icon.'" alt="" /></a>';
}
return $result;
}