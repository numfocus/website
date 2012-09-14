<?php
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	exit('Please do not load this page directly.');

if (!empty($post->post_password) && $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) : ?>
	<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', THEME_NS); ?></p>
<?php else : ?>
<?php if ($comments) :?>
<?php ob_start(); ?>
<h3 id="comments"><?php comments_number(__('No Responses', THEME_NS), __('One Response', THEME_NS), __('% Responses', THEME_NS));?> <?php printf(__('to &#8220;%s&#8221;', THEME_NS), the_title('', '', false)); ?></h3>
<?php theme_post_wrapper(array('content' => ob_get_clean())); ?>
<ul id="comments-list">
	<?php foreach ($comments as $comment) : ?>
		<li id="comment-<?php comment_ID() ?>">
      <?php ob_start(); ?>
      		<?php echo theme_get_avatar(array('id' => $comment, 'size' => 32)); ?>
			<cite><?php comment_author_link() ?></cite>:
      <?php if ($comment->comment_approved == '0') : ?>
			<em>
        <?php _e('Your comment is awaiting moderation.', THEME_NS); ?>
      </em>
			<?php endif; ?>
			<br />
			<small class="commentmetadata"><a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date(__('F jS, Y', THEME_NS)) ?> <?php comment_time() ?></a> <?php edit_comment_link(__('Edit', THEME_NS),'&nbsp;&nbsp;',''); ?></small>
      <?php comment_text() ?>
      <?php theme_post_wrapper(array('content' => ob_get_clean())); ?>
		</li>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>
<?php if ('open' == $post->comment_status) : ?>
<?php ob_start(); ?>
<h3 id="respond"><?php _e('Leave a Reply', THEME_NS); ?></h3>
<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', THEME_NS), get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode(get_permalink())); ?></p>
<?php else : ?>
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<?php if ( $user_ID ) : ?>
<p><?php printf(__('Logged in as <a href="%1$s">%2$s</a>.', THEME_NS), get_option('siteurl') . '/wp-admin/profile.php', $user_identity); ?> <a href="
    <?php if (function_exists('wp_logout_url')) echo wp_logout_url(get_permalink()); else echo get_option('siteurl') . "/wp-login.php?action=logout"; ?>" title="<?php _e('Log out of this account', THEME_NS); ?>"><?php _e('Log out &raquo;', THEME_NS); ?></a></p>
<?php else : ?>
<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
<label for="author"><small><?php _e('Name', THEME_NS); ?> <?php if ($req) _e("(required)", THEME_NS); ?></small></label></p>
<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
<label for="email"><small><?php _e('Mail (will not be published)', THEME_NS); ?> <?php if ($req) _e("(required)", THEME_NS); ?></small></label></p>
<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
<label for="url"><small><?php _e('Website', THEME_NS); ?></small></label></p>
<?php endif; ?>
<p><textarea name="comment" id="comment" cols="20" rows="10" tabindex="4"></textarea></p>
<p>
	<span class="art-button-wrapper"><span class="art-button-l"> </span><span class="art-button-r"> </span>
		<input class="art-button" type="submit" name="submit" tabindex="5" value="<?php _e('Submit Comment', THEME_NS); ?>" />
	</span>
	<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" /></p>
<?php do_action('comment_form', $post->ID); ?>
</form>
<?php endif; ?>
<?php theme_post_wrapper(array('content' => ob_get_clean())); ?>
<?php endif; ?>
<?php endif; ?>