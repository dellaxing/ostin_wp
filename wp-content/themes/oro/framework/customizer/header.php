<?php
/**
 * Controls for the Header Section
 */
 
function oro_header_customize_register( $wp_customize ) {
	 
	$wp_customize->get_section("title_tagline")->panel	=	"oro_header";
	$wp_customize->get_section("header_image")->panel	=	"oro_header";
	 
	$wp_customize->add_panel(
		"oro_header", array(
			"title"	=>	esc_html__("Header", "oro"),
			"priority"	=>	10
		)
	);
	
	$wp_customize->add_section(
		"oro_header_content", array(
			"title"		=>	esc_html__("Header Text", "oro"),
			"description"	=>	esc_html__("Title, Description and Call to Action for the Header Image", "oro"),
			"panel"			=>	"oro_header",
			"priority"		=> 60
		)
	);
	
	$wp_customize->add_setting(
		"oro_header_title", array(
			"default"			=>	esc_html__("Just.Do.It.", "oro"),
			"sanitize_callback"	=>	"sanitize_text_field"
		)
	);
	
	$wp_customize->add_control(
		"oro_header_title", array(
			"label"		=>	esc_html__("Header Title", "oro"),
			"type"		=>	"text",
			"section"	=>	"oro_header_content",
			"priority"	=>	10
		)
	);
	
	$wp_customize->add_setting(
		"oro_header_description", array(
			"default"			=>	esc_html__("Later", "oro"),
			"sanitize_callback"	=>	"sanitize_text_field"
		)
	);
	
	$wp_customize->add_control(
		"oro_header_description", array(
			"label"		=>	esc_html__("Header Description", "oro"),
			"type"		=>	"text",
			"section"	=>	"oro_header_content",
			"priority"	=>	20
		)
	);
	
	$wp_customize->add_setting(
		"oro_header_cta_text", array(
			"default"			=>	esc_html__("Read More", "oro"),
			"sanitize_callback"	=>	"sanitize_text_field"
		)
	);
	
	$wp_customize->add_control(
		"oro_header_cta_text", array(
			"label"		=>	esc_html__("Call to Action Text", "oro"),
			"type"		=>	"text",
			"section"	=>	"oro_header_content",
			"priority"	=>	30
		)
	);
	
	$wp_customize->add_setting(
		"oro_header_cta_url", array(
			"default"			=>	esc_html__("#", "oro"),
			"sanitize_callback"	=>	"sanitize_text_field"
		)
	);
	
	$wp_customize->add_control(
		"oro_header_cta_url", array(
			"label"		=>	esc_html__("Call to Action URL", "oro"),
			"type"		=>	"text",
			"section"	=>	"oro_header_content",
			"priority"	=>	20
		)
	);
	
	$wp_customize->add_section(
		"oro_header_options", array(
			"title"		=>	esc_html__("Header Options", "oro"),
			"panel"		=>	"oro_header",
			"priority"	=>	80
		)
	);
	
	$wp_customize->add_setting(
		"oro_header_waves", array(
			"default"	=>	1,
			"sanitize_callback"	=>	"absint"
		)
	);
	
	$wp_customize->add_control(
		"oro_header_waves", array(
			"label"		=>	esc_html__("Header Styles", "oro"),
			"type"		=>	"radio",
			"section"	=>	"oro_header_options",
			"priority"	=>	5,
			"choices"	=>	array(
					1		=>	esc_html__("Style 1", "oro"),
					2		=>	esc_html__("Style 2", "oro"),
					3		=>	esc_html__("Style 3", "oro"),
					4		=>	esc_html__("Style 4", "oro"),
			)		
		)
	);
	
	$wp_customize->add_setting(
		'oro_header_overlay_opacity', array(
			'default'	=>	30,
			'sanitize_callback'	=>	'absint',
		)
	);
	
	$wp_customize->add_control(
		new Oro_Range_Value_Control(
			$wp_customize,
			'oro_header_overlay_opacity', array(
				'label'		=>	__('Overlay Opacity', 'oro'),
				'type'		=>	'oro-range-value',
				'section'	=>	'oro_header_options',
				'settings'	=>	'oro_header_overlay_opacity',
				'priority'	=>	35,
				'input_attrs'	=>	array(
							'min'	=>	1,
							'max'	=>	100,
							'step'	=>	1,
							'suffix'=>	'%'
				)
			)
		)
	);
}
 
add_action("customize_register", "oro_header_customize_register");