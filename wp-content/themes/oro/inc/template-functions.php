<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package oro
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function oro_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'oro_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function oro_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'oro_pingback_header' );


/**
 *	Pagination
 */
function oro_get_pagination() {

	$args	=	array(
		'mid_size' => 2,
		'prev_text' => __( '<i class="fas fa-angle-left"></i>', 'oro' ),
		'next_text' => __( '<i class="fas fa-angle-right"></i>', 'oro' ),
	);

	the_posts_pagination($args);

}
add_action('oro_pagination', 'oro_get_pagination');


 /**
  *	Function to call Featured Image
	*/

	function oro_get_featured_thumnail( $layout ) {

		if ( has_post_thumbnail() ) :
			?>
			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'oro_' . $layout ); ?></a>
			<?php
		else :
			$path = esc_url( get_template_directory_uri() . '/assets/images/ph_' . $layout . '.png');
			?>
			<a href="<?php the_permalink(); ?>"><img src="<?php echo $path; ?>" alt="Featured Thumbnail"></a>
		<?php
		endif;
	}
	add_action('oro_featured_thumbnail', 'oro_get_featured_thumnail', 10, 1);




	function oro_get_post_categories() {

		$cats		=	wp_get_post_categories( get_the_ID() );
		$link_html	=	'<span class="cat-links"><i class="fas fa-folder"></i>';
		?>



		<?php
			foreach($cats as $cat) {
				$link_html	.=	'<a href=' . esc_url(get_category_link($cat)) . ' tabindex="0">' . esc_html(get_cat_name($cat)) . '</a>';
			}
			$link_html	.=	'</span>';
		echo $link_html;
		?>
		<?php
	}


	function oro_get_comments() {
		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link"><i class="fas fa-comment"></i>';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'oro' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
			echo '</span>';
		}
	}


	/**
	 *	Function to generate meta data for the posts
	 */
function oro_get_metadata() {
	if ( 'post' === get_post_type() ) :
		?>
			<div class="entry-meta">
				<?php
				oro_posted_by();
				oro_posted_on();
				oro_get_post_categories();
				oro_get_comments();
				?>
			</div>
	<?php endif;
}
add_action('oro_metadata', 'oro_get_metadata');


/**
 *	Function to load Showcase Featured Area
 */
function oro_get_showcase() {

	include_once get_template_directory() . '/framework/featured_areas/showcase.php';

}

/**
 *	Function to load Featured Posts Area
 */
function oro_get_featured_posts() {

	include_once get_template_directory() . '/framework/featured_areas/featured-posts.php';

}


/**
 *	Function for Blog Page Title
 */

function oro_get_blog_page_title() {

	if ( get_theme_mod('oro_blog_title', '' ) ) {
		?>
		<h3 id="blog_title" class="section-title title-font">
			<?php echo esc_html(get_theme_mod('oro_blog_title')) ?>
		</h3>
	<?php
	}
}
add_action('oro_blog_title', 'oro_get_blog_page_title');
 

/**
 *	Function for post content on Blog Page
 */
 function oro_get_blog_excerpt( $length = 30 ) {

	 global $post;
	 $output	=	'';

	 if ( isset($post->ID) && has_excerpt($post->ID) ) {
		 $output = $post->post_excerpt;
	 }

	 elseif ( isset( $post->post_content ) ) {
		 if ( strpos($post->post_content, '<!--more-->') ) {
			 $output	=	get_the_content('');
		 }
		 else {
			 $output	=	wp_trim_words( strip_shortcodes( $post->post_content ), $length );
		 }
	 }

	 $output	=	apply_filters('oro_excerpt', $output);

	 echo $output;
 }
 add_action('oro_blog_excerpt', 'oro_get_blog_excerpt', 10, 1);



 function oro_get_layout( $template = 'blog') {

	 $layout	=	'framework/layouts/content';

	 switch( $template ) {
		case 'blog':
			get_template_part( $layout, get_theme_mod("oro_blog_layout", "card" ), array('columns' => 'col-md-6') );
		break;
		case 'single':
			get_template_part( 'template-parts/content', 'single' );
		break;
		case 'search':
			get_template_part( $layout, get_theme_mod("oro_search_layout", "card" ), array('columns' => 'col-md-6') );
		break;
		case 'archive':
			get_template_part( $layout, get_theme_mod("oro_archive_layout", "card" ), array('columns' => 'col-md-6') );
		break;
		default:
			get_template_part( $layout, get_theme_mod('oro_blog_layout', 'card' ), array('columns' => 'col-md-6') );
	 }
 }
 add_action('oro_layout', 'oro_get_layout', 10, 1);


 /**
  *	Function for 'Read More' link
  */
  function oro_read_more_link() {
	  ?>
	  <div class="read-more title-font"><a href="<?php the_permalink() ?>"><?php _e('Read More', 'oro'); ?></a></div>
	  <?php
  }


