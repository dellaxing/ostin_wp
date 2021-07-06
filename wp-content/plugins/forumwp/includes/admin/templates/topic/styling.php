<?php if ( ! defined( 'ABSPATH' ) ) exit;

global $post;

$templates = [
	''                          => __( 'Global settings', 'forumwp' ),
	'fmwp_individual_default'   => __( 'Default template', 'forumwp' ),
];

$custom_templates = FMWP()->common()->forum()->get_templates( $post );

$fields = [];
if ( count( $custom_templates ) ) {
	$fields[] = [
		'id'        => 'fmwp_template',
		'type'      => 'select',
		'label'     => __( 'Template', 'forumwp' ),
		'value'     => get_post_meta( $post->ID, 'fmwp_template', true ),
		'options'   => array_merge( $templates, $custom_templates ),
	];
} else {
	$fields[] = [
		'id'    => 'fmwp_template',
		'type'  => 'hidden',
		'value' => '',
	];
	$fields[] = [
		'id'    => 'fmwp_template_text',
		'type'  => 'info_text',
		'value' => __( 'You could create custom templates to customize topics', 'forumwp' ),
	];
} ?>

<div class="fmwp-admin-metabox fmwp">

	<?php FMWP()->admin()->forms( [
		'class'     => 'fmwp-topic-styling fmwp-top-label',
		'prefix_id' => 'fmwp_metadata',
		'fields'    => $fields,
	] )->display(); ?>

	<div class="clear"></div>
</div>