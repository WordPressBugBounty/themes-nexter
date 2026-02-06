<?php
/**
 * Customizer Control: Color Palette
 * Type : nxt-color-palette
 *
 * @package	Nexter
 * @since	1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Nexter_Control_Color_Palette extends WP_Customize_Control {

	/**
	 * Control Type
	 */
	public $type = 'nxt-color-palette';

	/**
	 * Sanitize color palette value. Accepts hex (#xxx, #xxxxxx, #xxxxxxxx), rgb(), rgba(), and other valid CSS colors.
	 * Use this as sanitize_callback when registering the setting: array( $control, 'sanitize_palette' ) or Nexter_Control_Color_Palette::sanitize_palette_static().
	 *
	 * @param mixed $value Raw value (string JSON array or array of color strings).
	 * @return array Array of sanitized color strings.
	 */
	public function sanitize_palette( $value ) {
		return self::sanitize_palette_static( $value );
	}

	/**
	 * Static sanitizer for color palette. Accepts hex, rgb, rgba, and valid CSS color strings.
	 *
	 * @param mixed $value Raw value (string JSON array or array of color strings).
	 * @return array Array of sanitized color strings.
	 */
	public static function sanitize_palette_static( $value ) {
		$colors = array();
		if ( is_string( $value ) ) {
			$decoded = json_decode( $value, true );
			if ( is_array( $decoded ) ) {
				$colors = $decoded;
			}
		} elseif ( is_array( $value ) ) {
			$colors = $value;
		}
		$out = array();
		foreach ( $colors as $color ) {
			if ( is_string( $color ) && '' !== trim( $color ) ) {
				$out[] = self::sanitize_single_color( $color );
			}
		}
		return $out;
	}

	/**
	 * Sanitize a single color value (hex, rgb, rgba, or valid CSS color).
	 *
	 * @param string $color Color string.
	 * @return string Sanitized color string.
	 */
	public static function sanitize_single_color( $color ) {
		$color = trim( $color );
		if ( '' === $color ) {
			return '#ffffff';
		}
		// Hex: #rgb, #rrggbb, #rrggbbaa
		if ( preg_match( '/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6}|[A-Fa-f0-9]{8})$/', $color ) ) {
			return $color;
		}
		// rgb(r,g,b) or rgba(r,g,b,a) — allow integers or decimals, with optional spaces
		if ( preg_match( '/^rgba?\\s*\\(\\s*[\\d.]+\\s*,\\s*[\\d.]+\\s*,\\s*[\\d.]+\\s*(,\\s*[\\d.]+\\s*)?\\)$/i', $color ) ) {
			return $color;
		}
		// hsl/hsla
		if ( preg_match( '/^hsla?\\s*\\(/', $color ) ) {
			return $color;
		}
		// Named colors (basic set)
		$allowed_names = array( 'transparent', 'currentColor', 'inherit', 'initial', 'unset' );
		if ( in_array( strtolower( $color ), $allowed_names, true ) ) {
			return $color;
		}
		// Fallback: allow any string that looks like a CSS color (contains rgb, rgba, #, or hsl)
		if ( false !== strpos( $color, 'rgb' ) || false !== strpos( $color, '#' ) || false !== strpos( $color, 'hsl' ) ) {
			return wp_strip_all_tags( $color );
		}
		// Default
		return $color;
	}

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
		<div class="customize-control-content nxt-color-palette-control">
			<# if ( data.label ) { #>
				<label>
					<span class="customize-control-title">{{{ data.label }}}</span>
				</label>
			<# } #>
			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>
			
			<div class="nxt-color-palette-wrapper">
				<div class="nxt-color-palette-swatches">
					<# 
					var colors = [];
					if ( data.value && typeof data.value === 'string' ) {
						try {
							colors = JSON.parse( data.value );
						} catch(e) {
							colors = [];
						}
					} else if ( Array.isArray( data.value ) ) {
						colors = data.value;
					}
					
					if ( colors.length === 0 && data.default && Array.isArray( data.default ) ) {
						colors = data.default;
					}
					
					_.each( colors, function( color, index ) { #>
						<div class="nxt-color-swatch-item" data-index="{{ index }}">
							<div class="nxt-color-swatch" style="background-color: {{ color }};" data-color="{{ color }}">
								<button type="button" class="nxt-color-remove" data-index="{{ index }}" title="<?php esc_attr_e( 'Remove Color', 'nexter' ); ?>">
									<svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" fill="none" viewBox="0 0 6 6"><path fill="#666" d="M4.459 5.078 2.603 3.22.747 5.078a.437.437 0 1 1-.619-.619l1.856-1.856L.128.747A.438.438 0 0 1 .747.128l1.856 1.856L4.459.128a.438.438 0 0 1 .618.619L3.221 2.603l1.856 1.856a.437.437 0 1 1-.618.619"/></svg>
								</button>
							</div>
							<div class="nxt-color-picker-wrapper">
								<input type="text" class="nxt-color-picker-alpha color-picker-hex nxt-color-picker-{{ index }}" data-alpha="true" data-index="{{ index }}" value="{{ color }}" style="display: none;" />
							</div>
						</div>
					<# }); #>
					<div class="nxt-color-swatch-item nxt-add-color-item">
						<button type="button" class="nxt-add-color" title="<?php esc_attr_e( 'Add Color', 'nexter' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 16 16"><path stroke="#666" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.333" d="M8 3.332v9.333M3.334 8h9.333"/></svg>
						</button>
					</div>
				</div>
				
				<!-- WordPress Default Color Picker Modal -->
				<div class="nxt-color-picker-modal" style="display: none;">
					<div class="nxt-color-picker-content">
						<div class="nxt-color-picker-wp-wrapper"></div>
					</div>
				</div>
				
				<input type="hidden" class="nxt-color-palette-value" value="" {{{ data.link }}} />
			</div>
		</div>
		<?php
	}
}
