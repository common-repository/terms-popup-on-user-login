<?php
class TPUL_LoginPage_Utility {


    public function __construct($pageUrls) {
    }

    public static function __isLoginPage() {
        // Check if the global $pagenow variable is set (WordPress >= 2.6)
        if (isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] === 'wp-login.php') {
            // error_log('Global $pagenow variable is set to wp-login.php');
            return true;
        }

        // Check if the request URI contains '/wp-login.php'
        if (strpos($_SERVER['REQUEST_URI'], '/wp-login.php') !== false) {
            // error_log('Request URI contains /wp-login.php');
            return true;
        }

        // Check if the current page's URL contains 'wp-login.php'
        if (strpos($_SERVER['PHP_SELF'], 'wp-login.php') !== false) {
            // error_log('Current page URL contains wp-login.php');
            return true;
        }

        return false;
    }

    public static function __isCustomLoginPage() {

        $options = get_option('tpul_settings_term_modal_options');
        $pageUrls = $options['terms_modal_loginpage_paths'];
        $pageUrls = explode("\n", $pageUrls);


        if (empty($pageUrls)) {
            return false;
        }
        $currentPageUrl = $_SERVER['REQUEST_URI'];

        foreach ($pageUrls as $url) {
            if (strpos($currentPageUrl, $url) !== false) {
                return true;
            }
        }
        return false;
    }

    public static function is_login_page() {
        $currentPageUrl = $_SERVER['REQUEST_URI'];

        if (self::__isLoginPage()) {
            return true;
        }
        if (self::__isCustomLoginPage()) {
            return true;
        }
    }
}
