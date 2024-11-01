<?php

class TPUL_Terms_User_State_Model {

    public $timestamp;              // The timestamp of the acceptance.
    public $time;                   // The time the acceptance occurred.

    public $user_id;                // The user's ID.
    public $user_name;              // The user's username.
    public $user_displayname;       // The user's display name, if available.
    public $user_first_name;        // The user's first name, if available.
    public $user_last_name;         // The user's last name, if available.
    public $user_action;            // The action the user performed, e.g., "Accept Terms".
    public $user_action_code;            // The action the user performed, e.g., "Accept Terms".
    public $user_action_method;     // The method by which the user action was performed, e.g., button click, form submission, etc.
    public $user_device_info;       // Details about the device used, including browser type, operating system, and device type (e.g., mobile, desktop).
    public $user_useragent;         // The complete user agent string from the browser, which can give more context about the environment in which the acceptance occurred.
    public $user_ip_address;        // The IP address of the user at the time of acceptance.
    public $user_geolocation;       // The geolocation of the user at the time of acceptance, if available.
    public $user_language_preference;   // The user's preferred language, if available.
    public $user_visitor_id;        // A unique identifier for the user, which can be used to track their activity across sessions.
    public $user_action_log;        // A log of the user's actions leading up to the acceptance, which can provide more context about the user's behavior.

    public $terms_version;          // The version of the terms that were accepted.
    public $terms_acceptance_url_reference; // The URL of the terms that were accepted, which can be used to retrieve the terms text.
    public $terms_text_snapshot_hash;   // A hash of the terms text at the time of acceptance, which can be used to verify the integrity of the terms.

    public $order_id;               // The ID of the order associated with the acceptance, if applicable.
    public $meta;                   // Additional metadata about the acceptance, which can be used to store custom data.

    public function __construct(
        array $userstate
    ) {
        $this->timestamp = time();
        $this->time = date('Y-m-d H:i:s');

        $this->user_id = $userstate['user_id'];
        $this->user_name = get_user_by('id', $userstate['user_id'])->user_login;
        $this->user_displayname = get_user_by('id', $userstate['user_id'])->display_name;
        $this->user_first_name = get_user_meta($userstate['user_id'], 'first_name', true);
        $this->user_last_name = get_user_meta($userstate['user_id'], 'last_name', true);
        $this->user_action = (isset($userstate['user_action'])) ? $userstate['user_action'] : '';
        $this->user_action_code = (isset($userstate['user_action_code'])) ? $userstate['user_action_code'] : '';
        $this->user_action_method = (isset($userstate['user_action_method'])) ? $userstate['user_action_method'] : '';
        $this->user_device_info = (isset($userstate['user_device_info'])) ? $userstate['user_device_info'] : '';
        $this->user_useragent = (isset($userstate['user_useragent'])) ? $userstate['user_useragent'] : '';
        $this->user_visitor_id = (isset($userstate['user_visitor_id'])) ? $userstate['user_visitor_id'] : '';
        $this->user_ip_address = (isset($userstate['user_ip_address'])) ? $userstate['user_ip_address'] : '';
        $this->user_geolocation = (isset($userstate['user_geolocation'])) ? $userstate['user_geolocation'] : '';
        $this->user_language_preference = (isset($userstate['user_language_preference'])) ? $userstate['user_language_preference'] : '';
        $this->user_action_log = (isset($userstate['user_action_log'])) ? $userstate['user_action_log'] : '';

        $this->terms_text_snapshot_hash = (isset($userstate['terms_text_snapshot_hash'])) ? $userstate['terms_text_snapshot_hash'] : '';
        $this->terms_version = (isset($userstate['terms_version'])) ? $userstate['terms_version'] : '';
        $this->terms_acceptance_url_reference = (isset($userstate['terms_acceptance_url_reference'])) ? $userstate['terms_acceptance_url_reference'] : '';
    }

    public function getTimestamp() {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }

    public function getTime() {
        return $this->time;
    }

