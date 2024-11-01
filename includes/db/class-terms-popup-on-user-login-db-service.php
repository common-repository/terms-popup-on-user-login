<?php

class TPUL_DB_Service {
    public static function check_table_exists($table_name) {
        global $wpdb;
        $table_name = $wpdb->prefix . $table_name;
        $sql        = "SHOW TABLES LIKE '$table_name'";
        $results    = $wpdb->get_results($sql);
        // error_log('check_table_exists: ' . print_r($results, true));

        return count($results) > 0;
    }

    public static function check_table_version($table_name) {
        global $wpdb;
        $prefix = $wpdb->base_prefix;
        $version         = (int) get_site_option($prefix . $table_name . '_version');
    }

    public static function Create_TPUL_Terms_User_State_Table() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $version         = (int) get_site_option('tpul_terms_user_state_version');
        $success = false;
        $primary_key = TPUL_terms_user_state::get_primary_key();

        if ($version < 1) {
            $sql = "CREATE TABLE `{$wpdb->base_prefix}tpul_terms_user_state` (
                `{$primary_key}` int(11) NOT NULL AUTO_INCREMENT,
                'timestamp' timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                `user_id` int(11) NOT NULL,
                `user_name` varchar(255) NOT NULL,
                `user_displayname varchar(255),
                `user_first_name varchar(255),
                `user_last_name varchar(255),
                `user_action varchar(255),
                `user_action_method varchar(255),
                `user_action_log` text NOT NULL,
                `user_device_info varchar(255),
                `user_useragent` text NOT NULL,
                `user_ip_address` text,
                `user_geolocation` text,
                `user_language_preference` varchar(255),
                `user_visitor_id varchar(255),
                `terms_version varchar(255),
                `terms_acceptance_url_reference` text NOT NULL,
                `terms_text_snapshot_hash` text NOT NULL,
                `order_id` varchar(255),
                `meta` text NOT NULL,
                PRIMARY KEY  ($primary_key)
            ) $charset_collate;";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            $success = empty($wpdb->last_error);
            if ($success) {
                update_site_option('tpul_terms_user_state_version', 1);
            }
        }
        error_log('Create_tpul_terms_user_state_Table Success: ' . print_r($success, true));
        return $success;
    }
}
