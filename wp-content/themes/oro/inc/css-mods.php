<?php
/**
 *  File for Custom CSS
 */

function oro_custom_css() {

    $primary_width     = 100 - get_theme_mod('oro_sidebar_width', '25') . '%';
    $secondary_width   = get_theme_mod('oro_sidebar_width', '25') . '%';

    $css = "";
    
    $css .= "@media screen and (min-width: 768px) {";

    if (is_home() && is_active_sidebar('sidebar-1') && get_theme_mod('oro_blog_sidebar_enable', 1) !== '' ) {
        $css .= 'body.blog #primary  {width: ' . $primary_width . ';}';
        $css .= 'body.blog #secondary {width: ' . $secondary_width . ';}';
    }
    
    if (is_single() && is_active_sidebar('sidebar-single') && get_theme_mod('oro_single_sidebar_enable', 1) !== '' ) {
        $css .= 'body.single-post #primary {width: ' . $primary_width . ';}';
        $css .= 'body.single-post #secondary {width: ' . $secondary_width . ';}';
    }
    
    if (is_search() && is_active_sidebar('sidebar-1') && get_theme_mod('oro_search_sidebar_enable', 1) !== '' ) {
        $css .= 'body.search #primary {width: ' . $primary_width . ';}';
        $css .= 'body.search #secondary {width: ' . $secondary_width . ';}';
    }
    
    if (is_archive() && is_active_sidebar('sidebar-1') && get_theme_mod('oro_archive_sidebar_enable', 1) !== '' ) {
        $css .= 'body.archive #primary {width: ' . $primary_width . ';}';
        $css .= 'body.archive #secondary {width: ' . $secondary_width . ';}';
    }
    
    if (!is_front_page() && is_page() && is_active_sidebar('sidebar-page') && get_post_meta(get_the_ID(), 'enable-sidebar', true) !== '' ) {
        $css .= 'body.page-template-default #primary {width: ' . $primary_width . ';}';
        $css .= 'body.page-template-default #secondary {width: ' . $secondary_width . ';}';
    }

/*
    if ( !is_front_page() && is_page() && "" == get_post_meta( get_the_ID(), 'hide-sidebar', true) ) {
        $css .= 'body.page #primary {width: ' . $primary_width . ';}';
        $css .= 'body.page #secondary {width: ' . $secondary_width . ';}';
    }
*/

/*
     if ( ( class_exists('woocommerce') && ! is_woocommerce() && ( is_search() || is_post_type_archive('product') ) ) ||
     		( !class_exists('woocommerce') && ( is_search() || is_archive() ) ) ) {
         $css .= 'body.search #primary, body.archive #primary {width: ' . $primary_width . ';}';
         $css .= 'body.search #secondary, body.archive #secondary {width: ' . $secondary_width . ';}';
     }
*/
/*
	if ( is_archive() || is_search() ) {
		$css .= 'body.search #primary, body.archive #primary {width: ' . $primary_width . ';}';
        $css .= 'body.search #secondary, body.archive #secondary {width: ' . $secondary_width . ';}';
	}

     if ( class_exists('woocommerce') && is_post_type_archive('product') && 'col_s' == get_theme_mod('oro_wc_sidebar_layout', 'col_s') ) {
         $css .= 'body.post-type-archive-product #primary {width: ' . $primary_width . ';}';
         $css .= 'body.post-type-archive-product #secondary {width: ' . $secondary_width . ';}';
     }

     $css .= '#masthead.full #top-wrapper {background-color: rgba(255,255,255,' . get_theme_mod('oro_full_header_nav_opacity', 1) . ')}';
*/
	$css .= "}";
	
	if ( !get_theme_mod( 'oro_sticky_menu_enable' ) ) {
		$css .= '#sticky-navigation {display: none;}';
	}

     wp_add_inline_style( 'oro-main-style', wp_strip_all_tags($css) );

 }
 add_action('wp_enqueue_scripts', 'oro_custom_css');