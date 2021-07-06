<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Oro
 */

get_header(NULL, ['layout'	=>	'container', 'header' => 'archive']);
?>

	<main id="primary" class="site-main container <?php echo oro_sidebar_align('archive')[0]; ?>">

		<?php if ( have_posts() ) : 
			
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				do_action('oro_layout', 'archive');

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
do_action('oro_sidebar', 'archive');
get_footer();
