<?php

class WpLHLAdminUi {

    private $uniqKey = "";

    function __construct($uniqKey = '') {
        if ($uniqKey == "") {
            $uniqKey = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 3);
        }
        $this->uniqKey = $uniqKey;
    }

    /**
     * Enq Admin Styles
     */
    function wp_enqueue_style($handle = "") {
        wp_enqueue_style('wp-lhl-admin-ui-styles-' . esc_attr($this->uniqKey),  plugin_dir_url(dirname(__FILE__)) . '/css/wp-lhl-admin-ui.css');
    }
}
