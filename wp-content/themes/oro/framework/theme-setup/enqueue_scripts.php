<?php
/**
 * Enqueue scripts and styles.
 */
function oro_scripts() {
	wp_enqueue_style( 'oro-style', get_stylesheet_uri(), array(), ORO_VERSION );
	wp_style_add_data( 'oro-style', 'rtl', 'replace' );
	
	wp_enqueue_script('jquery-ui-tabs');
	
	wp_enqueue_style( 'oro-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500&family=Open+Sans:ital,wght@0,300;0,400;0,600;1,400&display=swap', array(), ORO_VERSION );

	wp_enqueue_style( 'oro-main-style', esc_url(get_template_directory_uri() . '/assets/theme-styles/css/default.css'), array(), ORO_VERSION );
	
	wp_enqueue_style( 'bootstrap', esc_url(get_template_directory_uri() . '/assets/bootstrap/bootstrap.css'), array(), ORO_VERSION );
	
	wp_enqueue_style( 'owl', esc_url(get_template_directory_uri() . '/assets/owl/owl.carousel.css'), array(), ORO_VERSION );
	
	wp_enqueue_style( 'mag-popup', esc_url(get_template_directory_uri() . '/assets/magnific-popup/magnific-popup.css'), array(), ORO_VERSION );
	
	wp_enqueue_style( 'font-awesome', esc_url(get_template_directory_uri() . '/assets/fonts/font-awesome.css'), array(), ORO_VERSION );
	
	wp_enqueue_script( 'big-slide', esc_url(get_template_directory_uri() . '/assets/js/bigSlide.js'), array('jquery'), ORO_VERSION, true );
	
	wp_enqueue_script( 'oro-custom-js', esc_url(get_template_directory_uri() . '/assets/js/custom.js'), array('jquery'), ORO_VERSION, true );
	
	wp_enqueue_script( 'owl-js', esc_url(get_template_directory_uri() . '/assets/js/owl.carousel.js'), array('jquery'), ORO_VERSION, true );
	
	wp_enqueue_script( 'mag-lightbox-js', esc_url(get_template_directory_uri() . '/assets/js/jquery.magnific-popup.min.js'), array('jquery'), ORO_VERSION, true );

	wp_enqueue_script( 'oro-navigation', esc_url(get_template_directory_uri() . '/assets/js/navigation.js'), array(), ORO_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'oro_scripts' );


/**
 *	Localize Customizer Data to make Theme Settings available in custom.js
 */
 function oro_localize_settings() {
	 
	 $data = array(
		 'stickyNav'	=>	get_theme_mod('oro_sticky_menu_enable', '')
	 );
	 
	 wp_localize_script( 'oro-custom-js', 'oro', $data );
	 
 }
 add_action('wp_enqueue_scripts', 'oro_localize_settings');