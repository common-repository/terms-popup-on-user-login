<?php

namespace WpLHLAdminUi\LHLImage;

class LHLImage {

    private $img_obj_id;

    /**
     * Constructor for ImageLoader class.
     *
     * @param string $img_obj_id The image object ID.
     */
    public function __construct($img_obj_id) {
        $this->img_obj_id = empty($img_obj_id) ? '' : $img_obj_id;
    }

    /**
     * Output a simple image tag using wp_get_attachment_image.
     *
     * @param string $size The image size.
     */
    public function img_tag_simple($size = 'full') {
        if (!$this->__check_id()) return;
        echo wp_get_attachment_image($this->img_obj_id, $size);
    }

    /**
     * Render an image tag with additional attributes.
     *
     * @param string $size                  The image size.
     * @param array  $additional_classes    Additional classes for the image tag.
     * @param array  $additional_attributes Additional attributes for the image tag.
     * @param string $alt                   Alt text for the image.
     * @param int    $width                 Width of the image.
     * @param int    $height                Height of the image.
     */
    public function render_img_tag($size = 'full', $additional_classes = [], $additional_attributes = [], $alt = '', $width = null, $height = null,) {
        if (!$this->__check_id()) return;

        $default_class = 'lhl-img';
        $classes = implode(' ', array_merge([$default_class], $additional_classes));

        $attributes = [
            'class' => esc_attr($classes),
        ];

        if ($alt !== '') {
            $attributes['alt'] = esc_attr($alt);
        }

        if ($width !== null) {
            $attributes['width'] = $width;
        }

        if ($height !== null) {
            $attributes['height'] = $height;
        }

        // Merge additional attributes
        $attributes = array_merge($attributes, $additional_attributes);

        echo wp_get_attachment_image($this->img_obj_id, $size, false, $attributes);
    }

    /**
     * Output the URL of the image.
     *
     * @param string $size The image size.
     */
    public function url($size = 'full') {
        if (!$this->__check_id()) return;
        echo wp_get_attachment_image_url($this->img_obj_id, $size);
    }

    /**
     * Output the image source URL.
     *
     * @param string $size The image size.
     */
    public function src($size = 'full') {
        if (!$this->__check_id()) return;
        $image_data = wp_get_attachment_image_src($this->img_obj_id, $size);
        error_log(print_r($image_data, true));
        if ($image_data) {
            echo $image_data[0]; // Output the URL
        }
    }

    /**
     * Output the image source set.
     *
     * @param string $size The image size.
     */
    public function srcset($size = 'full') {
        if (!$this->__check_id()) return;

        $image_data = wp_get_attachment_image_srcset($this->img_obj_id, $size);
        if ($image_data) {
            echo $image_data; // Output the srcset
        }
    }

    /**
     * Check if the image object ID is valid.
     *
     * @return bool Returns true if the ID is valid, false otherwise.
     */
    private function __check_id() {
        if (empty($this->img_obj_id)) {
            echo "invalid \$img_obj_id";
            return false;
        }
        return true;
    }
}
