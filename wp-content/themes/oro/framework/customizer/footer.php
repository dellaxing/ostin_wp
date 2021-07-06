<?php
/**
 *  Customizer Section for Footer
 */

 function oro_customize_register_footer( $wp_customize ) {

    $wp_customize->add_section(
        'oro_footer_section', array(
            'title'    => esc_html__('Footer', 'oro'),
            'priority' => 30,
        )
    );

    $wp_customize->add_setting(
        'oro_footer_cols', array(
            'default'  => 4,
            'sanitize_callback'    => 'absint'
        )
    );
     
    $wp_customize->add_control(
	    new Oro_Image_Radio_Control(
		    $wp_customize, 'oro_footer_cols', array(
			    'label'    =>  esc_html__('Select the Footer Layout', 'oro'),
	            'section'  =>  'oro_footer_section',
	            'priority' => 5,
	            'type'	   => 'image-radio',
	            'choices'	=>	array(
		            '1'	=>	array(
			            'name'	=>	esc_html__('1 Column', 'oro'),
			            'image'	=>  esc_url(get_template_directory_uri() . '/assets/images/1-column.png'),
		            ),
		            '2'	=>	array(
			            'name'	=>	esc_html__('2 Columns', 'oro'),
			            'image'	=>  esc_url(get_template_directory_uri() . '/assets/images/2-columns.png'),
		            ),
		            '3'	=>	array(
			            'name'	=>	esc_html__('3 Columns', 'oro'),
			            'image'	=>  esc_url(get_template_directory_uri() . '/assets/images/3-columns.png'),
		            ),
		            '4'	=>	array(
			            'name'	=>	esc_html__('4 Columns', 'oro'),
			            'image'	=> esc_url(get_template_directory_uri() . '/assets/images/4-columns.png'),
		            ),
	            )
	        )
	    )
    );

     $wp_customize->add_setting(
         'oro_footer_text', array(
             'default'  => '',
             'sanitize_callback'    =>  'sanitize_text_field'
         )
     );

     $wp_customize->add_control(
         'oro_footer_text', array(
             'label'    =>  esc_html__('Custom Footer Text', 'oro'),
             'description'  =>  esc_html__('Will show Default Text if empty', 'oro'),
             'priority' =>  10,
             'type'     =>  'text',
             'section'  => 'oro_footer_section'
         )
     );
 }
 add_action('customize_register', 'oro_customize_register_footer');