<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Oro
 */

get_header(NULL, ['layout'	=>	'container', 'header' => 'singular']);
?>

	<main id="primary" class="site-main container <?php echo oro_sidebar_align('single')[0]; ?>">

		<?php
		while ( have_posts() ) :
			the_post();
			
			do_action('oro_layout', 'single');
			
			if ( get_theme_mod('oro_single_navigation_enable') !== "" ) :
				$prev_post = get_adjacent_post( false, '', true );
				$next_post = get_adjacent_post( false, '', false );
				
				$prev_thumb = has_post_thumbnail($prev_post) ? get_the_post_thumbnail($prev_post) : '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/ph_list.png') . '">';
				$next_thumb = has_post_thumbnail($next_post) ? get_the_post_thumbnail($next_post) : '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/ph_list.png') . '">';
	
				
				the_post_navigation(
					array(
						'prev_text' => '<span class="nav-thumb nav-prev-thumb" title="' . $prev_post->post_title . '">' . $prev_thumb . '</span><br><span class="nav-prev-title">%title</span>',
						'next_text' => $next_post == '' ? '' : '<span class="nav-thumb nav-next-thumb" title="' . $next_post->post_title . '">' . $next_thumb . '</span><br><span class="nav-next-title">%title</span>',
					)
				);
			endif;
			
			if ( get_theme_mod('oro_single_related_enable', 1) !== "" ) :
				do_action('oro_related_posts');
			endif;
			
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
do_action('oro_sidebar', 'single');
get_footer();
