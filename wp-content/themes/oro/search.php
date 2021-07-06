<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result  
 *
 * @package Oro Theme
 */

get_header(NULL, ['layout'	=>	'container', 'header' => 'search']);
?>

	<main id="primary" class="site-main container <?php echo oro_sidebar_align('search')[0]; ?>">

		<?php if ( have_posts() ) :
		
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				do_action('oro_layout', 'search');

			endwhile;

			the_posts_pagination( array(
				'class'	=>	'oro-pagination',
				'prev_text'	=> '<i class="fa fa-angle-left"></i>',
				'next_text'	=> '<i class="fa fa-angle-right"></i>'
			) );
			
		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

	</main><!-- #main -->

<?php
do_action('oro_sidebar', 'search');
get_footer();
