<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<script type="text/html" id="tmpl-fmwp-topics-<?php echo ! empty( $fmwp_js_topics_list['type'] ) ? esc_attr( $fmwp_js_topics_list['type'] ) . '-' : ''; ?>list">
	<# if ( data.topics.length > 0 ) { #>
		<# _.each( data.topics, function( topic, key, list ) { #>
			<?php FMWP()->get_template_part( 'js/topic-row', [
				'item'          => 'topic',
				'actions'       => isset( $fmwp_js_topics_list['actions'] ) ? $fmwp_js_topics_list['actions'] : '',
				'show_forum'    => isset( $fmwp_js_topics_list['show_forum'] ) ? $fmwp_js_topics_list['show_forum'] : true,
			] ); ?>
		<# }); #>
	<# } #>
</script>