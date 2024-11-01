<?php

class TPUL_Popup_Type {

    private $popup_type = false;
    private $gen_options = null;
    private $woo_options = null;
    private $debug = false;

    private $all_popup_types = [
        'none',
        'terms_and_conditions_modal',
        'terms_and_conditions_modal_include_admin',
        'terms_and_conditions_modal_anypageeveryone',
        'terms_and_conditions_modal_onloginpage',
        'terms_and_conditions_modal_test',
        'terms_and_conditions_modal_woo',
    ];

    public function __construct() {

        // get options
        $this->gen_options = new TPUL_General_Options();
        $modal_gen_options = $this->gen_options->get_options();
        $this->woo_options = new TPUL_Woo_Options();

        // set modal type
        if (!empty($modal_gen_options['modal_to_show'])) {
            $this->popup_type = $modal_gen_options['modal_to_show'];
        }
    }

    public function get_popup_type() {
        return $this->popup_type;
    }

    public function set_popup_type($popup_type) {
        $this->popup_type = $popup_type;
    }

    public function is_modal_on() {

        if (
            ($this->popup_type == 'terms_and_conditions_modal') ||
            ($this->popup_type == 'terms_and_conditions_modal_include_admin') ||
            ($this->popup_type == 'terms_and_conditions_modal_anypageeveryone') ||
            ($this->popup_type == 'terms_and_conditions_modal_onloginpage') ||
            ($this->popup_type == 'terms_and_conditions_modal_woo') ||
            ($this->popup_type == 'terms_and_conditions_modal_test')
        ) {
            return true;
        }
        return false;
    }

    public function is_test_modal() {
        if ($this->popup_type == 'terms_and_conditions_modal_test') {
            return true;
        }
        return false;
    }

    public function is_loginpage_modal() {

        if (
            ($this->popup_type == 'terms_and_conditions_modal_onloginpage')
        ) {
            return true;
        }
        return false;
    }

    public function is_modal_anypage_everyone() {

        if ($this->popup_type == 'terms_and_conditions_modal_anypageeveryone') {
            return true;
        }
        return false;
    }

    public function is_login_modal() {
        if (
            ($this->popup_type == 'terms_and_conditions_modal') ||
            ($this->popup_type == 'terms_and_conditions_modal_include_admin') ||
            ($this->popup_type == 'terms_and_conditions_modal_test')
        ) {
            if ($this->debug) error_log('Popup Type: is_login_modal');
            return true;
        }
        return false;
    }

    public function is_woo_modal() {
        if ($this->popup_type == 'terms_and_conditions_modal_woo') {
            if ($this->debug) error_log('Popup Type: is_woo_modal');

            return true;
        }
        return false;
    }

    public function is_woo_public_modal() {
        $availble_for_anonymous = $this->woo_options->is_user_type_anonymous();

        if ($this->is_woo_modal() && $availble_for_anonymous) {
            return true;
        }
        return false;
    }

    public function should_show_on_wp_admin() {
        if ($this->popup_type == 'terms_and_conditions_modal_include_admin') {
            if ($this->debug) error_log('Popup Type: should_show_on_wp_admin');
            return true;
        }
        return false;
    }

    public function get_all_popup_types() {
        return $this->all_popup_types;
    }
}