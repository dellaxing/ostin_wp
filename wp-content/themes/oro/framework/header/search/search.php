<?php
/**
 *  PHP file for Top Search
 */
?>


<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
   <label>
       <span class="screen-reader-text"><?php _ex( 'Search for:', 'label', 'oro' ); ?></span>
       <button type="button" id="go-to-close"></button>
       <input type="text" class="search-field top_search_field" placeholder="<?php echo esc_attr_e( 'Search...', 'oro' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
       <button type="button" class="oro-btn secondary cancel_search"><?php _e('Cancel', 'oro') ?></button>
       <button type="button" id="go-to-field"></button>
   </label>
</form>