<?php 
/**
 * Nexter Settings Panel
 *
 * @package	Nexter
 * @since	1.0.0
 */
if (!defined('ABSPATH')) {
    exit;
}

/*
function nexter_ext_plugin_load_notice() {
	$plugin = 'nexter-extension/nexter-extension.php';
	$output = '';
	$installed_plugins = get_plugins();
	if ( isset( $installed_plugins[ $plugin ] ) ) {
		if ( ! current_user_can( 'activate_plugins' ) ) { return; }
		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
		$output .= sprintf( '<a href="%s">%s</a>', $activation_url, esc_html__( 'Activate Nexter Extensions', 'nexter' ) );
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) { return; }
		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=nexter-extension' ), 'install-plugin_nexter-extension' );
		$output .= sprintf( '<a href="%s">%s</a>', $install_url, esc_html__( 'Install Nexter Extension', 'nexter' ) );
	}
	return wp_kses_post($output);
}
*/

class Nexter_Settings_Panel {
	
	/**
     * Option key, and option page slug
     */
    private $key = 'nexter_settings_opts';
	
	/**
     * Array of meta boxes/fields
     * @var array
     */
    protected $option_metabox = array();
    
	/**
     * Setting Name/Title
     * @var string
     */
    protected $setting_name = '';

	/**
     * Array of recaptch version
     * @var string
     */

	/**
     * Constructor
     * @since 1.0.0
     */
    public function __construct() {
		
		if(defined('NXT_PRO_EXT')){
			$options = get_option( 'nexter_white_label' );
			$this->setting_name = (!empty($options['brand_name'])) ? $options['brand_name'].esc_html__(' Settings', 'nexter') : esc_html__('Nexter Settings', 'nexter');
		}else{
			$this->setting_name = esc_html__('Nexter Settings', 'nexter');
		}
		
    }
	
	/**
     * Initiate hooks
	 * @since 1.0.11
     */
	public function hooks() {
        add_action('admin_menu', array( $this, 'nxt_add_menu_page' ));
	}

	/**
     * Add menu options page
     */
    public function nxt_add_menu_page() {
		
		global $_registered_pages, $submenu;
		
		unset($submenu['themes.php'][20]);
		unset($submenu['themes.php'][15]);
		
		add_theme_page( $this->setting_name, $this->setting_name, 'manage_options', 'nexter_welcome', array( $this, 'nexter_ext_dashboard' )  );
    }

	/**
     * Render Dashboard Root Div
     * @since 4.0.0
     */
	public function nexter_ext_dashboard() {

        $checkSVG = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="20" viewBox="0 0 20 20" fill="none"><path d="M0 10C0 4.47715 4.47715 0 10 0V0C15.5228 0 20 4.47715 20 10V10C20 15.5228 15.5228 20 10 20V20C4.47715 20 0 15.5228 0 10V10Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M16.5207 4.9371C16.9004 5.31678 16.9004 5.93235 16.5207 6.31203L8.49986 14.3329C8.12018 14.7125 7.5046 14.7125 7.12493 14.3329L3.47909 10.687C3.09942 10.3074 3.09942 9.69178 3.47909 9.3121C3.85877 8.93243 4.47435 8.93243 4.85402 9.3121L7.81239 12.2705L15.1458 4.9371C15.5254 4.55742 16.141 4.55742 16.5207 4.9371Z" fill="#1717CC"/></svg>';

        $imgPath = NXT_THEME_URI.'assets/images/nexter-theme-welcome.png';

		echo '<div class="nexter-main-container">
                <h1 class="nexter-main-heading">'.esc_html__('Install Nexter Extension to Unlock Nexter Theme', 'nexter').'</h1>
                <p class="nexter-sub-heading">'.esc_html__('To get the most out of Nexter Theme, we recommend installing Nexter Extension to unlock its full potential and access all features.', 'nexter').'</p>
                <div class="nexter-action-btn">
                    <a class="nexter-install-ext">'.esc_html__('Install & Activate Nexter Extension', 'nexter').'</a>
                    <a class="nexter-view-features">'.esc_html__('View Features', 'nexter').'</a>
                </div>
                <div class="nexter-container-wrap">
                    <div class="nexter-column nxt-col-33">
                    <div class="nexter-features-heading">'.esc_html__('Theme Builder :', 'nexter').'</div>
                    <div class="nexter-features">
                        <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Advanced Header Builder', 'nexter').'</span></div>
                        <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Advanced Footer Builder', 'nexter').'</span></div>
                        <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Breadcrumbs Bar', 'nexter').'</span></div>
                        <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('404 Page', 'nexter').'</span></div>
                        <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Single Pages', 'nexter').'</span></div>
                        <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Archive Pages', 'nexter').'</span></div>
                        <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Display Rules for Theme Builder', 'nexter').'</span></div>
                        <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Action & Filter Hooks', 'nexter').'</span></div>
                    </div>
                </div>
                <div class="nexter-column nxt-col-33">
                    <div class="nexter-features-heading">'.esc_html__('Extra Options :', 'nexter').'</div>
                    <div class="nexter-features">
                        <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Adobe Fonts', 'nexter').'</span></div>
                        <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Disable Admin Settings', 'nexter').'</span></div>
                        <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Duplicate Post', 'nexter').'</span></div>
                        <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Branded WP Admin', 'nexter').'</span></div>
                        <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Custom Upload Fonts', 'nexter').'</span></div>
                        <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Replace Text & URL', 'nexter').'</span></div>
                        <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Regenerate Thumbnails', 'nexter').'</span></div>
                    </div>
                </div>
                <div class="nexter-column nxt-col-33">
                    <div class="nexter-features-heading">'.esc_html__('Code Snippets :', 'nexter').'</div>
                    <div class="nexter-features">
                        <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Add HTML, CSS, JS & PHP Code', 'nexter').'</span></div>
                        <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Smart Code Error Handling', 'nexter').'</span></div>
                        <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Conditional Code Load', 'nexter').'</span></div>
                    </div>
                </div>
            </div>
            <div class="nexter-bottom-part-cnt">
                <div class="nexter-container-wrap">
                    <div class="nexter-column nxt-col-33">
                        <div class="nexter-features-heading">'.esc_html__('Performance :', 'nexter').'</div>
                        <div class="nexter-features">
                            <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Advanced Performance', 'nexter').'</span></div>
                            <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Disable Image Sizes', 'nexter').'</span></div>
                            <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Disable Icons', 'nexter').'</span></div>
                            <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Google Fonts', 'nexter').'</span></div>
                        </div>
                    </div>
                </div>
                <div class="nexter-container-wrap">
                    <div class="nexter-column nxt-col-33">
                        <div class="nexter-features-heading">'.esc_html__('Security :', 'nexter').'</div>
                        <div class="nexter-features">
                            <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Advanced Security', 'nexter').'</span></div>
                            <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('Content Protections', 'nexter').'</span></div>
                            <div class="nxt-feature-inner">'.$checkSVG.' <span>'.esc_html__('2-Factor Authentication', 'nexter').'</span></div>
                        </div>
                    </div>
                </div>
                <img src="'.esc_url($imgPath).'"/>
            </div>
        </div>';
	}
}

// Get it started
$Nexter_Settings_Panel = new Nexter_Settings_Panel();
$Nexter_Settings_Panel->hooks();