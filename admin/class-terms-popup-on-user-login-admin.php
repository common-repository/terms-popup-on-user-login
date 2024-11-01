<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.lehelmatyus.com
 * @since      1.0.0
 *
 * @package    terms_popup_on_user_login
 * @subpackage terms_popup_on_user_login/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    terms_popup_on_user_login
 * @subpackage terms_popup_on_user_login/admin
 * @author     Lehel Matyus <contact@lehelmatyus.com>
 */
class Terms_Popup_On_User_Login_Admin {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->cache = $this->version;

		$this->popup_type = new TPUL_Popup_Type();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in terms_popup_on_user_login_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The terms_popup_on_user_login_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/terms-popup-on-user-login-admin.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name . "lhl-admin", plugin_dir_url(__FILE__) . '../vendor/lehelmatyus/wp-lhl-admin-ui/css/wp-lhl-admin-ui.css', array(), '1.0.8', 'all');


		$popup_type = new TPUL_Popup_Type();
		$should_show_on_wp_admin = $popup_type->should_show_on_wp_admin();
		if (is_user_logged_in() && $should_show_on_wp_admin) {
			$visibility_manager = new TPUL_Moddal_Visibility_Manager();
			if ($visibility_manager->should_modal_render()) {
				wp_enqueue_style($this->plugin_name . "-micromodal", plugin_dir_url(__DIR__) . 'public/library/micromodal/micromodal.css', array(), $this->cache, 'all');
				wp_enqueue_style($this->plugin_name . "-public", plugin_dir_url(__DIR__) . 'public/css/terms-popup-on-user-login-public.css', array(), $this->cache, 'all');
			}
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook) {

		$gen_options = new TPUL_Modal_Options();

		/*
		* User Profile
		*/
		if ($hook == 'profile.php' || $hook == 'user-edit.php') {
			wp_enqueue_script($this->plugin_name . "-profile", plugin_dir_url(__FILE__) . 'js/terms-popup-on-user-login-admin-profile.js', array('jquery'), $this->version, false);
			/**
			 * Pass an OBJ to our Script
			 */
			wp_localize_script($this->plugin_name . "-profile", 'tpulApiSettings', array(
				'root' => esc_url_raw(rest_url()),
				'tpul_nonce' => wp_create_nonce('wp_rest'),
				'tpul_geolocation' => ($gen_options->get_track_location()) ? 1 : '',
				'popup_type' => $this->popup_type->get_popup_type(),
				'popup_is_on' => $this->popup_type->is_modal_on(),
				'popup_is_test' => $this->popup_type->is_test_modal(),
				'popup_is_loginpage' => $this->popup_type->is_loginpage_modal(),
			));
		}

		/**
		 * Settings Page Chart
		 */
		if ($hook == 'settings_page_terms_popup_on_user_login_options') {

			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/terms-popup-on-user-login-admin.js', array('jquery', 'wp-color-picker'), $this->version, false);
			wp_enqueue_script($this->plugin_name . "-charts", plugin_dir_url(__FILE__) . 'js/chart.js', array(), $this->version, false);

			/**
			 * Pass an OBJ to our Script
			 */
			wp_localize_script($this->plugin_name, 'tpulApiSettings', array(
				'root' => esc_url_raw(rest_url()),
				'tpul_nonce' => wp_create_nonce('wp_rest'),
				'tpul_geolocation' => ($gen_options->get_track_location()) ? 1 : '',
				'popup_type' => $this->popup_type->get_popup_type(),
				'popup_is_on' => $this->popup_type->is_modal_on(),
				'popup_is_test' => $this->popup_type->is_test_modal(),
				'popup_is_loginpage' => $this->popup_type->is_loginpage_modal(),
			));
		}

		/**
		 * Catch and register tokens
		 */

		if (is_user_logged_in()) {

			wp_register_script($this->plugin_name . '-cookie', plugin_dir_url(__DIR__) . 'public/library/cookie/js.cookie.min.js', array('jquery'), $this->version, true);
			wp_enqueue_script($this->plugin_name . '-cookie');

			/**
			 * Framework
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
			 * Token
			 */
			wp_register_script(
				$this->plugin_name . "-token",
				plugin_dir_url(__DIR__) . 'public/js/terms-popup-on-user-login-public-add-token.js',
				array('jquery', 'wp-api-request', $this->plugin_name . '-cookie', $this->plugin_name . '-framework'),
				$this->version,
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



		/**
		 * If option was set to show the modal on the back end
		 */


		$popup_type = new TPUL_Popup_Type();
		$should_show_on_wp_admin = $popup_type->should_show_on_wp_admin();
		if (is_user_logged_in() && $should_show_on_wp_admin) {

			/**
			 * If this user has not accepted yet or the modal is set to show
			 */
			$modal_visibility_manager = new TPUL_Moddal_Visibility_Manager();
			$should_modal_show = $modal_visibility_manager->should_modal_render();
			if ($should_modal_show) {

				$last_reset_ran = 0;
				$reset_info = get_option('tpul_settings_term_modal_reset_info');
				if (!empty($reset_info)) {
					$last_reset_ran = $reset_info['last_ran'];
				}


				wp_register_script($this->plugin_name . '-micromodal-poly', plugin_dir_url(__DIR__) . 'public/library/micromodal/micromodal-polyfill.js', array('jquery'), $this->cache, true);
				wp_register_script($this->plugin_name . '-micromodal', plugin_dir_url(__DIR__) . 'public/library/micromodal/micromodal-0.4.0.min.js', array('jquery', $this->plugin_name . '-micromodal-poly'), $this->cache, true);
				wp_enqueue_script($this->plugin_name . "-micromodal");

				wp_register_script($this->plugin_name . '-cookie', plugin_dir_url(__DIR__) . 'public/library/cookie/js.cookie.min.js', array('jquery', $this->plugin_name . '-micromodal'), $this->cache, true);
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
					$this->plugin_name . "-public",
					plugin_dir_url(__DIR__) . 'public/js/terms-popup-on-user-login-public.js',
					array('jquery', $this->plugin_name . '-micromodal', $this->plugin_name . '-cookie', $this->plugin_name . '-framework', 'wp-api-request'),
					$this->cache,
					true
				);
				wp_enqueue_script($this->plugin_name . "-public");

				/**
				 * Pass an OBJ to our Script
				 */
				wp_localize_script($this->plugin_name . "-public", 'tpulApiSettings', array(
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
		}
	}
}
