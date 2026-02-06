<?php
/**
 * Color Palette Options for Nexter Theme.
 *
 * @package	Nexter
 * @since	1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Nexter_Color_Palette' ) ) {

	class Nexter_Color_Palette extends Nexter_Customizer_Config {
		
		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			parent::__construct();
		}
		
		/**
		 * Register Color Palette Customizer Configurations.
		 * @since 1.0.0
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			// Get default colors from helper function
			$default_colors = nxt_get_default_color_palette();
			$default_colors_array = array();
			foreach ( $default_colors as $key => $color ) {
				$default_colors_array[] = $color;
			}

			$options = array(
				array(
					'name'      => NXT_OPTIONS . '[heading-color-palette]',
					'type'      => 'control',
					'control'   => 'nxt-heading',
					'section'   => 'section-color-palette',
					'priority'  => 5,
					'title'     => __( 'Global Color Palette', 'nexter' ),
					'settings'  => array(),
					'separator' => false,
				),
				array(
					'name'     => NXT_OPTIONS . '[global-color-palette]',
					'type'     => 'control',
					'control'  => 'nxt-color-palette',
					'section'  => 'section-color-palette',
					'priority' => 10,
					'default'  => $default_colors_array,
					'title'    => __( 'Color Palette', 'nexter' ),
					'description' => __( 'Add, remove, or modify colors in your global color palette. These colors will be available throughout your theme.', 'nexter' ),
				),
			);

			return array_merge( $configurations, $options );
		}

	}
}

new Nexter_Color_Palette;