/**
 *	Function to Enable Sidebar
 */
function oro_get_sidebar( $template ) {

   global $post;

   switch( $template ) {
	   
	    case "blog";
	    if ( is_home() &&
	    	get_theme_mod('oro_blog_sidebar_enable', 1) !== "" ) {
		    get_sidebar(NULL, ['page' => 'blog']);
		}
		break;
	    case "single":
	   		if( is_single() &&
	   		get_theme_mod('oro_single_sidebar_enable', 1) !== "" ) {
				get_sidebar('single');
			}
		break;
		case "search":
	   		if( is_search() &&
	   		get_theme_mod('oro_search_sidebar_enable', 1) !== "" ) {
				get_sidebar(NULL, ['page' => 'search']);
			}
		break;
		case "archive":
	   		if( is_archive() &&
	   		get_theme_mod('oro_archive_sidebar_enable', 1) !== "" ) {
				get_sidebar(NULL, ['page' => 'archive']);
			}
		break;
		case "page":
			if ( '' == get_post_meta($post->ID, 'enable-sidebar', true) ) {
				get_sidebar('page');
			}
		break;
	    default:
	    	get_sidebar();
	}
}
add_action('oro_sidebar', 'oro_get_sidebar', 10, 1);



 /**
  *	Function for Sidebar alignment
  */
function oro_sidebar_align( $template = 'blog' ) {
	
		$align = 'page'	== $template ? get_post_meta( get_the_ID(), 'align-sidebar', true ) : get_theme_mod('oro_' . $template . '_sidebar_layout', 'right');

	$align_arr	=	['order-1', 'order-2'];

	if ( in_array( $template, ['single', 'blog', 'page', 'search', 'archive'] ) ) {
		return 'right' == $align ? $align_arr : array_reverse($align_arr);
	}
	else {
		return $align_arr;
	}
}


 /**
  *	Function to get Social icons
  */
 function oro_get_social_icons() {
 	get_template_part('social');
 }
 add_action('oro_social_icons', 'oro_get_social_icons');


 /**
  *	Get Custom sizes for 'image' post format
  */
  function oro_thumb_dim( $id, $size ) {

	$img_array	=	wp_get_attachment_image_src( $id, $size );

	$dim	=	[];
	$dim['width']	= $img_array[1];
	$dim['height']	= $img_array[2];

	return $dim;

}


/**
 *	The About Author Section
 */
function oro_get_about_author( $post ) { ?>
	<div id="author_box" class="row no-gutters">
		<div class="author_avatar col-2">
			<?php echo get_avatar( intval($post->post_author), 96 ); ?>
		</div>
		<div class="author_info col-10">
			<h4 class="author_name title-font">
				<?php echo get_the_author_meta( 'display_name', intval($post->post_author) ); ?>
			</h4>
			<div class="author_bio">
				<?php echo get_the_author_meta( 'description', intval($post->post_author) ); ?>
			</div>
		</div>
	</div>
<?php
}
add_action('oro_about_author', 'oro_get_about_author', 10, 1);

 /**
  *	Function to add featured Areas before Content
  */
function oro_get_before_content() {
	
	if ( is_front_page() && is_active_sidebar('before-content') ) :
		?>
		<div id="oro-before-content">
			<?php
				dynamic_sidebar('before-content');
			?>
		</div>
		<?php
	endif;
}
add_action('oro_before_content', 'oro_get_before_content');



  /**
   *	Function to add Featured Areas After Content
   */
   function oro_get_after_content() {

	    if ( is_front_page() && is_active_sidebar('after-content') ) :
			?>
			<div id="oro-before-content">
				<?php
					dynamic_sidebar('after-content');
				?>
			</div>
			<?php
		endif;
   }
   add_action('oro_after_content', 'oro_get_after_content');


/**
 *	Function for footer section
 */
 function oro_get_footer() {
	 
	$path 	=	'/framework/footer/footer';
	get_template_part( $path, get_theme_mod( 'oro_footer_cols', 4 ) );
 }
 add_action('oro_footer', 'oro_get_footer');
   
/**
 *	Function for AJAX request to get meta data of page set as Front Page
**/

