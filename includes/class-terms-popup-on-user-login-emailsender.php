<?php

use WpLHLAdminUi\Mailer\EmailTemplateHandler;

class TPUL_EmailSender {

    private $emailOptions;
    private $options;
    // @TODO make sure debug is false in production
    private $debug = false;
    private $is_html_email = false;

    public function __construct() {
        $this->emailOptions = new TPUL_Email_Options();
        $this->options = $this->emailOptions->get_options();
        if (!empty($this->options['email_allow_html'])) {
            $this->is_html_email = true;
        }
    }

    /**
     * Notify admin when an anonymous user accepts the terms
     * in Woo Mode
     */
    public function notify_admins_of_anon_acceptance($tpul_visitor_id, $site_name,  $currentURL, $ip = '', $subject = '') {

        $tpl_path = WP_PLUGIN_DIR . '/terms-popup-on-user-login/email-templates/default.php';

        $user_id = $tpul_visitor_id;
        $current_user = get_userdata($user_id);

        if (empty($ip)) {
            $ip = "IP tracking not turned on";
        }

        $replace_tokens = $this->__get_replace_tokens($user_id, $ip);

        $current_time = date('Y-m-d H:i:s');

        /**
         * Send Copy email to Admins
         */
        if (!empty($this->options['email_send_to_admins'])) {

            if (empty($subject)) {
                $subject = __("An Anonymous Visitor Just Accepted Terms on - {$site_name}",  "terms-popup-on-user-login");
            }

            $current_user_name = __("Anonymous",  "terms-popup-on-user-login");
            $current_user = wp_get_current_user();
            if (!empty($current_user)) {
                $current_user_name = wp_get_current_user()->user_login;
            }

            $body = "\n" . __(" ",  "terms-popup-on-user-login")
                . "\n" . "================================================="
                . "\n" . "Unique Visitor ID: {$tpul_visitor_id}"
                . "\n" . "Site name: {$site_name}"
                . "\n" . "URL: {$currentURL}"
                . "\n" . "IP: {$ip}"
                . "\n" . "Time: {$current_time}"
                . "\n" . "User: {$current_user_name}"
                . "\n" . "================================================="
                . "\n";

            $to = (!empty($this->options['email_admin_addr'])) ? $this->options['email_admin_addr'] : get_bloginfo('admin_email');
            $emailer = new EmailTemplateHandler(
                $to,
                $subject,
                $body,
                $replace_tokens,
                false,
                '',
                $this->debug
            );

            $emailer->send($tpl_path);
        }
    }
    public function notify_accept_user($user_id) {

        $user_data = get_userdata($user_id);
        $user_email = $user_data->user_email;
        $to = $user_email;
        $subject = (!empty($this->options['email_subject'])) ? $this->options['email_subject'] : "You've Accepted Our Terms and Conditions";
        $body = (!empty($this->options['email_text_content'])) ? $this->options['email_text_content'] : $this->emailOptions->default_options()['email_text_content'];
        $current_user = wp_get_current_user();

        $replace_tokens = $this->__get_replace_tokens($current_user->ID);

        $tpl_path = WP_PLUGIN_DIR . '/terms-popup-on-user-login/email-templates/default.php';


        $emailer = new EmailTemplateHandler(
            $to,
            $subject,
            $body,
            $replace_tokens,
            $this->is_html_email,
            '',
            $this->debug
        );

        $emailer->send($tpl_path);
    }


    public function notify_admin($user_id) {

        $user_data = get_userdata($user_id);
        $user_email = $user_data->user_email;
        $to = $user_email;
        $subject = (!empty($this->options['email_subject'])) ? $this->options['email_subject'] : "You've Accepted Our Terms and Conditions";
        $body = (!empty($this->options['email_text_content'])) ? $this->options['email_text_content'] : $this->emailOptions->default_options()['email_text_content'];

        $current_user = wp_get_current_user();
        $username = $current_user->user_login;
        $replace_tokens = $this->__get_replace_tokens($current_user->ID);

        $tpl_path = WP_PLUGIN_DIR . '/terms-popup-on-user-login/email-templates/default.php';

        /**
         * Send Copy email to Admins
         */
        $to = (!empty($this->options['email_admin_addr'])) ? $this->options['email_admin_addr'] : get_bloginfo('admin_email');

        $subject = __("User was notified of Terms - ",  "terms-popup-on-user-login")
            . $username;

        $emailer = new EmailTemplateHandler(
            $to,
            $subject,
            $body,
            $replace_tokens,
            $this->is_html_email,
            '',
            $this->debug
        );

        $emailer->send($tpl_path);
    }

