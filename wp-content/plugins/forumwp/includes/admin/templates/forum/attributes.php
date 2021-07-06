<?php if ( ! defined( 'ABSPATH' ) ) exit;

global $post; ?>

<div class="fmwp-admin-metabox fmwp">

	<?php $fields = [
		[
			'id'        => 'fmwp_locked',
			'type'      => 'select',
			'label'     => __( 'Is Locked?', 'forumwp' ),
			'options'   => [
				'0' => __( 'No', 'forumwp' ),
				'1' => __( 'Yes', 'forumwp' ),
			],
			'value'     => get_post_meta( $post->ID, 'fmwp_locked', true ),
		],
		[
			'id'        => 'fmwp_visibility',
			'type'      => 'select',
			'label'     => __( 'Visibility', 'forumwp' ),
			'options'   => FMWP()->common()->forum()->visibilities,
			'value'     => get_post_meta( $post->ID, 'fmwp_visibility', true ),
		],
		[
			'id'    => 'fmwp_order',
			'type'  => 'number',
			'label' => __( 'Order', 'forumwp' ),
			'value' => get_post_meta( $post->ID, 'fmwp_order', true ),
		],
	];

	$fields = apply_filters( 'fmwp_forum_admin_settings_fields', $fields, $post->ID );

	FMWP()->admin()->forms( [
		'class'     => 'fmwp-forum-attributes fmwp-top-label',
		'prefix_id' => 'fmwp_metadata',
		'fields'    => $fields
	] )->display(); ?>

	<a href="https://docs.forumwpplugin.com/article/1475-creating-a-new-forum-in-wp-admin#forum-settings" target="_blank">
		<?php _e( 'Learn more about forum settings', 'forumwp' ) ?>
	</a>

	<div class="clear"></div>
</div>