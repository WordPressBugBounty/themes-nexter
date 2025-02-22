<?php
/**
 * Customizer Control: Slider
 * Type : nxt-slider
 *
 * @package	Nexter
 * @since	1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Nexter_Control_Slider extends WP_Customize_Control {

	/**
	 * Control Type.
	 */
	public $type = 'nxt-slider';

	/**
	 * The control type.
	 */
	public $suffix = '';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['default'] = $this->setting->default;
		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		}
		$this->json['value']  = $this->value();
		$this->json['link']   = $this->get_link();
		$this->json['id']     = $this->id;
		$this->json['label']  = esc_html( $this->label );
		$this->json['suffix'] = $this->suffix;

		$this->json['inputAttrs'] = '';
		foreach ( $this->input_attrs as $attr => $value ) {
			$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
		}
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
		<label>
			<# if ( data.label ) { #>
				<span class="customize-control-title">{{{ data.label }}}</span>
			<# } #>
			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>
			<div class="nxt_slider_wrap">
				<input {{{ data.inputAttrs }}} type="range" value="{{ data.value }}" data-reset_value="{{ data.default }}" />
				<div class="nxt_range_value">
					<input type="number" class="value nxt-slide-val-input" {{{ data.link }}} value="{{ data.value }}" {{{ data.inputAttrs }}} ><#
					if ( data.suffix ) {

					#><span class="nxt-slide-unit">{{ data.suffix }}</span><#
					} #>
				</div>
				<div class="nxt-slider-reset">
					<span class="dashicons dashicons-image-rotate"></span>
				</div>
			</div>
		</label>
		<?php
	}

	/**
	 * Render the control's content.
	 *
	 * @see WP_Customize_Control::render_content()
	 */
	protected function render_content() {}
}
