<?php
// https://deliciousbrains.com/managing-custom-tables-wordpress/

class TPUL_BASE_DB_API {

    static $primary_key = '_id';

    static function get_primary_key() {
        return self::$primary_key;
    }

    private static function _table() {
        global $wpdb;
        $tablename = strtolower(get_called_class());
        $tablename = str_replace('wpas_model_', 'wpas_', $tablename);
        return $wpdb->prefix . $tablename;
    }

    private static function _fetch_sql($value) {
        global $wpdb;
        $sql = sprintf('SELECT * FROM %s WHERE %s = %%s', self::_table(), static::$primary_key);
        return $wpdb->prepare($sql, $value);
    }

    static function valid_check($data) {
        global $wpdb;

        $sql_where       = '';
        $sql_where_count = count($data);
        $i               = 1;
        foreach ($data as $key => $row) {
            if ($i < $sql_where_count) {
                $sql_where .= "`$key` = '$row' and ";
            } else {
                $sql_where .= "`$key` = '$row'";
            }
            $i++;
        }
        $sql     = 'SELECT * FROM ' . self::_table() . " WHERE $sql_where";
        $results = $wpdb->get_results($sql);
        if (count($results) != 0) {
            return false;
        } else {
            return true;
        }
    }

    static function get($value) {
        global $wpdb;
        return $wpdb->get_row(self::_fetch_sql($value));
    }

    static function insert($data) {
        global $wpdb;
        $wpdb->insert(self::_table(), $data);
    }

    static function update($data, $where) {
        global $wpdb;
        $wpdb->update(self::_table(), $data, $where);
    }

    static function delete($value) {
        global $wpdb;
        $sql = sprintf('DELETE FROM %s WHERE %s = %%s', self::_table(), static::$primary_key);
        return $wpdb->query($wpdb->prepare($sql, $value));
    }

    static function fetch($value) {
        global $wpdb;
        $value = intval($value);
        $sql   = 'SELECT * FROM ' . self::_table() . " WHERE `" . static::$primary_key . "` = '$value' order by `created_at` DESC";
        return $wpdb->get_results($sql);
    }

    static function fetch_all() {
        global $wpdb;
        $sql   = 'SELECT * FROM ' . self::_table() . " order by `created_at` DESC";
        return $wpdb->get_results($sql);
    }

    static function fetch_by_column_value($column, $value) {
        global $wpdb;
        $sql = sprintf('SELECT * FROM %s WHERE %s = %%s', self::_table(), $column);
        return $wpdb->get_results($wpdb->prepare($sql, $value));
    }

    static function count_all() {
        global $wpdb;
        $sql   = 'SELECT COUNT(*) FROM ' . self::_table() . " order by `created_at` DESC";
        $results = $wpdb->get_results($sql);

        $count = (array) $results[0];
        return $count['COUNT(*)'];
    }

    static function purge_older_than($date) {
        global $wpdb;
        $sql   = 'DELETE FROM ' . self::_table() . " WHERE `created_at` < '" . $date . "'";
        return $wpdb->query($wpdb->prepare($sql, $date));
    }

    static function does_table_exist() {
        global $wpdb;
        $table_name = self::_table();
        $query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table_name));

        if (!$wpdb->get_var($query) == $table_name) {
            return true;
        }
        return false;
    }

    static function insert_id() {
        global $wpdb;
        return $wpdb->insert_id;
    }

    static function date_to_time($date) {
        return strtotime($date . ' GMT');
    }

    static function time_to_date($time) {
        return gmdate('Y-m-d H:i:s', $time);
    }

    static function now() {
        return self::time_to_date(time());
    }
}

/**
 * Class name must be the same as the table name
 */
class TPUL_terms_user_state extends TPUL_BASE_DB_API {

    static $primary_key = 'terms_user_state_id';

    static function get_primary_key() {
        return self::$primary_key;
    }
}
