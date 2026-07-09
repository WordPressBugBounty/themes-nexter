<?php
/**
 * Register customizer panels & sections (optimized)
 *
 * @package Nexter
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'Nexter_Customizer_Register_Sections_Panels' ) ) {

	class Nexter_Customizer_Register_Sections_Panels {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'customize_register', [ $this, 'register_configuration' ] );
		}

		/**
		 * Register panels & sections
		 */
		public function register_configuration( $wp_customize ) {

			// Panels config
			$panels = [
				'panel-global-general' => [
					'title'    => esc_html__( 'General', 'nexter' ),
					'priority' => 5,
					'sections' => [
						'section-site-layout-container' => [ esc_html__( 'Container', 'nexter' ), 5 ],
						'section-header-mode'           => [ esc_html__( 'Header', 'nexter' ), 10 ],
						'section-footer-mode'           => [ esc_html__( 'Footer', 'nexter' ), 10 ],
						'section-layout-sidebar'        => [ esc_html__( 'Sidebar', 'nexter' ), 10 ],
						'section-body-style'            => [ esc_html__( 'Body Style', 'nexter' ), 15 ],
						'section-selected-text-style'   => [ esc_html__( 'Selection Text Color', 'nexter' ), 20 ],
						'section-maintenance-mode'      => [ esc_html__( 'Maintenance Mode', 'nexter' ), 30 ],
					],
				],
				'panel-styling-colors' => [
					'title'    => esc_html__( 'Styling Colors', 'nexter' ),
					'priority' => 15,
					'sections' => [
						'section-color-palette'  => [ esc_html__( 'Color Palette', 'nexter' ), 1 ],
						'section-body-colors'    => [ esc_html__( 'Body', 'nexter' ), 5 ],
						'section-heading-colors' => [ esc_html__( 'Headings H1-H6', 'nexter' ), 10 ],
					],
				],
				'panel-typography' => [
					'title'    => esc_html__( 'Typography', 'nexter' ),
					'priority' => 20,
					'sections' => [
						'section-body-typography' => [ esc_html__( 'Body', 'nexter' ), 5 ],
						'section-heading-h1-typo' => [ esc_html__( 'Heading H1', 'nexter' ), 5 ],
						'section-heading-h2-typo' => [ esc_html__( 'Heading H2', 'nexter' ), 10 ],
						'section-heading-h3-typo' => [ esc_html__( 'Heading H3', 'nexter' ), 15 ],
						'section-heading-h4-typo' => [ esc_html__( 'Heading H4', 'nexter' ), 20 ],
						'section-heading-h5-typo' => [ esc_html__( 'Heading H5', 'nexter' ), 25 ],
						'section-heading-h6-typo' => [ esc_html__( 'Heading H6', 'nexter' ), 30 ],
					],
				],
				'panel-pages-option' => [
					'title'    => esc_html__( 'Pages', 'nexter' ),
					'priority' => 22,
					'sections' => [
						'section-page-single' => [ esc_html__( 'Single Page', 'nexter' ), 5 ],
					],
				],
				'panel-blog-layout' => [
					'title'    => esc_html__( 'Blog', 'nexter' ),
					'priority' => 25,
					'sections' => [
						'section-blog-single' => [ esc_html__( 'Single Post', 'nexter' ), 5 ],
					],
				],
			];

			// Add panels & their sections
			foreach ( $panels as $panel_id => $panel ) {
				$wp_customize->add_panel(
					new Nexter_Customizer_Panel(
						$wp_customize,
						$panel_id,
						[
							'title'    => $panel['title'],
							'priority' => $panel['priority'],
						]
					)
				);

				if ( ! empty( $panel['sections'] ) ) {
					foreach ( $panel['sections'] as $section_id => $section ) {
						$wp_customize->add_section(
							new Nexter_Customizer_Section(
								$wp_customize,
								$section_id,
								[
									'title'    => $section[0],
									'priority' => $section[1],
									'panel'    => $panel_id,
								]
							)
						);
					}
				}
			}

			// Standalone section (Site Identity)
			$wp_customize->add_section(
				new Nexter_Customizer_Section(
					$wp_customize,
					'title_tagline',
					[
						'title'    => __( 'Site Identity', 'nexter' ),
						'priority' => 5,
					]
				)
			);
		}
	}
}

new Nexter_Customizer_Register_Sections_Panels();