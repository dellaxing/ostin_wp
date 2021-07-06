<?php if ( ! defined( 'ABSPATH' ) ) exit;

global $post, $post_id;

$author_id = $post->post_author;
$author = get_userdata( $author_id );

$topics_count = FMWP()->user()->get_topics_count( $author_id );
$replies_count = FMWP()->user()->get_replies_count( $author_id ); ?>

<div class="fmwp-metabox">
	<div class="fmwp-metabox-card">
		<div class="fmwp-metabox-avatar">
			<?php echo get_avatar( $author_id ); ?>
		</div>
		<div class="fmwp-metabox-label">
			<a href="#" title="<?php echo esc_attr( FMWP()->user()->display_name( $author ) ) ?>"><?php echo FMWP()->user()->display_name( $author ) ?></a>
		</div>
	</div>
</div>

<p>
	<strong class="label"><?php _e( 'Topics', 'forumwp' ) ?></strong>
	<label class="screen-reader-text" for="fmwp_type"><?php _e( 'Topics', 'forumwp' ) ?></label>
	<span><?php echo $topics_count ?></span>
</p>

<p>
	<strong class="label"><?php _e( 'Replies', 'forumwp' ) ?></strong>
	<label class="screen-reader-text" for="fmwp_type"><?php _e( 'Replies', 'forumwp' ) ?></label>
	<span><?php echo $replies_count ?></span>
</p>