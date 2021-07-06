<?php
/**
 * Oro functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Oro
 */

if ( ! defined( 'ORO_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'ORO_VERSION', '1.1.5' );
}

if ( ! function_exists( 'oro_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function oro_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Oro, use a find and replace
		 * to change 'oro' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'oro', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'oro' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);
		
		// Set Theme Color Palette
		add_theme_support(
			'editor-color-palette', array(
				array(
					'name'	=>	esc_attr__('Faded Blue', 'oro'),
					'slug'	=>	'faded-blue',
					'color'	=>	'#7a94ce'
				)
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'oro_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );
		
		// Custom Image sizes for the theme
		add_image_size('oro_list_thumb', 500, 400, true);
		add_image_size('oro_slide', 1200, 500, true);

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 60,
				'width'       => 240,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
		
		$starter_content = array(
			'widgets' => array(
				'sidebar-1'	=>	array(
					'search',
					'recent-posts',
					'archives'
				),
				'footer-1'	=>	array(
					'text_about'
				),
				'footer-2'	=>	array(
					'text_business_info'
				),
				'footer-3'	=>	array(
					'meta'
				)
			),
			'options'	=>	array(
				'show_on_front'	=>	'posts',
			),
			'attachments'	=>	array(
				'massage'		=>	array(
					'post_title'	=>	_x('Massage', 'Theme starter content', 'oro'),
					'file'			=>	'assets/images/stock/3.jpg'
				),
				'pills'		=>	array(
					'post_title'	=>	_x('Pills', 'Theme starter content', 'oro'),
					'file'			=>	'assets/images/stock/2.jpg'
				),
				'flower'	=>	array(
					'post_title'	=>	_x('Flower', 'Theme starter content', 'oro'),
					'file'			=>	'assets/images/stock/1.jpg'
				)
			),
			'posts'		=>	array(
				'post-flower'	=>	array(
					'post_type'		=>	'post',
					'post_title'	=>	_x('How to keep calm in stressful situations', 'Theme starter content', 'oro'),
					'thumbnail'		=>	'{{flower}}'
				),
				'post-pills'	=>	array(
					'post_type'		=>	'post',
					'post_title'	=>	_x('Only Pill you need is a Chill Pill', 'Theme starter content', 'oro'),
					'thumbnail'		=>	'{{pills}}'
				),
				'post-massage'	=>	array(
					'post_type'		=>	'post',
					'post_title'	=>	_x('Masage keeps your body relaxed and mind calm', 'Theme starter content', 'oro'),
					'thumbnail'		=>	'{{massage}}'
				),
			),
			'theme_mods'	=> array(
				'oro_header_title'	=>	esc_html_x('Oro is Gold', 'Theme Starter Content', 'oro'),
				'oro_header_description'	=>	esc_html_x('In Spanish', 'Theme Starter Content', 'oro'),
				'oro_header_cta_text'		=> esc_html_x('Read More','Theme Starter Content', 'oro'),
				'blogdescription'			=> esc_html_x('A Beautiful WordPress Theme', 'Starter Content', 'oro')
			),
		);
		
		add_theme_support( 'starter-content', $starter_content );
	}
endif;
add_action( 'after_setup_theme', 'oro_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function oro_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'oro_content_width', 640 );
}
add_action( 'after_setup_theme', 'oro_content_width', 0 );

/**
 * Register widget area.
 */
require get_template_directory() . '/framework/theme-setup/register_sidebars.php';


/**
 *	Enqueue Front-end Theme Scripts and Styles
 */
require get_template_directory() . '/framework/theme-setup/enqueue_scripts.php';

/**
 *	Enqueue Back-end Theme Scripts and Styles
 */
 require get_template_directory() . '/framework/theme-setup/admin_scripts.php';

/**
 *	Functions for the masthead.
 */
 require get_template_directory() . '/inc/masthead.php';
 

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 *	Custom CSS 
 */
require get_template_directory() . '/inc/css-mods.php';

/**
 *	Custom Color Control
 */
require get_template_directory() . '/inc/colors.php';

/**
 *	Block Patterns
 */
require get_template_directory() . '/inc/block-styles.php';

/**
 *	Block Patterns
 */
require get_template_directory() . '/inc/block-patterns.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/framework/customizer/customizer.php';

/**
 *	Add Menu Walker
 */
require get_template_directory() . '/inc/walker.php';

/**
 *	The Meta Box for the Page
 */
 
require get_template_directory() . '/framework/metabox/display-options.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 *	Custom Widgets
 */
require get_template_directory() . '/framework/widgets/featured-category.php';
require get_template_directory() . '/framework/widgets/recent-posts.php';
require get_template_directory() . '/framework/widgets/slider-category.php';
require get_template_directory() . '/framework/widgets/tab-categories.php';