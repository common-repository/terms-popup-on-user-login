<?php

namespace WpLHLAdminUi\Models;

class LHLLinkModel {

    private $url;
    private $text;
    private $target;
    private $rel;
    private $classes;
    private $attributes;

    public function __construct($text, $url, $classes = array(), $attributes = array(), $target = '_self', $rel = 'noopener noreferrer') {
        $this->url = $url;
        $this->text = $text;
        $this->target = $target;
        $this->rel = $rel;
        $this->classes = $classes;
        $this->attributes = $attributes;
    }

    public static function get_from_field($field) {
        $link = new LHLLinkModel('', '');
        $link->inflate_from_field($field);
        return $link;
    }

    public function inflate_from_field($field) {

        if (isset($field['url'])) {
            $this->url = $field['url'];
        }
        // error_log(print_r($field, true));
        if (isset($field['title'])) {
            $this->text = $field['title'];
        }
        if (isset($field['target'])) {
            $this->target = $field['target'];
        }
        if (isset($field['rel'])) {
            $this->rel = $field['rel'];
        }
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getText() {
        return $this->text;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function getTarget() {
        return $this->target;
    }

    public function setTarget($target) {
        $this->target = $target;
    }

    public function getRel() {
        return $this->rel;
    }

    public function setRel($rel) {
        $this->rel = $rel;
    }

    public function getClasses() {
        return $this->classes;
    }

    public function setClasses($classes) {
        $this->classes = $classes;
    }

    public function addClass($class) {
        $this->classes[] = $class;
    }
    public function addClasses($classes) {
        $this->classes = array_merge($this->classes, $classes);
    }

    public function getAttributes() {
        return $this->attributes;
    }

    public function setAttributes($attributes) {
        $this->attributes = $attributes;
    }

    public function addAttribute($attribute) {
        $this->attributes[] = $attribute;
    }
}
