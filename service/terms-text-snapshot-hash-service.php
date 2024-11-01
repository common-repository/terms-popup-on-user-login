<?php
class TPUL_Text_Hash_Utility {

    public static function get_hash_from_text($terms_text) {
        return hash('sha256', $terms_text);
    }

    public static function verify_hash_from_text($terms_text, $hash) {
        return $hash === self::get_hash_from_text($terms_text);
    }
}
