<?php
/**
 * Customizer Control: Typography Preview
 * Type : nxt-typo-preview
 *
 * Renders a live sample of the typography options of its section, so the
 * selected font family / weight / transform / size / line height / colour can
 * be checked without leaving the control panel.
 *
 * @package	Nexter
 * @since	1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Nexter_Control_Typography_Preview extends WP_Customize_Control {

	/**
	 * Control Type
	 */
	public $type = 'nxt-typo-preview';

	/**
	 * Sample text rendered inside the preview box.
	 */
	public $preview_text = '';

	/**
	 * Settings the preview reads from.
	 * Accepted keys : family, weight, transform, size, line-height, color, background
	 */
	public $connect = array();

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$preview_text = ( '' !== $this->preview_text ) ? $this->preview_text : __( 'The quick brown fox', 'nexter' );

		$this->json['label']       = esc_html( $this->label );
		$this->json['description'] = $this->description;
		$this->json['previewText'] = esc_html( $preview_text );
		$this->json['connect']     = (array) $this->connect;

		// Font used when the section is left on "Inherit", same one the site falls back on.
		$inherit_font = function_exists( 'nexter_get_body_fontfamily' ) ? nexter_get_body_fontfamily() : '';
		$this->json['inheritFont'] = ! empty( $inherit_font ) ? $inherit_font : 'sans-serif';
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<# if ( data.label ) { #>
			<span class="customize-control-title">{{{ data.label }}}</span>
		<# } #>

		<div class="nxt-typo-preview-box">
			<span class="nxt-typo-preview-text">{{{ data.previewText }}}</span>
			<span class="nxt-typo-preview-meta"></span>
		</div>

		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>
		<?php
	}

	/**
	 * Render the control's content.
	 *
	 * @see WP_Customize_Control::render_content()
	 */
	protected function render_content() {}
}
