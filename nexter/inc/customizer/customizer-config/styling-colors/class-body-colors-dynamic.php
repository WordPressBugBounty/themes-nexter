<?php
/**
 * Body Color Styling Options for Nexter Theme.
 *
 * @package     Nexter
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'nxt_render_theme_css', 'nxt_body_color_dynamic_css' );
add_filter( 'nxt_gutenberg_render_theme_css', 'nxt_body_color_editor_dynamic_css',1 );
function nxt_body_color_dynamic_css( $theme_css ){

	$body_color         = nexter_get_option('body-color','#888');
	$a_link_color       = nexter_get_option('link-color','#8072fc');
	$a_link_hover_color = nexter_get_option('link-hover-color','#ff5a6e');

	/**
	 * Expose the body / link / link-hover colours as CSS custom properties on :root,
	 * then reference them everywhere via var() with the resolved value as a fallback.
	 * Default output is the SAME colour as before, but a page builder, child theme, or
	 * user CSS can now override --nxt-link-hover-color (etc.) at ANY scope — e.g.
	 * neutralise the hover colour on builder buttons without editing the theme. This
	 * makes Nexter's colour handling as override-friendly as Astra / Kadence / Blocksy.
	 */
	$c_body  = 'var(--nxt-body-color, '       . esc_attr( $body_color )         . ')';
	$c_link  = 'var(--nxt-link-color, '       . esc_attr( $a_link_color )       . ')';
	$c_hover = 'var(--nxt-link-hover-color, ' . esc_attr( $a_link_hover_color ) . ')';

	$style =array();

	$style  = array(
		':root' => array(
			'--nxt-body-color'       => esc_attr( $body_color ),
			'--nxt-link-color'       => esc_attr( $a_link_color ),
			'--nxt-link-hover-color' => esc_attr( $a_link_hover_color ),
		),
		'body,blockquote' => array(
			'color' => $c_body,
		),
		'blockquote' => array(
			'border-color' => nexter_hexa_to_rgba($a_link_color, 0.15)
		),

		'a, .page-title,.wp-block-navigation .wp-block-navigation__container' => array(
			'color' => $c_link
		),
		'a:hover, a:focus,.wp-block-navigation .wp-block-navigation-item__content:hover, .wp-block-navigation .wp-block-navigation-item__content:focus' => array(
			'color' => $c_hover
		),

		//widget area
		'.widget-area ul li:not(.page_item):not(.menu-item):before, .widget-area ul li.page_item a:before, .widget-area ul li.menu-item a:before' => array(
			'border-color' => $c_link
		),
		'.widget-area ul li:not(.page_item):not(.menu-item):hover:before, .widget-area ul li.page_item a:hover:before, .widget-area ul li.menu-item a:hover:before' => array(
			'border-color' => $c_hover
		),
		'.widget_calendar :where(#today)' => array(
			'background' => $c_link
		),

		//Pagination
		'.nxt-paginate .current, .nxt-paginate a:not(.next):not(.prev):hover, .nxt-paginate .next:hover, .nxt-paginate .prev:hover' => array(
			'background' => $c_hover
		),

		//Button
		'button:focus, .menu-toggle:hover, button:hover, .button:hover, input[type=reset]:hover, input[type=reset]:focus, input#submit:hover, input#submit:focus, input[type="button"]:hover, input[type="button"]:focus, input[type="submit"]:hover, input[type="submit"]:focus,.button:focus' => array(
			'background' => $c_hover,
			'border-color' => $c_hover,
		),

		//tagcloud
		'.tagcloud a:hover, .tagcloud a:focus, .tagcloud a.current-item' => array(
			'color' => nexter_get_foreground_color($a_link_color),
			'border-color' => $c_link,
			'background-color' => $c_link
		),

			//Input Tag Typography
		'input:focus, input[type="text"]:focus, input[type="email"]:focus, input[type="url"]:focus, input[type="password"]:focus, input[type="reset"]:focus, input[type="search"]:focus, textarea:focus' => array(
			'border-color' => $c_link
		),
		'input[type="radio"]:checked, input[type=reset], input[type="checkbox"]:checked, input[type="checkbox"]:hover:checked, input[type="checkbox"]:focus:checked, input[type=range]::-webkit-slider-thumb' => array(
			'border-color' => $c_link,
			'background-color' => $c_link,
			'box-shadow' => 'none'
		),

		//Next Prev Single Post
		'.single .nav-links .nav-previous, .single .nav-links .nav-next' => array(
			'color' => $c_link
		),

		//Blog Post Meta
		'.entry-meta, .entry-meta *' => array(
			'line-height' => '1.42',
			'color' => $c_link
		),
		'.entry-meta a:hover, .entry-meta a:hover *, .entry-meta a:focus, .entry-meta a:focus *' => array(
			'color' => $c_hover
		),

		//Page Links And Nav
		'.page-links .page-link, .single .post-navigation a' => array(
			'color' => $c_link
		),
		'.page-links > .page-link, .page-links .page-link:hover, .post-navigation a:hover' => array(
			'color' => $c_hover
		),
	);

	if( !empty($style)){
		$theme_css[]= $style;
	}

	return $theme_css;
}
function nxt_body_color_editor_dynamic_css( $theme_css ){

	$body_color         = nexter_get_option('body-color','#888');
	$a_link_color       = nexter_get_option('link-color','#8072fc');
	$a_link_hover_color = nexter_get_option('link-hover-color','#ff5a6e');

	$c_body  = 'var(--nxt-body-color, '       . esc_attr( $body_color )         . ')';
	$c_link  = 'var(--nxt-link-color, '       . esc_attr( $a_link_color )       . ')';
	$c_hover = 'var(--nxt-link-hover-color, ' . esc_attr( $a_link_hover_color ) . ')';

	$style =array();

	$style  = array(
		':root' => array(
			'--nxt-body-color'       => esc_attr( $body_color ),
			'--nxt-link-color'       => esc_attr( $a_link_color ),
			'--nxt-link-hover-color' => esc_attr( $a_link_hover_color ),
		),
		'body :where(.editor-styles-wrapper)' => array(
			'color' => $c_body,
		),
		'.editor-styles-wrapper a, .editor-styles-wrapper .page-title,.wp-block-navigation .wp-block-navigation__container' => array(
			'color' => $c_link
		),
		'.editor-styles-wrapper a:hover, .editor-styles-wrapper a:focus' => array(
			'color' => $c_hover
		),
		'.wp-block-navigation-item .wp-block-navigation-item__content' => array(
			'color' => 'inherit'
		),
		//tagcloud
		'.edit-post-visual-editor .tagcloud a:hover, .edit-post-visual-editor .tagcloud a:focus, .edit-post-visual-editor .tagcloud a.current-item' => array(
			'color' => nexter_get_foreground_color($a_link_color),
			'border-color' => $c_link,
			'background-color' => $c_link
		),

		//Page Links And Nav
		'.edit-post-visual-editor .page-links .page-link,.edit-post-visual-editor .single .post-navigation a' => array(
			'color' => $c_link
		),
		'.edit-post-visual-editor .page-links > .page-link,.edit-post-visual-editor .page-links .page-link:hover,.edit-post-visual-editor .post-navigation a:hover' => array(
			'color' => $c_hover
		),
	);

	if( !empty($style)){
		$theme_css[]= $style;
	}

	return $theme_css;
}