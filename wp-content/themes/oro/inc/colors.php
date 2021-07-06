<?php
/**
 *	Custom Color Control
 */

function oro_hex_to_rgb($color, $opacity) {
	
	if (strpos($color, '#') !== false ) {
		$color = substr($color, 1);
	}
	
	$split	=	str_split($color, 2);
	$r		=	hexdec($split[0]);
	$g		=	hexdec($split[1]);
	$b		=	hexdec($split[2]);
	
	return 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $opacity . ')';
}
 
function oro_custom_colors() {
	
	$title_color	=	get_theme_mod('header_textcolor', 'ffffff');
	$bg_color 		=	get_theme_mod('background_color', 'ffffff');
	$body_color 	=	get_theme_mod('oro-body-color', '000000');
	$theme_color	=	get_theme_mod('oro-theme-color', '#7a94ce');
	$header_opacity =	get_theme_mod('oro_header_overlay_opacity', 30) / 100;
	
	$colors = "";
	// Title Color
	$colors .= '#header_content_wrapper,
				#masthead #top-bar button
				{color: #' . $title_color . '}';
	// Background Color Control
	$colors .= '.oro-content-svg path {fill: #' .  $bg_color . '}';
	
	// Body Color Control
	$colors .= 'body {color: ' . $body_color . '}';
	
	// Theme Color Control
	$colors .= 'a, button, cite,
				.widget-area:not(#footer-sidebar) ul li:before,
				#panel-top-bar .menu-link i.fa-chevron-right,
				#menu ul li a,
				#menu ul li.menu-item-has-children span.dropdown-arrow i,
				#menu ul li.menu-item-has-children ul a,
				body article .entry-meta i,
				body article .entry-footer .cat-links a, body article .entry-footer .tags-links a,
				.oro-btn.secondary,
				.oro-read-more .more-link,
				#respond input.submit,
				.widget.widget_oro_cat_slider .owl-nav span
				{color: ' . $theme_color . '}';
				
	$colors .= 'blockquote,
				#respond input.submit,
				.oro-read-more .more-link
				{border-color: ' . $theme_color . '}';
				
	$colors .= '.widget.widget_oro_cats_tab ul li.ui-tabs-active:after,
				.widget.widget_oro_cats_tab .tabs-slider
				{border-top-color: ' . $theme_color . '}';
	
	$colors .= '#search-screen {background-color: ' . oro_hex_to_rgb($theme_color, 0.85) . '}';
	
	$colors .= '#menu ul li.menu-item-has-children ul {background-color: ' . oro_hex_to_rgb($theme_color, 0.1) . '}';
	
	$colors .= 'body article.oro-card .oro-thumb .card-posted-on
				{background-color: ' . oro_hex_to_rgb($theme_color, 0.3) . '}';
				
	$colors .= '#header-image #header_content_wrapper
				{background-color: ' . oro_hex_to_rgb($theme_color, $header_opacity) . '}';
	
	$colors .= '.top-bar,
				#footer-sidebar,
				#panel-top-bar,
				.oro-btn.primary,
				.edit-link .post-edit-link,
				.widget.widget_oro_cats_tab ul li a,
				.widget.widget_oro_cat_slider .slide-title,
				#comments .comment .reply a,
				#colophon,
				.widget.widget_oro_cat_slider .owl-nav button span,
				.widget.widget_oro_cat_slider .owl-nav i
				{background-color: ' . $theme_color . '}';
				
	$colors .= '#footer-sidebar .footer-content-svg path { fill: ' . $theme_color . '}';
	
	wp_add_inline_style('oro-main-style', esc_html($colors));

}
add_action('wp_enqueue_scripts', 'oro_custom_colors');
