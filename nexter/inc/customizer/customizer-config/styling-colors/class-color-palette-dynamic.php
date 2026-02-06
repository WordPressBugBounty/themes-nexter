<?php
/**
 * Color Palette Dynamic CSS for Nexter Theme.
 *
 * @package	Nexter
 * @since	1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter('nexter_theme_dynamic_css', 'nxt_global_color_palette_css', 10);
add_filter('nxt_gutenberg_dynamic_style_css', 'nxt_global_color_palette_css', 10);
function nxt_global_color_palette_css( $dynamic_css ){
	
	// Get saved colors from options
	$saved_colors = nexter_get_option( 'global-color-palette', array() );
	
	// If no saved colors, use defaults
	if ( empty( $saved_colors ) || ! is_array( $saved_colors ) ) {
		$default_colors = nxt_get_default_color_palette();
		$colors = array();
		foreach ( $default_colors as $key => $color ) {
			$colors[] = $color;
		}
	} else {
		$colors = $saved_colors;
	}
	
	if( !empty($colors) ){
		$i = 1;
		$css_output = ':root{';
		foreach($colors as $color){
			// Sanitize color value
			$color = nxt_sanitize_color_frontend( $color );
			if ( ! empty( $color ) ) {
				$css_output .= '--nxt-global-color-'.$i.':'.$color.';';
				$i++;
			}
		}
		$css_output .= '}';
		
		$ij = 1;
		foreach($colors as $color){
			$color = nxt_sanitize_color_frontend( $color );
			if ( ! empty( $color ) ) {
				$css_output .= ':root .has---nxt-global-color-'.$ij.'-background-color, :root .has-nxt-global-color-'.$ij.'-background-color{background-color : var(--nxt-global-color-'.$ij.');}';
				$css_output .= ':root .has---nxt-global-color-'.$ij.'-color, :root .has-nxt-global-color-'.$ij.'-color, :root .has-nxt-global-color-'.$ij.'-color > .wp-block-navigation-item__content{color : var(--nxt-global-color-'.$ij.');}';
				$css_output .= ':root .has---nxt-global-color-'.$ij.'-border-color, :root .has-nxt-global-color-'.$ij.'-border-color{border-color : var(--nxt-global-color-'.$ij.');}';
				$ij++;
			}
		}
		$dynamic_css .= nexter_minify_css_generate($css_output);
	}
	
	return $dynamic_css;
}
