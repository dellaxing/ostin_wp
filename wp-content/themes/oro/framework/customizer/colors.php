<?php
/**
 *	Color Controls
 */
 
function oro_color_customize_register( $wp_customize ) {
	
	$wp_customize->add_setting(
		'oro-theme-color', array(
			'default'			=>	'7a94ce',
			'sanitize_callback'	=>	'sanitize_hex_color'
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, 'oro-theme-color', array(
				'label'		=>	esc_html__('Theme Color', 'oro'),
				'section'	=>	'colors',
				'settings'	=>	'oro-theme-color',
				'priority'	=>	30
			)	
		)
	);
	
	$wp_customize->add_setting(
		'oro-body-color', array(
			'default'			=>	'#000000',
			'sanitize_callback'	=>	'sanitize_hex_color'
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, 'oro-body-color', array(
				'label'		=>	esc_html__('Body Color', 'oro'),
				'section'	=>	'colors',
				'settings'	=>	'oro-body-color',
				'priority'	=>	40
			)	
		)
	);
}
add_action('customize_register', 'oro_color_customize_register');