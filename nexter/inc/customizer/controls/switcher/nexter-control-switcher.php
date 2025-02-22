<?php
/**
 * Customizer Control: Switcher
 * Type : nxt-switcher
 *
 * @package	Nexter
 * @since	1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Nexter_Control_Switcher' ) && class_exists( 'WP_Customize_Control' ) ) :

	class Nexter_Control_Switcher extends WP_Customize_Control {

		/**
		 * Control Type
		 */
		public $type = 'nxt-switcher';

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

			$this->json['value']          = $this->value();
			// $this->json['choices']        = $this->choices;
			$this->json['link']           = $this->get_link();
			$this->json['id']             = $this->id;
			$this->json['label']          = esc_html( $this->label );						
			$this->json['inputAttrs']     = '';

			foreach ( $this->input_attrs as $attr => $value ) {
				$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
			}
			$this->json['inputAttrs'] = maybe_serialize( $this->input_attrs() );

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
			<div class='nxt-switcher'>

				<# if ( data.label ) { #>
					<span class="customize-control-title">{{{ data.label }}}</span>
				<# } #>
				<# if ( data.description ) { #>
					<span class="description customize-control-description">{{{ data.description }}}</span>
				<# } #>

				<div class="nxt-switcher-wrap">
					<div id="input_{{ data.id }}" class="nxt-switcher">
						<input class="switch-input" type="checkbox" value="{{data.value}}" name="_customize-radio-{{{ data.id }}}" id="{{ data.id }}" {{{ data.link }}} <# if ( "on" === data.value ) { #> checked="checked" <# } #>>
						<label class="switch-label switch-label-on" for="{{ data.id }}"></label>
					</div>
				</div>
			
			</div>

			<?php
		}

		/**
		 * Render the control's content.
		 *
		 * @see WP_Customize_Control::render_content()
		 */
		protected function render_content() {}
	}

endif;
