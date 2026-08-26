<?php
/**
 * Customizer Control: Background
 * Type : nxt-background
 *
 * @package	Nexter
 * @since	1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Nexter_Control_Background' ) && class_exists( 'WP_Customize_Control' ) ) :

	class Nexter_Control_Background extends WP_Customize_Control {

		/**
		 * Control Type
		 */
		public $type = 'nxt-background';

		/**
		 * Refresh parameters passed to the JavaScript via JSON.
		 *
		 * @see WP_Customize_Control::to_json()
		 */
		public function to_json() {
			parent::to_json();

			$this->json['default'] = $this->setting->default;
			if ( isset( $this->default ) ) {
				$this->json['default'] = $this->default;
			}
			
			$this->json['label'] = esc_html( $this->label );
			$this->json['value'] = $this->value();
			$this->json['link']  = $this->get_link();
			$this->json['id']    = $this->id;

			$this->json['inputAttrs'] = '';
			foreach ( $this->input_attrs as $attr => $value ) {
				$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
			}
		}

		/**
		 * Icons used by this control.
		 *
		 * One stroked set at a single weight and viewBox, so the type toggles, the
		 * reset and the media buttons all read alike. The row previously mixed two
		 * dashicon font glyphs with a solid-filled svg, which is why they looked
		 * mismatched and oversized. Size comes from css, colour from currentColor.
		 */
		private function get_icon( $name, $extra_class = '' ) {

			$icons = array(
				'reset'  => '<polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>',
				'color'  => '<path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>',
				'image'  => '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>',
				'trash'  => '<polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>',
				'upload' => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>',
			);

			if ( ! isset( $icons[ $name ] ) ) {
				return '';
			}

			$classes = 'nxt-bg-icon nxt-bg-icon-' . $name . ( $extra_class ? ' ' . $extra_class : '' );

			return '<svg class="' . esc_attr( $classes ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg">' . $icons[ $name ] . '</svg>';
		}

		/**
		 * An Underscore (JS) template for this control's content (but not its container).
		 *
		 * Class variables for this control class are available in the `data` JS object;
		 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
		 *
		 * @see WP_Customize_Control::print_template()
		 *
		 * @access protected
		 */
		protected function content_template() {
			?>
			<# if ( data.label || data.description ) { #>
				<label>
					<# if ( data.label ) { #>
						<span class="customize-control-title">{{{ data.label }}}</span>
					<# } #>
					<# if ( data.description ) { #>
						<span class="description customize-control-description">{{{ data.description }}}</span>
					<# } #>
				</label>
			<# } #>
			<div class="nxt-control-background">
				<!--Background Type -->
				<div class="nxt-bg-type-inner nxt-d-flex nxt-align-center">
					<div class="nxt-bg-title"><?php esc_html_e( 'Background Type', 'nexter' ); ?></div>
					
					<div class="nxt-bg-type-list">
						<button type="button" class="nxt-bg-reset" aria-label="<?php esc_attr_e( 'Reset background', 'nexter' ); ?>" title="<?php esc_attr_e( 'Reset background', 'nexter' ); ?>">
							<?php echo $this->get_icon( 'reset' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</button>
						<input {{{ data.inputAttrs }}} class="switch-input screen-reader-text" type="radio" value="color" name="_customize-bg-{{{ data.id }}}-type123" id="{{ data.id }}type-color" <# if ( 'color' === data.value['bg-type'] ) { #> checked="checked" <# } #>>
						<label class="nxt-check-btn" for="{{ data.id }}type-color"><?php echo $this->get_icon( 'color' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
						
						<input {{{ data.inputAttrs }}} class="switch-input screen-reader-text" type="radio" value="image" name="_customize-bg-{{{ data.id }}}-type123" id="{{ data.id }}type-image" <# if ( 'image' === data.value['bg-type'] ) { #> checked="checked" <# } #>>
						<label class="nxt-check-btn" for="{{ data.id }}type-image"><?php echo $this->get_icon( 'image' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
					</div>
				</div>
				
				<!-- background color -->
				<div class="nxt-bg-extra nxt-bg-color nxt-d-flex nxt-align-center <# if ( data.value['bg-type'] === 'color' ) { #><# } else { #> hidden <# } #>">
					<div class="nxt-bg-title"><?php esc_html_e( 'Background Color', 'nexter' ); ?></div>
					<span class="nxt-color-field">
						<input type="text" data-default-color="{{ data.default['bg-color'] }}" data-alpha="true" value="{{ data.value['bg-color'] }}" class="nxt-color-control"/>
					</span>
				</div>
				
				<!-- background image -->
				<div class="nxt-bg-image <# if ( data.value['bg-type'] === 'image' ) { #><# } else { #> hidden <# } #>">
					<div class="nxt-bg-title"><?php esc_html_e( 'Background Image', 'nexter' ); ?></div>
					<div class="attachment-media-view bg-image-upload">
						<# if ( data.value['bg-image'] ) { #>
							<div class="thumbnail thumbnail-image"><img src="{{ data.value['bg-image'] }}" alt="" /></div>
						<# } else { #>
							<div class="placeholder"><?php esc_html_e( 'No File Selected', 'nexter' ); ?></div>
						<# } #>
						<div class="actions">
							<button type="button" class="button bg-image-upload-remove-button<# if ( ! data.value['bg-image'] ) { #> hidden <# } #>" aria-label="<?php esc_attr_e( 'Remove image', 'nexter' ); ?>" title="<?php esc_attr_e( 'Remove image', 'nexter' ); ?>"><?php echo $this->get_icon( 'trash', 'bg-img-remove-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
							<button type="button" class="button bg-image-upload-button" aria-label="<?php esc_attr_e( 'Select image', 'nexter' ); ?>" title="<?php esc_attr_e( 'Select image', 'nexter' ); ?>"><?php echo $this->get_icon( 'upload', 'bg-img-upload-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
						</div>
					</div>
				</div>
				
				<!-- background position -->
				<div class="nxt-bg-extra nxt-bg-position nxt-d-flex nxt-align-center <# if ( data.value['bg-type'] === 'image' && data.value['bg-image'] ) { #><# } else { #> hidden <# } #>">
					<div class="nxt-bg-title"><?php esc_html_e( 'Background Position', 'nexter' ); ?></div>
					<select {{{ data.inputAttrs }}}>
						<option value="left top"<# if ( 'left top' === data.value['bg-position'] ) { #> selected <# } #>><?php esc_html_e( 'Left Top', 'nexter' ); ?></option>
						<option value="left center"<# if ( 'left center' === data.value['bg-position'] ) { #> selected <# } #>><?php esc_html_e( 'Left Center', 'nexter' ); ?></option>
						<option value="left bottom"<# if ( 'left bottom' === data.value['bg-position'] ) { #> selected <# } #>><?php esc_html_e( 'Left Bottom', 'nexter' ); ?></option>
						
						<option value="center top"<# if ( 'center top' === data.value['bg-position'] ) { #> selected <# } #>><?php esc_html_e( 'Center Top', 'nexter' ); ?></option>
						<option value="center center"<# if ( 'center center' === data.value['bg-position'] ) { #> selected <# } #>><?php esc_html_e( 'Center Center', 'nexter' ); ?></option>
						<option value="center bottom"<# if ( 'center bottom' === data.value['bg-position'] ) { #> selected <# } #>><?php esc_html_e( 'Center Bottom', 'nexter' ); ?></option>
						
						<option value="right top"<# if ( 'right top' === data.value['bg-position'] ) { #> selected <# } #>><?php esc_html_e( 'Right Top', 'nexter' ); ?></option>
						<option value="right center"<# if ( 'right center' === data.value['bg-position'] ) { #> selected <# } #>><?php esc_html_e( 'Right Center', 'nexter' ); ?></option>
						<option value="right bottom"<# if ( 'right bottom' === data.value['bg-position'] ) { #> selected <# } #>><?php esc_html_e( 'Right Bottom', 'nexter' ); ?></option>
					</select>
				</div>
				
				<!-- background size -->
				<div class="nxt-bg-extra nxt-bg-size nxt-d-flex nxt-align-center <# if ( data.value['bg-type'] === 'image' && data.value['bg-image'] ) { #><# } else { #> hidden <# } #>">
					<div class="nxt-bg-title"><?php esc_html_e( 'Background Size', 'nexter' ); ?></div>
					<select {{{ data.inputAttrs }}}>
						<option value="auto"<# if ( 'auto' === data.value['bg-size'] ) { #> selected <# } #>><?php esc_html_e( 'Auto', 'nexter' ); ?></option>
						<option value="cover"<# if ( 'cover' === data.value['bg-size'] ) { #> selected <# } #>><?php esc_html_e( 'Cover', 'nexter' ); ?></option>
						<option value="contain"<# if ( 'contain' === data.value['bg-size'] ) { #> selected <# } #>><?php esc_html_e( 'Contain', 'nexter' ); ?></option>
						<option value="repeat-y"<# if ( 'repeat-y' === data.value['bg-size'] ) { #> selected <# } #>><?php esc_html_e( 'Repeat-Y', 'nexter' ); ?></option>
					</select>
				</div>
				
				<!-- background repeat -->
				<div class="nxt-bg-extra nxt-bg-repeat nxt-d-flex nxt-align-center <# if ( data.value['bg-type'] === 'image' && data.value['bg-image'] ) { #><# } else { #> hidden <# } #>">
					<div class="nxt-bg-title"><?php esc_html_e( 'Background Repeat', 'nexter' ); ?></div>
					<select {{{ data.inputAttrs }}}>
						<option value="no-repeat"<# if ( 'no-repeat' === data.value['bg-repeat'] ) { #> selected <# } #>><?php esc_html_e( 'No Repeat', 'nexter' ); ?></option>
						<option value="repeat"<# if ( 'repeat' === data.value['bg-repeat'] ) { #> selected <# } #>><?php esc_html_e( 'Repeat All', 'nexter' ); ?></option>
						<option value="repeat-x"<# if ( 'repeat-x' === data.value['bg-repeat'] ) { #> selected <# } #>><?php esc_html_e( 'Repeat-X', 'nexter' ); ?></option>
						<option value="repeat-y"<# if ( 'repeat-y' === data.value['bg-repeat'] ) { #> selected <# } #>><?php esc_html_e( 'Repeat-Y', 'nexter' ); ?></option>
					</select>
				</div>
				
				<!-- background attachment -->
				<div class="nxt-bg-extra nxt-bg-attachment nxt-d-flex nxt-align-center <# if ( data.value['bg-type'] === 'image' && data.value['bg-image'] ) { #><# } else { #> hidden <# } #>">
					<div class="nxt-bg-title"><?php esc_html_e( 'Background Attachment', 'nexter' ); ?></div>
					<select {{{ data.inputAttrs }}}>
						<option value="inherit"<# if ( 'inherit' === data.value['bg-attachment'] ) { #> selected <# } #>><?php esc_html_e( 'Inherit', 'nexter' ); ?></option>
						<option value="scroll"<# if ( 'scroll' === data.value['bg-attachment'] ) { #> selected <# } #>><?php esc_html_e( 'Scroll', 'nexter' ); ?></option>
						<option value="fixed"<# if ( 'fixed' === data.value['bg-attachment'] ) { #> selected <# } #>><?php esc_html_e( 'Fixed', 'nexter' ); ?></option>
					</select>
				</div>
				<input class="background-hidden-val" type="hidden" {{{ data.link }}}>
			</div>
			<?php
		}

		/**
		 * Render the control's content.
		 */
		protected function render_content() {}
	}
endif;