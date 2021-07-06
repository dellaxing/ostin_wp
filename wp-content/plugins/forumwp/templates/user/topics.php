<?php if ( ! defined( 'ABSPATH' ) ) exit;

FMWP()->get_template_part( 'js/topics-list', [
	'show_forum'    => ! empty( FMWP()->options()->get( 'show_forum' ) ),
] );

$user_id = ! empty( $fmwp_user_topics['user_id'] ) ? $fmwp_user_topics['user_id'] : get_current_user_id(); ?>

<div class="fmwp-user-topics fmwp" data-user_id="<?php echo esc_attr( $user_id ) ?>">
	<?php FMWP()->get_template_part( 'archive-topic-header' );

	$classes = apply_filters( 'fmwp_topics_wrapper_classes', '' ); ?>

	<div class="fmwp-topics-wrapper<?php echo esc_attr( $classes ) ?>"></div>
</div>