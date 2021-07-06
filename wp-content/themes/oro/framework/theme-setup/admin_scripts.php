<?php
/**
 *	Scripts and Styles for Admin area and Customizer
 */
 
function oro_customize_control_scripts() {
	
	wp_enqueue_script("oro-customize-control-js", esc_url(get_template_directory_uri() . "/assets/js/customize_controls.js"), array(), ORO_VERSION, true );
	
}
add_action("customize_controls_enqueue_scripts", "oro_customize_control_scripts");


function oro_custom_admin_styles() {
	
	global $pagenow;
	
	$allowed = array('post.php', 'post-new.php', 'customize.php');
	
	if (!in_array($pagenow, $allowed)) {
		return;
	}

    wp_enqueue_style( 'oro-admin-css', esc_url( get_template_directory_uri() . '/assets/theme-styles/css/admin.css' ), array(), ORO_VERSION );
    
}
add_action( 'admin_enqueue_scripts', 'oro_custom_admin_styles' );
