<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<script type="text/html" id="tmpl-fmwp-forum-categories-list">
	<# if ( data.categories.length > 0 ) { #>
		<# _.each( data.categories, function( category, key, list ) { #>
			<div class="fmwp-forum-category-row <# if ( ! category.child && ! category.enabled ) { #>fmwp-forum-category-disabled<# } #>" data-category_id="{{{category.id}}}">
				<div class="fmwp-forum-category-row-lines">

					<div class="fmwp-forum-category-row-line fmwp-forum-category-primary-data">

						<div class="fmwp-forum-category-data">
							<span class="fmwp-forum-category-first-line">
								<span class="fmwp-forum-category-title"><a href="{{{category.permalink}}}"><# if ( category.child ) { #>— <# } #>{{{category.title}}}</a></span>
							</span>
							<div class="fmwp-forum-category-description">{{{category.content}}}</div>
						</div>

					</div>

					<div class="fmwp-forum-category-row-line fmwp-forum-category-statistics-data">

						<div class="fmwp-forum-category-forums fmwp-tip-n" title="<?php esc_attr_e( '{{{category.forums}}} forums', 'forumwp' ) ?>">
							{{{category.forums}}}
						</div>
						<div class="fmwp-forum-category-topics fmwp-tip-n" title="<?php esc_attr_e( '{{{category.topics}}} topics', 'forumwp' ) ?>">
							{{{category.topics}}}
						</div>
						<div class="fmwp-forum-category-replies fmwp-tip-n" title="<?php esc_attr_e( '{{{category.replies}}} people have replied', 'forumwp' ) ?>">
							{{{category.replies}}}
						</div>

					</div>
				</div>

				<div class="fmwp-forum-category-actions">
					<?php if ( is_user_logged_in() ) { ?>
						<# if ( Object.keys( category.dropdown_actions ).length > 0 ) { #>
							<span class="fmwp-forum-category-actions-dropdown" title="<?php esc_attr_e( 'More Actions', 'forumwp' ) ?>">
								<i class="fas fa-angle-down"></i>
								<div class="fmwp-dropdown" data-element=".fmwp-forum-category-actions-dropdown" data-trigger="click">
									<ul>
										<# _.each( category.dropdown_actions, function( title, key, list ) { #>
											<li><a href="javascript:void(0);" class="{{{key}}}">{{{title}}}</a></li>
										<# }); #>
									</ul>
								</div>
							</span>
						<# } #>
					<?php } ?>
				</div>
			</div>
			<div class="clear"></div>
		<# }); #>
	<# } #>
</script>