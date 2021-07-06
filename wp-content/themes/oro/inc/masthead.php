<?php
	
/**
 *	Search Form
 */
 function oro_get_search() {

	get_template_part('framework/header/search/search');
 }
 add_action('oro_search', 'oro_get_search');


 /**
  *	Function to add Mobile Navigation
  */
 function oro_navigation() {

 	require get_template_directory() . '/framework/header/navigation/navigation.php';

 }
  add_action('oro_get_navigation', 'oro_navigation');
  
  
 /**
  *	Top Bar
  */ 
 function oro_top_bar() { ?>
 
	 <div class="top-bar row no-gutters">
		 
        <div class="site-branding col-auto mr-auto">
			<?php do_action('oro_get_branding'); ?>
        </div>
        
    	<button class="search-btn"><i class="fa fa-search"></i></button>
		
		<button  href="#menu" class="menu-link mobile-nav-btn"><i class="fa fa-bars" aria-hidden="true"></i></button>
		<button  href="#menu" class="menu-link mobile-nav-btn-sticky"><i class="fa fa-bars" aria-hidden="true"></i></button>
		
	</div>
 <?php
 }

 /**
  *	Function for adding Site Branding via action
  */
 function oro_branding() {

 	require get_template_directory() . '/framework/header/branding/branding.php';

 }
 add_action('oro_get_branding', 'oro_branding');
 
/**
 *	Control the Masthead of the theme
 */
function oro_get_masthead( $layout = 'default') {

    switch ($layout) {
        case 'default':
        ?>
        <header id="masthead" class="site-header default">
	        
	        <?php oro_top_bar(); ?>
	        
    		<div id="header-image">
	    		<div id="header_content_wrapper">
		    		<h1 id="oro_header_title"><?php echo esc_html(get_theme_mod("oro_header_title", __('Hero Text', 'oro'))); ?></h1><?php // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText ?>
		    		<p id="oro_header_description"><?php echo esc_html(get_theme_mod("oro_header_description", __('Hero Description', 'oro'))); ?></p><?php // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText ?>
		    		<a href="<?php echo esc_url(get_theme_mod("oro_header_cta_url")); ?>" class="oro-btn bordered-white"><?php echo esc_html(get_theme_mod("oro_header_cta_text", __('Call to Action', 'oro'))); ?></a><?php // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText ?>
	    		</div>
	    		<div class="oro-content-svg">
					<?php echo oro_add_header_svg(); ?>
				</div>
    		</div>

    	</header><!-- #masthead -->
    <?php
        break;
        case 'singular': ?>
        <header id="masthead" class="site-header singular">
	        
			<?php oro_top_bar(); ?>
			
    		<div id="header-image">
	    		<div class="entry-header">
					<?php
					if ( is_singular() ) :
						the_title( '<h1 class="entry-title">', '</h1>' );
					else :
						the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
					endif;
			
					 ?>
				</div><!-- .entry-header -->
	    		<div class="oro-content-svg">
					<?php echo oro_add_header_svg(); ?>
				</div>
    		</div>

    	</header><!-- #masthead -->
        <?php
	    break;
	    case 'archive': ?>
	    <header id="masthead" class="site-header archive">
	        
	        <?php oro_top_bar(); ?>
	        
    		<div id="header-image">
	    		<div class="page-header">
					<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="archive-description">', '</div>' );
					?>
				</div><!-- .page-header -->
	    		<div class="oro-content-svg">
					<?php echo oro_add_header_svg(); ?>
				</div>
    		</div>

    	</header><!-- #masthead -->
    	<?php
	    break;
	    case 'search': ?>
	    <header id="masthead" class="site-header search">
	        
	        <?php oro_top_bar(); ?>
	        
    		<div id="header-image">
	    		<div class="page-header">
					<h2 class="page-title">
						<?php
						/* translators: %s: search query. */
						printf( esc_html__( 'Search Results for: %s', 'oro' ), '<span>' . get_search_query() . '</span>' );
						?>
					</h2>
				</div><!-- .page-header -->
	    		<div class="oro-content-svg">
					<?php echo oro_add_header_svg(); ?>
				</div>
    		</div>

    	</header><!-- #masthead -->
    	<?php
	    break;
        default: ?>
        <header id="masthead" class="site-header default">
	        
	        <?php oro_top_bar(); ?>
	        
    		<div id="header-image">
	    		<div id="header_content_wrapper">
		    		<h1 id="oro_header_title"><?php echo esc_html(get_theme_mod("oro_header_title", __('Hero Text', 'oro'))); ?></h1><?php // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText ?>
		    		<p id="oro_header_description"><?php echo esc_html(get_theme_mod("oro_header_description", __('Hero Description', 'oro'))); ?></p><?php // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText ?>
		    		<a href="<?php echo esc_url(get_theme_mod('oro_header_cta_url', '')); ?>" class="oro-btn bordered-white"><?php echo esc_html(get_theme_mod("oro_header_cta_text", __('Call to Action', 'oro'))); ?></a>
	    		</div>
	    		<div class="oro-content-svg">
					<?php echo oro_add_header_svg(); ?>
				</div>
    		</div>

    	</header><!-- #masthead -->
        <?php
    }
}
add_action('oro_masthead', 'oro_get_masthead', 10, 1);