    public function notify_admin_separate($user_id) {

        $user_data = get_userdata($user_id);
        $user_email = $user_data->user_email;
        $to = $user_email;

        $body = (!empty($this->options['email_text_content'])) ? $this->options['email_text_content'] : $this->emailOptions->default_options()['email_text_content'];
        $current_user = wp_get_current_user();
        $username = $current_user->user_login;
        $subject = __("User accepted Terms - ",  "terms-popup-on-user-login") . $username;
        $replace_tokens = $this->__get_replace_tokens($current_user->ID);

        $tpl_path = WP_PLUGIN_DIR . '/terms-popup-on-user-login/email-templates/default.php';


        /**
         * Send Copy email to Admins
         */
        $to = (!empty($this->options['email_admin_addr'])) ? $this->options['email_admin_addr'] : get_bloginfo('admin_email');

        $body = "\n" . __("User Info: ",  "terms-popup-on-user-login")
            . "\n" . "================================================="
            . "\n" . "user ID: " . $user_id
            . "\n" . "username: " . $username
            . "\n" . "user email: " . $user_email
            . "\n" . "date: " . date('Y-m-d H:i:s')
            . "\n" . "================================================="
            . "\n";

        $emailer = new EmailTemplateHandler(
            $to,
            $subject,
            $body,
            $replace_tokens,
            false,
            '',
            $this->debug
        );

        $emailer->send($tpl_path);
    }


    public function send_test_email() {
        $to = (!empty($this->options['email_admin_addr'])) ? $this->options['email_admin_addr'] : get_bloginfo('admin_email');
        $subject = (!empty($this->options['email_subject'])) ? $this->options['email_subject'] : "You've Accepted Our Terms and Conditions";
        $body = (!empty($this->options['email_text_content'])) ? $this->options['email_text_content'] : $this->emailOptions->default_options()['email_text_content'];

        $current_user = wp_get_current_user();
        $first_name = $current_user->first_name;
        $username = $current_user->user_login;

        $replace_tokens = $this->__get_replace_tokens($current_user->ID);

        $tpl_path = WP_PLUGIN_DIR . '/terms-popup-on-user-login/email-templates/default.php';

        $emailer = new EmailTemplateHandler(
            $to,
            $subject,
            $body,
            $replace_tokens,
            $this->is_html_email,
            $tpl_path,
            $this->debug
        );

        $emailer->set_debug(true);

        $emailer->send($tpl_path);
    }

    public function __get_replace_tokens($user_id, $ip = '') {

        $current_user = get_userdata($user_id);

        if (!empty($current_user)) {
            $user_email = $current_user->user_email;
            $first_name = $current_user->first_name;
            $last_name = $current_user->last_name;
            $username = $current_user->user_login;
        } else {
            $user_email = '';
            $first_name = '';
            $last_name = '';
            $username = '';
        }

        if (empty($ip)) {
            // if ip was not provided we try to get it from the user state
            // since at acceptance that could have been recorded
            $user_state = new TPUL_User_State($user_id);
            $ip = $user_state->get_clientIP_as_Text();
        }


        $date_format = get_option('date_format');
        $date = date($date_format);

        $replace_tokens = [
            '[user-name]' => $username,
            '[user-email]' => (!empty($user_email)) ? $user_email : '',
            '[user-first-name]' => (!empty($first_name)) ? $first_name : '',
            '[user-last-name]' => (!empty($last_name)) ? $last_name : '',
            '[user-ip]' => $ip,
            '[website-name]' => get_bloginfo('name'),
            '[website-url]' => get_bloginfo('url'),
            '[accepted-date]' => $date,

        ];

        return $replace_tokens;
    }
}
