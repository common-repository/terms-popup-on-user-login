<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.lehelmatyus.com
 * @since      1.0.0
 *
 * @package    terms_popup_on_user_login
 * @subpackage terms_popup_on_user_login/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    terms_popup_on_user_login
 * @subpackage terms_popup_on_user_login/public
 * @author     Lehel Matyus <contact@lehelmatyus.com>
 */
class Terms_Popup_On_User_Login_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	private $cache;
	private $popup_type;
	private $woo_public_modal;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->cache = $this->version;
		// $this->cache = strval(round(time()) % 1000);

		$this->popup_type = new TPUL_Popup_Type();
		$this->woo_public_modal = $this->popup_type->is_woo_public_modal();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		// Front-End Library
		$visibility_manager = new TPUL_Moddal_Visibility_Manager();
		if ($visibility_manager->should_modal_render()) {
			wp_enqueue_style($this->plugin_name . "-micromodal", plugin_dir_url(__FILE__) . 'library/micromodal/micromodal.css', array(), $this->cache, 'all');
			wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/terms-popup-on-user-login-public.css', array(), $this->cache, 'all');
		}
	}

	/**
	 * Plugin has an option to register styles in the footer instead of the header
	 * this is needed when other plugins mess up the header too much	
	 */
	public function enqueue_styles_in_footer() {

		$visibility_manager = new TPUL_Moddal_Visibility_Manager();
		if ($visibility_manager->should_modal_render()) {
			$options = get_option('tpul_settings_term_modal_options');
			if (!empty($options['terms_modal_asset_placement']) && $options['terms_modal_asset_placement'] == "styles_in_footer") {
				echo '<link rel="stylesheet" id="terms-popup-on-user-login-micromodal-css-footer" href="/wp-content/plugins/terms-popup-on-user-login/public/library/micromodal/micromodal.css?ver=' . $this->version . '" type="text/css" media="all">';
				echo '<link rel="stylesheet" id="terms-popup-on-user-login-css-footer" href="/wp-content/plugins/terms-popup-on-user-login/public/css/terms-popup-on-user-login-public.css?ver=' . $this->version . '" type="text/css" media="all">';
			}
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$gen_options = new TPUL_Modal_Options();

		// if user is logged in or is public modal
		$visibility_manager = new TPUL_Moddal_Visibility_Manager();
		$should_show_modal = $visibility_manager->should_modal_render();
		if ($should_show_modal) {

			$last_reset_ran = 0;
			$reset_info = get_option('tpul_settings_term_modal_reset_info');
			if (!empty($reset_info)) {
				$last_reset_ran = $reset_info['last_ran'];
			}

			wp_register_script($this->plugin_name . '-micromodal-poly', plugin_dir_url(__FILE__) . 'library/micromodal/micromodal-polyfill.js', array('jquery'), $this->cache, true);
			wp_register_script($this->plugin_name . '-micromodal', plugin_dir_url(__FILE__) . 'library/micromodal/micromodal-0.4.0.min.js', array('jquery', $this->plugin_name . '-micromodal-poly'), $this->cache, true);
			wp_enqueue_script($this->plugin_name . "-micromodal");

			wp_register_script($this->plugin_name . '-cookie', plugin_dir_url(__FILE__) . 'library/cookie/js.cookie.min.js', array('jquery', $this->plugin_name . '-micromodal'), $this->cache, true);
			wp_enqueue_script($this->plugin_name . '-cookie');

			/**
			 * Register the framework
			 */
			wp_register_script(
				$this->plugin_name . "-framework",
				plugin_dir_url(__DIR__) . 'public/js/terms-popup-on-user-login-framework.js',
				array('jquery', $this->plugin_name . '-cookie', 'wp-api-request'),
				$this->cache,
				true
			);
			wp_enqueue_script($this->plugin_name . "-framework");

			/**
			 * Register the popup public script
			 */
			wp_register_script(
				$this->plugin_name,
				plugin_dir_url(__FILE__) . 'js/terms-popup-on-user-login-public.js',
				array('jquery', $this->plugin_name . '-micromodal', $this->plugin_name . '-cookie', $this->plugin_name . '-framework', 'wp-api-request'),
				$this->cache,
				true
			);
			wp_enqueue_script($this->plugin_name);

			/**
			 * Pass an OBJ to our Script
			 */
			wp_localize_script($this->plugin_name, 'tpulApiSettings', array(
				'root' => esc_url_raw(rest_url()),
				'tpul_nonce' => wp_create_nonce('wp_rest'),
				'tpul_geolocation' => ($gen_options->get_track_location()) ? 1 : '',
				'tpul_last_reset_ran' => $last_reset_ran,
				'popup_type' => $this->popup_type->get_popup_type(),
				'popup_is_on' => $this->popup_type->is_modal_on(),
				'popup_is_test' => $this->popup_type->is_test_modal(),
				'popup_is_loginpage' => $this->popup_type->is_loginpage_modal(),
			));
		}

		/**
		 * Check both for is user logged in and should modal show
		 * this is needed 
		 * both woo when user may or not may be logged in
		 * and when the popup is no longer needed but we need to register the acceptance token
		 */

		if (is_user_logged_in()) {
			wp_register_script($this->plugin_name . '-cookie', plugin_dir_url(__FILE__) . 'library/cookie/js.cookie.min.js', array('jquery'), $this->cache, true);
			wp_enqueue_script($this->plugin_name . '-cookie');

			/**
			 * Register the framework
			 */
			wp_register_script(
				$this->plugin_name . "-framework",
				plugin_dir_url(__DIR__) . 'public/js/terms-popup-on-user-login-framework.js',
				array('jquery', $this->plugin_name . '-cookie', 'wp-api-request'),
				$this->cache,
				true
			);
			wp_enqueue_script($this->plugin_name . "-framework");

			/**
			 * Register the token script
			 */
			wp_register_script(
				$this->plugin_name . "-token",
				plugin_dir_url(__FILE__) . 'js/terms-popup-on-user-login-public-add-token.js',
				array('jquery', 'wp-api-request', $this->plugin_name . '-cookie', $this->plugin_name . '-framework'),
				$this->cache,
				true
			);
			wp_enqueue_script($this->plugin_name . "-token");

			/**
			 * Pass an OBJ to our Script
			 */
			wp_localize_script($this->plugin_name . "-token", 'tpulApiSettings', array(
				'root' => esc_url_raw(rest_url()),
				'tpul_nonce' => wp_create_nonce('wp_rest'),
				'tpul_geolocation' => ($gen_options->get_track_location()) ? 1 : '',
				'popup_type' => $this->popup_type->get_popup_type(),
				'popup_is_on' => $this->popup_type->is_modal_on(),
				'popup_is_test' => $this->popup_type->is_test_modal(),
				'popup_is_loginpage' => $this->popup_type->is_loginpage_modal(),

			));
		}
	}
}
