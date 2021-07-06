<?php
/**
 *	Customizer Controls for General Settings for the theme
 */
 
function oro_general_customize_register( $wp_customize ) {
	
	$wp_customize->add_section(
		"oro_general_options", array(
			"title"			=>	esc_html__("General", "oro"),
			"description"	=>	esc_html__("General Settings for the Theme", "oro"),
			"priority"		=>	5
		)
	);
	
	$wp_customize->add_setting(
        'oro_sidebar_width', array(
            'default'    =>  25,
            'sanitize_callback'  =>  'absint'
        )
    );

    $wp_customize->add_control(
        new Oro_Range_Value_Control(
            $wp_customize, 'oro_sidebar_width', array(
	            'label'         =>	esc_html__( 'Sidebar Width', 'oro' ),
            	'type'          => 'oro-range-value',
            	'section'       => 'oro_general_options',
            	'settings'      => 'oro_sidebar_width',
                'priority'		=>  5,
            	'input_attrs'   => array(
            		'min'            => 25,
            		'max'            => 40,
            		'step'           => 1,
            		'suffix'         => '%', //optional suffix
				),
            )
        )
    );
    
    $wp_customize->add_setting(
		'oro_sticky_menu_enable', array(
			'default'	=>	'',
			'sanitize_callback'	=> 'oro_sanitize_checkbox'
		)
	);
	
	$wp_customize->add_control(
		'oro_sticky_menu_enable', array(
			'label'		=>	__('Enable Sticky Navigation', 'oro'),
			'type'		=>	'checkbox',
			'section'	=>	'oro_general_options',
			'priority'	=>	30
		)
	);
}
add_action("customize_register", "oro_general_customize_register");