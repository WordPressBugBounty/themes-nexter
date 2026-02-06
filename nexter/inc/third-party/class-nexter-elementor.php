<?php
/**
 * Elementor Compatibility – Global Color Palette.
 *
 * Merges Nexter theme global color palette with Elementor Page Builder global colors
 * so theme colors appear in Elementor and render correctly on the front end.
 *
 * @package Nexter
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\Elementor\Plugin' ) ) {
	return;
}

/**
 * Nexter Elementor compatibility.
 */
if ( ! class_exists( 'Nexter_Elementor' ) ) {

	/**
	 * Nexter Elementor Compatibility – Global Colors.
	 *
	 * @since 1.0.0
	 */
	class Nexter_Elementor {

		/**
		 * Elementor global color ID prefix (no hyphens).
		 *
		 * @var string
		 */
		const ELEMENTOR_COLOR_ID_PREFIX = 'nxt-global';

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'rest_request_after_callbacks', array( $this, 'elementor_add_theme_colors' ), 999, 3 );
			add_filter( 'rest_request_after_callbacks', array( $this, 'display_global_colors_front_end' ), 999, 3 );
			add_filter( 'nexter_theme_dynamic_css', array( $this, 'generate_global_elementor_style' ), 11 );
		}

		/**
		 * Get Nexter global color palette as indexed array.
		 *
		 * @since 1.0.0
		 * @return array Indexed array of color values.
		 */
		public static function get_palette_colors() {
			$saved = nexter_get_option( 'global-color-palette', array() );
			if ( ! empty( $saved ) && is_array( $saved ) ) {
				return array_values( $saved );
			}
			$defaults = nxt_get_default_color_palette();
			return array_values( $defaults );
		}

		/**
		 * Get Elementor color ID for a palette index (1-based).
		 * Elementor expects IDs without hyphens.
		 *
		 * @since 1.0.0
		 * @param int $index 1-based palette index.
		 * @return string
		 */
		public static function get_elementor_color_id( $index ) {
			return self::ELEMENTOR_COLOR_ID_PREFIX . (int) $index;
		}

		/**
		 * Get palette label for a 1-based index.
		 *
		 * @since 1.0.0
		 * @param int $index 1-based index.
		 * @return string
		 */
		public static function get_palette_label( $index ) {
			return sprintf( /* translators: 1: color number */ __( 'Theme Color %d', 'nexter' ), (int) $index );
		}

		/**
		 * Add Nexter theme global colors to Elementor globals (editor picker).
		 *
		 * @since 1.0.0
		 * @param mixed             $response REST response.
		 * @param array             $handler  Route handler.
		 * @param \WP_REST_Request   $request  Request.
		 * @return mixed
		 */
		public function elementor_add_theme_colors( $response, $handler, $request ) {
			$route = $request->get_route();
			if ( '/elementor/v1/globals' !== $route ) {
				return $response;
			}

			$colors = self::get_palette_colors();
			$data   = $response->get_data();

			if ( ! isset( $data['colors'] ) ) {
				$data['colors'] = array();
			}

			$i = 1;
			foreach ( $colors as $color ) {
				$color = nxt_sanitize_color_frontend( $color );
				if ( empty( $color ) ) {
					$i++;
					continue;
				}
				$id = self::get_elementor_color_id( $i );
				$data['colors'][ $id ] = array(
					'id'    => $id,
					'title' => self::get_palette_label( $i ),
					'value' => $color,
				);
				$i++;
			}

			$response->set_data( $data );
			return $response;
		}

		/**
		 * Return Nexter theme color when Elementor requests a single global (front end).
		 *
		 * @since 1.0.0
		 * @param mixed             $response REST response.
		 * @param array             $handler  Route handler.
		 * @param \WP_REST_Request   $request  Request.
		 * @return mixed
		 */
		public function display_global_colors_front_end( $response, $handler, $request ) {
			$route = $request->get_route();
			if ( 0 !== strpos( $route, '/elementor/v1/globals' ) ) {
				return $response;
			}

			$rest_id = substr( $route, strrpos( $route, '/' ) + 1 );
			$prefix  = self::ELEMENTOR_COLOR_ID_PREFIX;
			if ( 0 !== strpos( $rest_id, $prefix ) ) {
				return $response;
			}

			$index = (int) substr( $rest_id, strlen( $prefix ) );
			if ( $index < 1 ) {
				return $response;
			}

			$colors = self::get_palette_colors();
			$key    = $index - 1;
			if ( ! isset( $colors[ $key ] ) ) {
				return $response;
			}

			$color = nxt_sanitize_color_frontend( $colors[ $key ] );
			return rest_ensure_response(
				array(
					'id'    => esc_attr( $rest_id ),
					'title' => self::get_palette_label( $index ),
					'value' => $color,
				)
			);
		}

		/**
		 * Output Elementor global CSS variables so theme colors render on front end.
		 *
		 * Elementor uses --e-global-color-{id} for global colors. We define these
		 * so widgets using "Theme Color 1" etc. display the Nexter palette.
		 *
		 * @since 1.0.0
		 * @param string $dynamic_css Existing dynamic CSS.
		 * @return string
		 */
		public function generate_global_elementor_style( $dynamic_css ) {
			$colors = self::get_palette_colors();
			if ( empty( $colors ) ) {
				return $dynamic_css;
			}

			$style = array();
			$i     = 1;
			foreach ( $colors as $color ) {
				$color = nxt_sanitize_color_frontend( $color );
				if ( ! empty( $color ) ) {
					$variable_key           = '--e-global-color-' . self::get_elementor_color_id( $i );
					$style[ $variable_key ] = $color;
					$i++;
				}
			}

			if ( empty( $style ) ) {
				return $dynamic_css;
			}

			$palette_css = nexter_generate_css( array( ':root' => $style ) );
			$dynamic_css .= nexter_minify_css_generate( $palette_css );
			return $dynamic_css;
		}
	}
}

new Nexter_Elementor();
