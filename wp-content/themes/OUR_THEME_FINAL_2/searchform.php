<form class="art-search" method="get" name="searchform" action="<?php bloginfo('url'); ?>/">
  <div><input class="art-search-text" name="s" type="text" value="<?php echo esc_attr(get_search_query()); ?>" /></div>
  <input class="art-search-button" type="submit" value="" />       
</form>