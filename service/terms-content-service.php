<?php
class TPUL_Terms_Content_Service {
    public static function get_terms_content() {

        $modal_options = new TPUL_Modal_Options();
        $terms_options_data = $modal_options->get_options();

        $license_key_handler = new TPUL_LicenseKeyHandler(new TPUL_LicsenseKeyDataProvider());
        $license_is_active = $license_key_handler->is_active();


        $content_from_url = $modal_options->get_content_from_url();
        if (!empty($content_from_url) && $license_is_active) {
            $url = $terms_options_data['terms_modal_content_from_url'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $content = curl_exec($ch);
            curl_close($ch);
            return wpautop(wp_kses($content, self::__get_body_allowed_html()));
        }

        $content_page_id = $modal_options->get_terms_modal_pageid();
        if (!empty($content_page_id) && $license_is_active) {
            $post_content = get_post($content_page_id);
            $content = $post_content->post_content;
            return do_shortcode(wpautop(wp_kses_post($content)));
        }

        $content = $terms_options_data['terms_modal_content'];
        if ($license_is_active) {
            return wpautop(wp_kses($content, self::__get_body_allowed_html()));
        }
        return wpautop(wp_kses_post($content));
    }

    public static function get_terms_content_hash() {
        $content = self::get_terms_content();
        return TPUL_Text_Hash_Utility::get_hash_from_text($content);
    }

    public static function get_terms_content_version() {
    }


    public static function __get_body_allowed_html() {

        $allowed_tags = array(
            'a' => array(
                'class' => array(),
                'href'  => array(),
                'rel'   => array(),
                'title' => array(),
            ),
            'b' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'blockquote' => array(
                'cite'  => array(),
            ),
            'cite' => array(
                'title' => array(),
            ),
            'code' => array(),
            'div' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'dl' => array(),
            'dt' => array(),
            'em' => array(),
            'h1' => array(),
            'h2' => array(),
            'h3' => array(),
            'h4' => array(),
            'h5' => array(),
            'h6' => array(),
            'i' => array(),
            'img' => array(
                'alt'    => array(),
                'class'  => array(),
                'height' => array(),
                'src'    => array(),
                'width'  => array(),
            ),
            'li' => array(
                'class' => array(),
            ),
            'ol' => array(
                'class' => array(),
            ),
            'p' => array(
                'class' => array(),
            ),
            'span' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'strong' => array(),
            'ul' => array(
                'class' => array(),
            ),
        );

        return $allowed_tags;
    }
}
