<?php

namespace WpLHLAdminUi\Models;

class LHLTagModel {

    private $id;
    private $text;
    private $url;
    private $link;
    private $classes;
    private $attributes;

    public function __construct($text, $link = '', $url = '', $classes = array(), $id = '', $attributes = array()) {
        $this->text = $text;
        $this->url = $url;
        if (!empty($link)) {
            $this->link = $link;
        }
        $this->id = $id;
        $this->classes = $classes;
    }

    public function inflate_from_vocabulary_term($term) {
        $this->id = $term->term_id;
        $this->text = $term->name;
        $this->link = new LHLLinkModel($term->name, get_term_link($term));
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getText() {
        return $this->text;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function getClasses() {
        return $this->classes;
    }

    public function getClassesString() {
        return implode(' ', $this->classes);
    }

    public function setClasses($classes) {
        $this->classes = $classes;
    }

    public function addClass($class) {
        $this->classes[] = $class;
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
