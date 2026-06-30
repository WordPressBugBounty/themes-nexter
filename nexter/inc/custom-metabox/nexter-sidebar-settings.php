<?php
/**
 * Sidebar Settings — block-editor (Gutenberg) implementation.
 *
 * @package Nexter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexter_sidebar_settings_meta_keys' ) ) {
	/**
	 * Sidebar settings meta keys.
	 *
	 * @return string[] Meta keys.
	 */
	function nexter_sidebar_settings_meta_keys() {
		return array(
			'nxt-post-page-sidebar',
			'nxt-post-page-display-sidebar',
			'nxt-post-page-custom-sidebar',
		);
	}
}

/**
 * Register the sidebar settings as REST-exposed post meta for posts and pages.
 *
 * Runs in every context (admin, REST, front end) so the block editor can read and
 * write the meta over the REST API.
 *
 * @return void
 */
function nexter_register_sidebar_settings_meta() {
	$auth_callback = function ( $allowed, $meta_key, $object_id ) {
		return current_user_can( 'edit_post', $object_id );
	};

	foreach ( array( 'post', 'page' ) as $post_type ) {
		foreach ( nexter_sidebar_settings_meta_keys() as $meta_key ) {
			register_post_meta(
				$post_type,
				$meta_key,
				array(
					'type'              => 'string',
					'single'            => true,
					'default'           => '',
					'show_in_rest'      => true,
					'sanitize_callback' => 'sanitize_text_field',
					'auth_callback'     => $auth_callback,
				)
			);
		}
	}
}
add_action( 'init', 'nexter_register_sidebar_settings_meta' );

/**
 * Enqueue the Gutenberg Document panel for sidebar settings (post/page only).
 *
 * @return void
 */
function nexter_sidebar_settings_editor_assets() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'post' !== $screen->base || ! in_array( $screen->post_type, array( 'post', 'page' ), true ) ) {
		return;
	}

	$handle  = 'nexter-sidebar-settings';
	$rel_js  = 'assets/js/admin/sidebar-settings.js';
	$version = file_exists( NXT_THEME_DIR . $rel_js ) ? filemtime( NXT_THEME_DIR . $rel_js ) : NXT_VERSION;

	wp_enqueue_script(
		$handle,
		NXT_THEME_URI . $rel_js,
		array( 'wp-plugins', 'wp-editor', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-data', 'wp-i18n' ),
		$version,
		true
	);

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( $handle, 'nexter' );
	}

	$custom_sidebars = array();
	if ( post_type_exists( 'nxt_builder' ) ) {
		$custom_sidebars['none'] = esc_html__( 'Select Template', 'nexter' );
		$builder_ids             = get_posts(
			array(
				'post_type'        => 'nxt_builder',
				'post_status'      => 'publish',
				'numberposts'      => -1,
				'fields'           => 'ids',
				'suppress_filters' => true,
			)
		);
		foreach ( $builder_ids as $builder_id ) {
			$custom_sidebars[ (string) $builder_id ] = get_the_title( $builder_id );
		}
	}

	wp_localize_script(
		$handle,
		'nxtSidebar',
		array(
			'postTypes'      => array( 'post', 'page' ),
			'displayOptions' => array(
				'default'       => esc_html__( 'Customizer Default', 'nexter' ),
				'no-sidebar'    => esc_html__( 'No Sidebar', 'nexter' ),
				'left-sidebar'  => esc_html__( 'Left Sidebar', 'nexter' ),
				'right-sidebar' => esc_html__( 'Right Sidebar', 'nexter' ),
			),
			'sidebars'       => nexter_get_sidebar_list(),
			'customSidebars' => $custom_sidebars,
		)
	);
}
add_action( 'enqueue_block_editor_assets', 'nexter_sidebar_settings_editor_assets' );
