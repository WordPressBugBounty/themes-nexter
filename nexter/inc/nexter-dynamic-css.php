<?php
/**
 * Nexter Customizer Options Css
 *
 * @package	Nexter
 * @since	1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Nexter Theme Dynamic CSS
 */
if (!class_exists('Nexter_Dynamic_Css')) {

    class Nexter_Dynamic_Css {

        /**
         * Build (and cache) the dynamic theme CSS.
         *
         * The CSS generation + minification is expensive and previously ran on every
         * request. It is now cached in a transient keyed on a hash of the fully
         * resolved rules array (plus NXT_VERSION). Because the key derives from the
         * actual rules, it is self-invalidating: any Customizer change or per-post
         * override produces a different key and fresh CSS, while stale entries orphan
         * and expire via TTL. Per-page variation is handled correctly (unlike a single
         * global cache). Bypass with SCRIPT_DEBUG or the 'nexter_dynamic_css_use_cache'
         * filter.
         *
         * @param array|null $theme_css Optional pre-resolved rules array.
         * @return string Generated (minified) CSS.
         */
        public static function render_theme_css( $theme_css = null ) {

            $theme_css = apply_filters( 'nxt_render_theme_css', $theme_css );

            if ( empty( $theme_css ) ) {
                return apply_filters( 'nexter_theme_dynamic_css', '' );
            }

            $use_cache = apply_filters( 'nexter_dynamic_css_use_cache', ! ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) );
            $cache_key = 'nexter_dyn_css_' . md5( NXT_VERSION . '|' . wp_json_encode( $theme_css ) );

            if ( $use_cache ) {
                $cached = get_transient( $cache_key );
                if ( false !== $cached ) {
                    return apply_filters( 'nexter_theme_dynamic_css', $cached );
                }
            }
            
            $parse_css = $parse_tablet_css = $parse_mobile_css = '';

            foreach ( $theme_css as $key => $value ) {

                if ( $key === 'root_tablet' && ! empty( $value ) ) {

                    $parse_css .= nexter_generate_css( $value, '', '780' ); //Root tablet max=780

                } else if ( $key === 'tablet' && ! empty( $value ) ) {

                    $parse_tablet_css .= nexter_generate_css( $value, '', '1024' );   //tablet max=1024

                } else if ( $key === 'mobile' && ! empty( $value ) ) {

                    $parse_mobile_css .= nexter_generate_css( $value, '', '767' ); //mobile max=767

                } else if ( $key === 'container_d' && ! empty( $value ) ) {

                    $parse_css .= nexter_generate_css( $value, '1200' );  //desktop container min=1200

                } else if ( $key === 'container_t' && ! empty( $value ) ) {

                    $parse_css .= nexter_generate_css( $value, '768', '1199' );  //tablet container min=768 max=1199

                } else if ( $key === 'container_m' && ! empty( $value ) ) {

                    $parse_css .= nexter_generate_css( $value, '', '767' );  //mobile container max=767

                } else if ( ! empty( $value ) ) {

                    $parse_css .= nexter_generate_css( $value );  //Normal/default Css

                }
            }

            //Minify css
            $dynamic_css = nexter_minify_css_generate( $parse_css . $parse_tablet_css . $parse_mobile_css );

            if ( $use_cache ) {
                set_transient( $cache_key, $dynamic_css, DAY_IN_SECONDS );
            }

            return apply_filters( 'nexter_theme_dynamic_css', $dynamic_css );
        }

    }
}