/*
add_action('wp_ajax_front_page_meta', 'oro_front_page_ajax');
function oro_front_page_ajax() {
	
	$page_id	=	intval( $_POST['id'] );
	$path		=	get_page_template_slug($page_id);

	echo $path;
	
	wp_die();
	
}
*/


/**
 *	Related Posts of a Single Post
 */
 
function oro_get_related_posts() {
	
	global $post;
	
	$related_args = [
		"posts_per_page"		=>	3,
		"ignore_sticky_posts"	=>	true,
		"post__not_in"			=>	[get_the_ID()],
		"category_name"			=>	get_the_category($post)[0]->slug,
		"orderby"				=> "rand"
	];
	
	$related_query	=	new WP_Query( $related_args );
	
	if ( $related_query->have_posts() ) : ?>
		<div id="oro-related-posts-wrapper">
			<h3 id="oro-related-posts-title"><?php _e('Related Posts', 'oro'); ?></h3>
			<div id="oro-related-posts row">
				<?php
					while ( $related_query->have_posts() ) : $related_query->the_post();
			
						get_template_part( 'framework/layouts/content', 'card', array('columns' => 'col-lg-4') );
						
					endwhile;
				?>
			</div>
		</div>
	<?php
	endif;
	wp_reset_postdata();
}
add_action('oro_related_posts', 'oro_get_related_posts');


/**
 *	Add inline SVG Waves
 */
function oro_add_header_svg() {
	
	switch(get_theme_mod('oro_header_waves', 1) ) {
		case '1' :
			return '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1440 193.5" style="enable-background:new 0 0 1440 193.5;" xml:space="preserve"><path class="st0" fill="#ffffff" d="M0,129.5l48-26.7c48-26.3,144-80.3,240-74.6c96,5.3,192,69.3,288,64c96-5.7,192-79.7,288-90.7s192,43,288,69.3 c96,26.7,192,26.7,240,26.7h48v96h-48c-48,0-144,0-240,0s-192,0-288,0s-192,0-288,0s-192,0-288,0s-192,0-240,0H0V129.5z"/></svg>';
			break;
		case '2':
			return '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 1440 288" style="enable-background:new 0 0 1440 288;" xml:space="preserve">
<path class="st0" fill="#ffffff" d="M0,192l30-26.7C60,139,120,85,180,74.7C240,64,300,96,360,133.3c60,37.7,120,79.7,180,69.4
	C600,192,660,128,720,101.3C780,75,840,85,900,122.7c60,37.3,120,101.3,180,90.6c60-10.3,120-96.3,180-144c60-48.3,120-58.3,150-64
	l30-5.3v288h-30c-30,0-90,0-150,0s-120,0-180,0s-120,0-180,0s-120,0-180,0s-120,0-180,0s-120,0-180,0s-120,0-180,0s-120,0-150,0H0
	V192z"/>
</svg>';
			break;
		case '3':
			return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#ffffff" fill-opacity="1" d="M0,256L1440,128L1440,320L0,320Z"></path></svg>';
			break;
		case '4':
			return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#ffffff" fill-opacity="1" d="M0,96L1440,256L1440,320L0,320Z"></path></svg>';
			break;
		default:
			return '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1440 193.5" style="enable-background:new 0 0 1440 193.5;" xml:space="preserve"><path class="st0" fill="#ffffff" d="M0,129.5l48-26.7c48-26.3,144-80.3,240-74.6c96,5.3,192,69.3,288,64c96-5.7,192-79.7,288-90.7s192,43,288,69.3 c96,26.7,192,26.7,240,26.7h48v96h-48c-48,0-144,0-240,0s-192,0-288,0s-192,0-288,0s-192,0-288,0s-192,0-240,0H0V129.5z"/></svg>';
	}
}

/**
 *	Footer SVG
 */
 function oro_get_footer_svg() { ?>
 
	<div class="footer-content-svg">
		<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 1440 193.5" style="enable-background:new 0 0 1440 193.5;" xml:space="preserve">
<path class="st0" fill="#7a94ce" d="M0,129.5l48-26.7c48-26.3,144-80.3,240-74.6c96,5.3,192,69.3,288,64c96-5.7,192-79.7,288-90.7s192,43,288,69.3
	c96,26.7,192,26.7,240,26.7h48v96h-48c-48,0-144,0-240,0s-192,0-288,0s-192,0-288,0s-192,0-288,0s-192,0-240,0H0V129.5z"/>
</svg>
	</div>
	 
<?php
}