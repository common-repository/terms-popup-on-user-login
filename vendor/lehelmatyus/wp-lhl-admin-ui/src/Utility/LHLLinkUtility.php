<?php

namespace WpLHLAdminUi\Utility;

use WpLHLAdminUi\Models\LHLLinkModel;

class LHLLinkUtility {

    private LHLLinkModel $link;

    public function __construct(LHLLinkModel $link) {
        $this->link = $link;
    }
    public function getLik() {
        return $this->link;
    }
    public function setLink($link) {
        $this->link = $link;
    }

    public function inflateFromPost($post_id) {
        $url = get_permalink($post_id);
        $text = get_the_title($post_id);
        $this->link = new LHLLinkModel($url, $text);
    }

    public static function render_link_model(LHLLinkModel $link_model) {
        $link_utility = new LHLLinkUtility($link_model);
        $link_utility->render($link_model);
    }

    public function render($link_model) {
        $this->link = $link_model;
        $this->render_link();
    }
    public function render_link($text = '', $classes = [], $attributes = [], $target = '_self', $rel = 'noopener noreferrer') {

        $text = empty($text) ? $this->link->getText() : $text;
        $default_classes = ['lhl-link'];
        $classes = array_merge($default_classes, $classes);
        $classes = implode(' ', $classes);

        $default_attributes = [
            'href' => esc_url($this->link->getUrl()),
            'target' => esc_attr($target),
            'rel' => esc_attr($rel),
        ];

        $attributes = array_merge($default_attributes, $attributes);

        $attributes_str = '';
        foreach ($attributes as $key => $value) {
            $attributes_str .= $key . '="' . $value . '" ';
        }

        echo '<a class="' . $classes . '" ' . $attributes_str . '>' . $text . '</a>';
    }

    public function render_opening_tag($classes = [], $attributes = [], $target = '_self', $rel = 'noopener noreferrer') {
        $default_classes = ['lhl-link'];
        $classes = array_merge($default_classes, $classes);
        $classes = implode(' ', $classes);

        $default_attributes = [
            'href' => esc_url($this->link->getUrl()),
            'target' => esc_attr($target),
            'rel' => esc_attr($rel),
        ];

        $attributes = array_merge($default_attributes, $attributes);

        $attributes_str = '';
        foreach ($attributes as $key => $value) {
            $attributes_str .= $key . '="' . $value . '" ';
        }

        echo '<a class="' . $classes . '" ' . $attributes_str . '>';
    }

    public function render_closing_tag() {
        echo '</a>';
    }
}
