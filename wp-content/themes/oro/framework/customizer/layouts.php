<?php
/**
 *	Customizer Controls for Sidebar
**/

function oro_sidebr_customize_register( $wp_customize ) {
	
	$wp_customize->add_panel(
		"oro_layouts_panel", array(
			"title"			=>	esc_html__("Layouts", "oro"),
			"description"	=>	esc_html__("Layout Settings for the Theme", "oro"),
			"priority"		=>	20
		)
	);
	
	$wp_customize->add_section(
		"oro_blog", array(
			"title"			=>	esc_html__("Blog Page", "oro"),
			"description"	=>	esc_html__("Control the Layout Settings for the Blog Page", "oro"),
			"priority"		=>	10,
			"panel"			=>	"oro_layouts_panel"
		)
	);
	
	$wp_customize->add_setting(
		"oro_blog_layout", array(
			"default"	=> "card",
			"sanitize_callback"	=>	"oro_sanitize_select"
		)
	);
	
	$wp_customize->add_control(
		"oro_blog_layout", array(
			"label"	=>	esc_html__("Blog Layout", "oro"),
			"type"	=>	"select",
			"section"	=>	"oro_blog",
			"priority"	=>	3,
			"choices"	=>	array(
				"blog"		=>	esc_html__("Blog Layout", "oro"),
				"list"		=>	esc_html__("List Layout", "oro"),
				"card"		=>	esc_html__("Card Layout", "oro")
			)
		)
	);
	
	$wp_customize->add_setting(
		"oro_blog_sidebar_enable", array(
			"default"			=>	"right",
			"sanitize_callback"	=>	"oro_sanitize_checkbox"
		)
	);
	
	$wp_customize->add_control(
		"oro_blog_sidebar_enable", array(
			"label"		=>	esc_html__("Enable Sidebar for Blog Page.", "oro"),
			"type"		=>	"checkbox",
			"section"	=>	"oro_blog",
			"priority"	=>	5
		)
	);
	
	
	
	$wp_customize->add_setting(
     "oro_blog_sidebar_layout", array(
       "default"  => "right",
       "sanitize_callback"  => "oro_sanitize_radio",
     )
   );
   
   $wp_customize->add_control(
	   new Oro_Image_Radio_Control(
		   $wp_customize, "oro_blog_sidebar_layout", array(
			   "label"		=>	esc_html__("Blog Layout", "oro"),
			   "type"		=>	"oro-image-radio",
			   "section"	=> "oro_blog",
			   "settings"	=> "oro_blog_sidebar_layout",
			   "priority"	=> 10,
			   "choices"	=>	array(
					"left"		=>	array(
						"name"	=>	esc_html__("Left Sidebar", "oro"),
						"image"	=>	esc_url(get_template_directory_uri() . "/assets/images/left-sidebar.png")
					),
					"right"		=>	array(
						"name"	=>	esc_html__("Right Sidebar", "oro"),
						"image"	=>	esc_url(get_template_directory_uri() . "/assets/images/right-sidebar.png")
					)   
			   )
		   )
	   )
   );
   
    $sidebar_controls = array_filter( array(
        $wp_customize->get_control( 'oro_blog_sidebar_layout' ),
    ) );
    foreach ( $sidebar_controls as $control ) {
        $control->active_callback = function( $control ) {
            $setting = $control->manager->get_setting( 'oro_blog_sidebar_enable' );
            if (  $setting->value() ) {
                return true;
            } else {
                return false;
            }
        };
    }
	
	$wp_customize->add_section(
		"oro_single", array(
			"title"			=>	esc_html__("Single Post", "oro"),
			"description"	=>	esc_html__("Control the Layout Settings for the Single Post Page", "oro"),
			"priority"		=>	20,
			"panel"			=>	"oro_layouts_panel"
		)
	);
	
	$wp_customize->add_setting(
		"oro_single_sidebar_enable", array(
			"default"			=>	1,
			"sanitize_callback"	=>	"oro_sanitize_checkbox"
		)
	);
	
	$wp_customize->add_control(
		"oro_single_sidebar_enable", array(
			"label"		=>	esc_html__("Enable Sidebar for Posts", "oro"),
			"type"		=>	"checkbox",
			"section"	=>	"oro_single",
			"priority"	=>	5
		)
	);
	
	$wp_customize->add_setting(
     "oro_single_sidebar_layout", array(
       "default"  => "right",
       "sanitize_callback"  => "oro_sanitize_radio",
     )
   );
   
   $wp_customize->add_control(
	   new Oro_Image_Radio_Control(
		   $wp_customize, "oro_single_sidebar_layout", array(
			   "label"		=>	esc_html__("Single Post Layout", "oro"),
			   "type"		=>	"oro-image-radio",
			   "section"	=> "oro_single",
			   "Settings"	=> "oro_single_sidebar_layout",
			   "priority"	=> 10,
			   "choices"	=>	array(
					"left"		=>	array(
						"name"	=>	esc_html__("Left Sidebar", "oro"),
						"image"	=>	esc_url(get_template_directory_uri() . "/assets/images/left-sidebar.png")
					),
					"right"		=>	array(
						"name"	=>	esc_html__("Right Sidebar", "oro"),
						"image"	=>	esc_url(get_template_directory_uri() . "/assets/images/right-sidebar.png")
					)   
			   )
		   )
	   )
   );
   
   $sidebar_controls = array_filter( array(
        $wp_customize->get_control( 'oro_single_sidebar_layout' ),
    ) );
    foreach ( $sidebar_controls as $control ) {
        $control->active_callback = function( $control ) {
            $setting = $control->manager->get_setting( 'oro_single_sidebar_enable' );
            if (  $setting->value() ) {
                return true;
            } else {
                return false;
            }
        };
    }
   
   $wp_customize->add_setting(
		"oro_single_navigation_enable", array(
			"default"			=>	1,
			"sanitize_callback"	=>	"oro_sanitize_checkbox"
		)
	);
	
	$wp_customize->add_control(
		"oro_single_navigation_enable", array(
			"label"		=>	esc_html__("Enable Post Navigation", "oro"),
			"type"		=>	"checkbox",
			"section"	=>	"oro_single",
			"priority"	=>	15
		)
	);
	
	$wp_customize->add_setting(
		"oro_single_related_enable", array(
			"default"			=>	1,
			"sanitize_callback"	=>	"oro_sanitize_checkbox"
		)
	);
	
	$wp_customize->add_control(
		"oro_single_related_enable", array(
			"label"		=>	esc_html__("Enable Related Posts Section", "oro"),
			"type"		=>	"checkbox",
			"section"	=>	"oro_single",
			"priority"	=>	20
		)
	);
	
	$wp_customize->add_section(
		"oro_search", array(
			"title"			=>	esc_html__("Search Page", "oro"),
			"description"	=>	esc_html__("Layout Settings for the Search Page", "oro"),
			"priority"		=>	30,
			"panel"			=>	"oro_layouts_panel"
		)
	);
	
	$wp_customize->add_setting(
		"oro_search_layout", array(
			"default"	=> "card",
			"sanitize_callback"	=>	"oro_sanitize_select"
		)
	);
	
	$wp_customize->add_control(
		"oro_search_layout", array(
			"label"	=>	esc_html__("Blog Layout", "oro"),
			"type"	=>	"select",
			"section"	=>	"oro_search",
			"priority"	=>	3,
			"choices"	=>	array(
				"blog"		=>	esc_html__("Blog Layout", "oro"),
				"list"		=>	esc_html__("List Layout", "oro"),
				"card"		=>	esc_html__("Card Layout", "oro")
			)
		)
	);
	
	$wp_customize->add_setting(
		"oro_search_sidebar_enable", array(
			"default"			=>	1,
			"sanitize_callback"	=>	"oro_sanitize_checkbox"
		)
	);
	
	$wp_customize->add_control(
		"oro_search_sidebar_enable", array(
			"label"		=>	esc_html__("Enable Sidebar for Search Page", "oro"),
			"type"		=>	"checkbox",
			"section"	=>	"oro_search",
			"priority"	=>	5
		)
	);
	
	$wp_customize->add_setting(
     "oro_search_sidebar_layout", array(
       "default"  => "right",
       "sanitize_callback"  => "oro_sanitize_radio",
     )
   );
   
   $wp_customize->add_control(
	   new Oro_Image_Radio_Control(
		   $wp_customize, "oro_search_sidebar_layout", array(
			   "label"		=>	esc_html__("Arc Page Layout", "oro"),
			   "type"		=>	"oro-image-radio",
			   "section"	=> "oro_search",
			   "Settings"	=> "oro_search_sidebar_layout",
			   "priority"	=> 10,
			   "choices"	=>	array(
					"left"		=>	array(
						"name"	=>	esc_html__("Left Sidebar", "oro"),
						"image"	=>	esc_url(get_template_directory_uri() . "/assets/images/left-sidebar.png")
					),
					"right"		=>	array(
						"name"	=>	esc_html__("Right Sidebar", "oro"),
						"image"	=>	esc_url(get_template_directory_uri() . "/assets/images/right-sidebar.png")
					)   
			   )
		   )
	   )
   );
   
   $sidebar_controls = array_filter( array(
        $wp_customize->get_control( 'oro_search_sidebar_layout' ),
    ) );
    foreach ( $sidebar_controls as $control ) {
        $control->active_callback = function( $control ) {
            $setting = $control->manager->get_setting( 'oro_search_sidebar_enable' );
            if (  $setting->value() ) {
                return true;
            } else {
                return false;
            }
        };
    }
   
   $wp_customize->add_section(
		"oro_archive", array(
			"title"			=>	esc_html__("archives", "oro"),
			"description"	=>	esc_html__("Layout Settings for the Archives", "oro"),
			"priority"		=>	40,
			"panel"			=>	"oro_layouts_panel"
		)
	);
	
	$wp_customize->add_setting(
		"oro_archive_layout", array(
			"default"	=> "card",
			"sanitize_callback"	=>	"oro_sanitize_select"
		)
	);
	
	$wp_customize->add_control(
		"oro_archive_layout", array(
			"label"	=>	esc_html__("Archive Layout", "oro"),
			"type"	=>	"select",
			"section"	=>	"oro_archive",
			"priority"	=>	3,
			"choices"	=>	array(
				"blog"		=>	esc_html__("Blog Layout", "oro"),
				"list"		=>	esc_html__("List Layout", "oro"),
				"card"		=>	esc_html__("Card Layout", "oro")
			)
		)
	);
	
	$wp_customize->add_setting(
		"oro_archive_sidebar_enable", array(
			"default"			=>	1,
			"sanitize_callback"	=>	"oro_sanitize_checkbox"
		)
	);
	
	$wp_customize->add_control(
		"oro_archive_sidebar_enable", array(
			"label"		=>	esc_html__("Enable Sidebar for Archives", "oro"),
			"type"		=>	"checkbox",
			"section"	=>	"oro_archive",
			"priority"	=>	5
		)
	);
	
	$wp_customize->add_setting(
     "oro_archive_sidebar_layout", array(
       "default"  => "right",
       "sanitize_callback"  => "oro_sanitize_radio",
     )
   );
   
   $wp_customize->add_control(
	   new Oro_Image_Radio_Control(
		   $wp_customize, "oro_archive_sidebar_layout", array(
			   "label"		=>	esc_html__("Archives Layout", "oro"),
			   "type"		=>	"oro-image-radio",
			   "section"	=> "oro_archive",
			   "Settings"	=> "oro_archive_sidebar_layout",
			   "priority"	=> 10,
			   "choices"	=>	array(
					"left"		=>	array(
						"name"	=>	esc_html__("Left Sidebar", "oro"),
						"image"	=>	esc_url(get_template_directory_uri() . "/assets/images/left-sidebar.png")
					),
					"right"		=>	array(
						"name"	=>	esc_html__("Right Sidebar", "oro"),
						"image"	=>	esc_url(get_template_directory_uri() . "/assets/images/right-sidebar.png")
					)   
			   )
		   )
	   )
   );
   
   $sidebar_controls = array_filter( array(
        $wp_customize->get_control( 'oro_search_sidebar_layout' ),
    ) );
    foreach ( $sidebar_controls as $control ) {
        $control->active_callback = function( $control ) {
            $setting = $control->manager->get_setting( 'oro_search_sidebar_enable' );
            if (  $setting->value() ) {
                return true;
            } else {
                return false;
            }
        };
    }
}
add_action("customize_register", "oro_sidebr_customize_register");