<?php
/**
 * List Layout for Blog
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Oro
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('oro-list col-12'); ?>>
	
	<div class="list-wrapper no-gutters">
		
		
		<div class="oro-thumb col-md-3">
			<?php if ( has_post_thumbnail() ): ?>
				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('oro_list_thumb'); ?></a>
			<?php
			else :
			?>	<a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/ph_list.png'); ?>"></a>
			<?php endif; ?>
		</div>
		
		
		<div class="oro-list-content col-md-9">
			<header class="entry-header">
				<?php
					the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h3>' );
		
				if ( 'post' === get_post_type() ) :
					?>
					<div class="entry-meta">
						<?php
						oro_posted_on();
						oro_posted_by();
						?>
					</div><!-- .entry-meta -->
				<?php endif; ?>
			</header><!-- .entry-header -->
	
			<div class="entry-content">
				
				<?php do_action('oro_blog_excerpt', 10); ?>
				
				<div class="oro-read-more"><a href="<?php esc_url( the_permalink() ); ?>" class="more-link"><?php _e("Read More", "oro"); ?></a></div>
			</div><!-- .entry-content -->
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->