<?php

class TPUL_Woo_Options {

    private $options = false;
    private $options_name = 'tpul_settings_term_modal_woo_options';

    private $defaults = array(
        'terms_modal_woo_display_on'                =>   'product_pages',
        'terms_modal_woo_display_user_type'           =>    'anonymous_and_logged_in',
        'terms_modal_woo_log_out_user'                =>    false,
        'terms_modal_woo_popup_frequency'          =>    "until_browser_is_closed",
        'terms_modal_woo_display_on_custom'       =>  '',
    );

    // private $possible_values = [

    //     'terms_modal_woo_display_on' => [            
    //         'product_pages' => [
    //             'value' => 'product_pages',
    //             'label' => __( 'Product pages only', 'terms-popup-on-user-login' ),
    //         ],
    //         'product_pages_and_category' => [
    //             'value' => 'product_pages_and_category',
    //             'label' => __( 'Both product and category pages', 'terms-popup-on-user-login' ),
    //         ],
    //     ],

    //     'terms_modal_woo_display_user_type' => [

    //         'anonymous_and_logged_in' => [
    //             'value' => 'anonymous_and_logged_in',
    //             'label' => __( 'Both Anonymous visitors and logged in users', 'terms-popup-on-user-login' ),
    //         ],
    //         'anonymous_only' => [
    //             'value' => 'anonymous_only',
    //             'label' => __( 'Anonymous visitors only', 'terms-popup-on-user-login' ),
    //         ],
    //         'logged_in_only' => [
    //             'value' => 'logged_in_only',
    //             'label' => __( 'Logged in users only', 'terms-popup-on-user-login' ),
    //         ]

    //     ]
    // ];

    public function __construct() {
        $this->options = get_option($this->options_name);
    }

    public function default_options() {
        return $this->defaults;
    }

    public function get_options() {
        if (false ==  $this->options) {
            return $this->default_options();
        }
        return $this->options;
    }

    public function get_user_type() {
        return $this->options['terms_modal_woo_display_user_type'];
    }

    public function get_display_on() {
        return $this->options['terms_modal_woo_display_on'];
    }

    public function should_logout() {
        return $this->options['terms_modal_woo_log_out_user'] == "woo_log_out";
    }

    public function should_save_cookie() {
        return $this->options['terms_modal_woo_popup_frequency'] == "until_browser_is_closed";
    }

    public function is_user_type_anonymous() {
        if (
            $this->get_user_type() == 'anonymous_only' ||
            $this->get_user_type() == 'anonymous_and_logged_in'
        ) {
            return true;
        }
    }
    public function get_custom_woo_paths() {
        if (!empty($this->options['terms_modal_woo_display_on_custom'])) {
            return $this->options['terms_modal_woo_display_on_custom'];
        }
        return '';
    }
    public function get_custom_woo_paths_as_array() {
        $paths = $this->get_custom_woo_paths();
        if (empty($paths)) {
            return [];
        }
        return explode("\n", $paths);
    }

    public function is_current_path_custom_woo_path() {
        $current_path = $_SERVER['REQUEST_URI'];
        $custom_paths = $this->get_custom_woo_paths_as_array();
        if (empty($custom_paths)) {
            return false;
        }
        foreach ($custom_paths as $path) {
            if (strpos($current_path, $path) !== false) {
                return true;
            }
        }
        return false;
    }
}
