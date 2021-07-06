<?php
/**
 *	Custom Block Styles for ORO
 */
 
function oro_register_block_style() {
	
	wp_enqueue_style( 'oro-block-style', esc_url( get_template_directory_uri() . '/assets/theme-styles/css/block-styles.css'), array(), ORO_VERSION );
	
}
add_action('init', 'oro_register_block_style');