    public function setTime($time) {
        $this->time = $time;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function getUserName() {
        return $this->user_name;
    }

    public function setUserName($user_name) {
        $this->user_name = $user_name;
    }

    public function getUserDisplayname() {
        return $this->user_displayname;
    }

    public function setUserDisplayname($user_displayname) {
        $this->user_displayname = $user_displayname;
    }

    public function getUserAction() {
        return $this->user_action;
    }

    public function setUserAction($user_action) {
        $this->user_action = $user_action;
    }

    public function getUserActionCode() {
        return $this->user_action_code;
    }

    public function setUserActionCode($user_action_code) {
        $this->user_action_code = $user_action_code;
    }

    public function getUserActionMethod() {
        return $this->user_action_method;
    }

    public function setUserActionMethod($user_action_method) {
        $this->user_action_method = $user_action_method;
    }

    public function getUserDeviceInfo() {
        return $this->user_device_info;
    }

    public function setUserDeviceInfo($user_device_info) {
        $this->user_device_info = $user_device_info;
    }

    public function getUserUseragent() {
        return $this->user_useragent;
    }

    public function setUserUseragent($user_useragent) {
        $this->user_useragent = $user_useragent;
    }

    public function getUserIpAddress() {
        return $this->user_ip_address;
    }

    public function setUserIpAddress($user_ip_address) {
        $this->user_ip_address = $user_ip_address;
    }

    public function getUserGeolocation() {
        return $this->user_geolocation;
    }

    public function setUserGeolocation($user_geolocation) {
        $this->user_geolocation = $user_geolocation;
    }

    public function getUserLanguagePreference() {
        return $this->user_language_preference;
    }

    public function setUserLanguagePreference($user_language_preference) {
        $this->user_language_preference = $user_language_preference;
    }

    public function getUserVisitorId() {
        return $this->user_visitor_id;
    }

    public function setUserVisitorId($user_visitor_id) {
        $this->user_visitor_id = $user_visitor_id;
    }

    public function getTermsVersion() {
        return $this->terms_version;
    }

    public function setTermsVersion($terms_version) {
        $this->terms_version = $terms_version;
    }

    public function getTermsAcceptanceUrlReference() {
        return $this->terms_acceptance_url_reference;
    }

    public function setTermsAcceptanceUrlReference($terms_acceptance_url_reference) {
        $this->terms_acceptance_url_reference = $terms_acceptance_url_reference;
    }

    public function getTermsTextSnapshotHash() {
        return $this->terms_text_snapshot_hash;
    }

    public function setTermsTextSnapshotHash($terms_text_snapshot_hash) {
        $this->terms_text_snapshot_hash = $terms_text_snapshot_hash;
    }

    public function getActionLog() {
        return $this->user_action_log;
    }

    public function setActionLog($user_action_log) {
        $this->user_action_log = $user_action_log;
    }

    public function getOrderId() {
        return $this->order_id;
    }

    public function setOrderId($order_id) {
        $this->order_id = $order_id;
    }

    public function getMeta() {
        return $this->meta;
    }

    public function setMeta($meta) {
        $this->meta = $meta;
    }

    public static function from_array($array) {
        return new self(
            array(
                'timestamp' => $array['timestamp'],
                'time' => $array['time'],
                'user_id' => $array['user_id'],
                'user_name' => $array['user_name'],
                'user_displayname' => $array['user_displayname'],
                'user_first_name' => $array['user_first_name'],
                'user_last_name' => $array['user_last_name'],
                'user_action' => $array['user_action'],
                'user_action_code' => $array['user_action_code'],
                'user_action_method' => $array['user_action_method'],
                'user_device_info' => $array['user_device_info'],
                'user_useragent' => $array['user_useragent'],
                'user_ip_address' => $array['user_ip_address'],
                'user_geolocation' => $array['user_geolocation'],
                'user_language_preference' => $array['user_language_preference'],
                'user_visitor_id' => $array['user_visitor_id'],
                'user_action_log' => $array['user_action_log'],
                'terms_version' => $array['terms_version'],
                'terms_acceptance_url_reference' => $array['terms_acceptance_url_reference'],
                'terms_text_snapshot_hash' => $array['terms_text_snapshot_hash'],
                'order_id' => $array['order_id'],
                'meta' => $array['meta']
            )

        );
    }

    public function to_array() {
        return array(
            'timestamp' => $this->timestamp,
            'time' => $this->time,
            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
            'user_displayname' => $this->user_displayname,
            'user_first_name' => $this->user_first_name,
            'user_last_name' => $this->user_last_name,
            'user_action' => $this->user_action,
            'user_action_code' => $this->user_action_code,
            'user_action_method' => $this->user_action_method,
            'user_device_info' => $this->user_device_info,
            'user_useragent' => $this->user_useragent,
            'user_ip_address' => $this->user_ip_address,
            'user_geolocation' => $this->user_geolocation,
            'user_language_preference' => $this->user_language_preference,
            'user_visitor_id' => $this->user_visitor_id,
            'user_action_log' => $this->user_action_log,
            'terms_version' => $this->terms_version,
            'terms_acceptance_url_reference' => $this->terms_acceptance_url_reference,
            'terms_text_snapshot_hash' => $this->terms_text_snapshot_hash,
            'order_id' => $this->order_id,
            'meta' => $this->meta
        );
    }

    public function __toString() {
        return json_encode($this->to_array());
    }

    public static function from_json($json) {
        return self::from_array(json_decode($json, true));
    }

    public function to_json() {
        return json_encode($this->to_array());
    }
}
