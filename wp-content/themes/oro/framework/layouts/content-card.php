<?php
/**
 * List Layout for Blog
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Oro
 */
 
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('oro-card ' . $args["columns"] ); ?>>
		<div class="oro-card-wrapper">
			<div class="oro-thumb">
				<?php if ( has_post_thumbnail() ): ?>
					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('oro_list_thumb'); ?></a>
				<?php
				else :
				?>	<a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/ph_list.png'); ?>"></a>
				<?php endif; ?>
				<div class="card-posted-on">
					<div class="card-date"><?php echo get_the_date('d'); ?></div>
					<div class="card-month"><?php echo get_the_date('M'); ?></div>
				</div>
			</div>
			<header class="entry-header">
				<?php
					the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
				 ?>
			</header><!-- .entry-header -->
		</div>
</article><!-- #post-<?php the_ID(); ?> -->