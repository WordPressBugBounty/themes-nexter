/**
 * Nexter — Sidebar Settings Document panel (block editor).
 *
 * Native Gutenberg replacement for the classic "Sidebar Settings" meta box,
 * bound to the REST-registered post meta. No build step (uses global wp.* runtime).
 */
( function ( wp ) {
	'use strict';

	if ( ! wp || ! wp.plugins || ! wp.element || ! wp.data || ! wp.components ) {
		return;
	}

	var el = wp.element.createElement;
	var registerPlugin = wp.plugins.registerPlugin;
	var SelectControl = wp.components.SelectControl;
	var useSelect = wp.data.useSelect;
	var useDispatch = wp.data.useDispatch;
	var __ = ( wp.i18n && wp.i18n.__ ) ? wp.i18n.__ : function ( s ) { return s; };

	// PluginDocumentSettingPanel moved from wp.editPost to wp.editor in WP 6.6+
	// (wp.editPost is deprecated/empty on WP 7.0), so prefer wp.editor.
	var PluginDocumentSettingPanel =
		( wp.editor && wp.editor.PluginDocumentSettingPanel ) ||
		( wp.editPost && wp.editPost.PluginDocumentSettingPanel );

	if ( ! PluginDocumentSettingPanel ) {
		return;
	}

	var data = window.nxtSidebar || {};
	var allowedPostTypes = data.postTypes || [ 'post', 'page' ];

	var META_LAYOUT = 'nxt-post-page-sidebar';
	var META_SELECT = 'nxt-post-page-display-sidebar';
	var META_CUSTOM = 'nxt-post-page-custom-sidebar';

	/**
	 * Convert a { value: label } map (or array) into SelectControl options.
	 *
	 * @param {Object|Array} map Key/label map.
	 * @return {Array} Options.
	 */
	function toOptions( map ) {
		if ( ! map ) {
			return [];
		}
		return Object.keys( map ).map( function ( key ) {
			return { label: String( map[ key ] ), value: key };
		} );
	}

	function SidebarPanel() {
		// All hooks run unconditionally and in a stable order (Rules of Hooks);
		// the post-type gate happens only after every hook has been called.
		var postType = useSelect( function ( select ) {
			return select( 'core/editor' ).getCurrentPostType();
		}, [] );

		var meta = useSelect( function ( select ) {
			return select( 'core/editor' ).getEditedPostAttribute( 'meta' ) || {};
		}, [] );

		var editPost = useDispatch( 'core/editor' ).editPost;

		if ( allowedPostTypes.indexOf( postType ) === -1 ) {
			return null;
		}

		function update( key, value ) {
			var next = Object.assign( {}, meta );
			next[ key ] = value;
			editPost( { meta: next } );
		}

		var layoutOptions = toOptions( data.displayOptions || {} );
		var sidebarOptions = toOptions( data.sidebars || {} );
		var customOptions = toOptions( data.customSidebars || {} );

		var layoutValue = meta[ META_LAYOUT ] || 'default';
		var selectValue = meta[ META_SELECT ] || '';

		// Mirror the front-end logic (nexter_site_sidebar_layout): the per-post sidebar
		// selection only applies when a left/right sidebar is chosen, and the custom
		// template only applies when "Select Sidebar" is set to "Custom Sidebar".
		var showSidebarSelect = ( 'left-sidebar' === layoutValue || 'right-sidebar' === layoutValue );
		var showCustomSelect = showSidebarSelect && 'custom' === selectValue;

		var children = [];

		children.push(
			el( SelectControl, {
				key: 'layout',
				label: __( 'Display Sidebar', 'nexter' ),
				value: layoutValue,
				options: layoutOptions,
				onChange: function ( value ) {
					update( META_LAYOUT, value );
				},
			} )
		);

		if ( showSidebarSelect && sidebarOptions.length ) {
			children.push(
				el( SelectControl, {
					key: 'select',
					label: __( 'Select Sidebar', 'nexter' ),
					value: selectValue,
					options: sidebarOptions,
					onChange: function ( value ) {
						update( META_SELECT, value );
					},
				} )
			);
		}

		// Custom Sidebar template — only when "Custom Sidebar" is selected above and the
		// Nexter Extension provides Theme Builder templates.
		if ( showCustomSelect && customOptions.length ) {
			children.push(
				el( SelectControl, {
					key: 'custom',
					label: __( 'Custom Sidebar', 'nexter' ),
					value: meta[ META_CUSTOM ] || 'none',
					options: customOptions,
					onChange: function ( value ) {
						update( META_CUSTOM, value );
					},
				} )
			);
		}

		return el(
			PluginDocumentSettingPanel,
			{
				name: 'nexter-sidebar-settings',
				title: __( 'Sidebar Settings', 'nexter' ),
				className: 'nexter-sidebar-settings-panel',
			},
			children
		);
	}

	registerPlugin( 'nexter-sidebar-settings', {
		render: SidebarPanel,
		icon: 'align-pull-left',
	} );
} )( window.wp );
