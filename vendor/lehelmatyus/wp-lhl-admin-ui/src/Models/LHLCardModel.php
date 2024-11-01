<?php


/**
 * Base Object that (most..) Card Template files understand
 */

namespace WpLHLAdminUi\Models;

class LHLCardModel {

    public $post_id = 0;

    public $image_id;
    public $meta;
    public $title;
    public $description;
    public $link;
    public $tags = [];

    public $count = 0;
    public $parity = '';
    public $template = '';

    public $class = [];
    public $id = '';

    public bool $link_the_image = false;

    public function __construct(
        $post_id = 0,
        array $overrides = [
            'image_id' => '',
            'meta' => '',
            'title' => '',
            'description' => '',
        ],
        LHLLinkModel $link = null,
        bool $link_the_image = false,
        string $template = ''
    ) {

        if (!empty($post_id)) {
            $this->post_id = $post_id;
        }
        if (!empty($overrides['image_id'])) {
            $this->image_id = $overrides['image_id'];
        }
        if (!empty($overrides['meta'])) {
            $this->meta = $overrides['meta'];
        }
        if (!empty($overrides['title'])) {
            $this->title = $overrides['title'];
        }
        if (!empty($overrides['description'])) {
            $this->description = $overrides['description'];
        }

        // Initialize $link to null if not provided
        $this->link = $link;

        if (!empty($link)) {
            $this->link = $link;
        }

        if (!empty($link_the_image)) {
            $this->link_the_image = $link_the_image;
        }

        if (!empty($template)) {
            $this->template = $template;
        }
    }

    /**
     * Getter and setters
     */

    // Getter and setter for image_id property
    public function getPostId() {
        return $this->post_id;
    }

    public function setPostId($post_id) {
        $this->post_id = $post_id;
    }

    // Getter and setter for image_id property
    public function getImageId() {
        $image_id = apply_filters('lhl_card_model_get_image_id', $this->image_id, $this);
        return $image_id;
    }

    public function setImageId($image_id) {
        $this->image_id = $image_id;
    }

    // Getter and setter for meta property
    public function getMeta() {
        $meta = apply_filters('lhl_card_model_get_meta', $this->meta, $this);
        return $meta;
    }

    public function setMeta($meta) {
        $this->meta = $meta;
    }

    // Getter and setter for title property
    public function getTitle() {
        $title = apply_filters('lhl_card_model_get_title', $this->title, $this);
        return $title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    // Getter and setter for description property
    public function getDescription() {
        $description = apply_filters('lhl_card_model_get_description', $this->description, $this);
        return $description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getTags() {
        return $this->tags;
    }

    public function setTags($tags) {
        $this->tags = $tags;
    }


    // Getter and setter for class property
    public function getClass() {
        return $this->class;
    }

    public function setClass($class) {
        $this->class = $class;
    }

    // Getter and setter for id property
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    // Method to add a class to the existing class property
    public function addClass($class) {
        $class = esc_attr($class);
        $this->class[] = $class;
    }

    public function getClassesString() {
        $classes = $this->class;
        $classes = array_map('esc_attr', $classes);
        $classes = implode(' ', $classes);
        return $classes;
    }

    // Getter and setter for link property
    public function getLink() {
        $link = apply_filters('lhl_card_model_get_link', $this->link, $this);
        return $link;
    }

    public function setLink($link) {
        $this->link = $link;
    }

    public function getTemplate() {
        $template = apply_filters('lhl_card_model_get_template', $this->template, $this);
        return $template;
    }

    public function setTemplate($template) {
        $this->template = $template;
    }


    // Getter and setter for count property
    public function getCount() {
        return $this->count;
    }

    public function setCount($count) {
        $this->count = $count;
    }

    // Getter and setter for parity property
    public function getParity() {
        $this->count % 2 == 0 ? 'even' : 'odd';
    }

    public function setLinkTheImage($link_the_image) {
        $this->link_the_image = $link_the_image;
    }


    /**
     * Preprocess
     */

    public function inflateFromPost($post_id = 0) {

        if (!empty($post_id)) {
            $this->post_id = $post_id;
        }

        /**
         * Get The Image_id
         */

        if (empty($this->image_id) && !empty($this->post_id)) {
            $this->image_id = get_post_thumbnail_id($this->post_id);
        }
        /**
         * Get The Meta
         */
        if (empty($this->meta) && !empty($this->post_id)) {
            $this->meta = get_the_date('F d, Y', $this->post_id);
        }

        /**
         * Get the title
         */
        if (empty($this->title) && !empty($this->post_id)) {
            $this->title = get_the_title($this->post_id);
        }

        /**
         * Get The Description
         */
        if (empty($this->description) && !empty($this->post_id)) {

            //get excerpt
            $description = get_the_excerpt($this->post_id);
            // fallback to content
            if (empty($description)) {
                $post = get_post($this->post_id);
                if ($post) {
                    $description = LHLTextUtility::extractTextContent($post->post_content);
                }
            }
            $this->description = $description;
            $this->description = LHLTextUtility::truncate($description, 150);
        }

        /**
         * Get the link
         */
        if (empty($this->link) && !empty($this->post_id)) {
            $this->link = new LHLLinkModel("Read More", get_permalink($this->post_id));
        }
    }
}
