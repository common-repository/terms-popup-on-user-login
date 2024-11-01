<?php

namespace WpLHLAdminUi\LHLSimpleQuery;

/**
 * Class LHLSimpleQuery 27
 */
class LHLSimpleQuery {

    /**
     * Array to store post IDs.
     *
     * @var array
     */
    private $post_ids;

    /**
     * LHLSimpleQuery constructor.
     *
     * @param string $post_type       The post type to query.
     * @param int    $number_of_posts The number of posts to retrieve.
     * @param array  $acf_filters     ACF field filters in the format ['acf_field_name' => 'value'].
     * @param string $order           The order in which to retrieve the posts ('ASC' for ascending, 'DESC' for descending).
     * @param string $post_status     The post status to query (default is 'publish').
     */
    public function __construct($post_type, $number_of_posts, $acf_filters = array(), $order = 'DESC', $post_status = 'publish') {
        // Default arguments for the WP_Query.
        $default_args = array(
            'post_type'      => $post_type,
            'posts_per_page' => $number_of_posts,
            'orderby'        => 'date',
            'order'          => $order,
            'meta_query'     => array(),
            'post_status'    => $post_status,
        );

        // Add ACF filters to the meta_query array.
        foreach ($acf_filters as $key => $value) {
            $default_args['meta_query'][] = array(
                'key'   => $key,
                'value' => $value,
            );
        }

        // Initialize the WP_Query with the merged arguments.
        $query = new WP_Query($default_args);
        // Array to store post IDs.
        $this->post_ids = array();

        // Loop through the query results.
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $this->post_ids[] = get_the_ID();
            }
            // Reset post data to the main query.
            wp_reset_postdata();
        }
    }

    /**
     * Check if there are posts.
     *
     * @return bool True if there are posts, false otherwise.
     */
    public function have_posts() {
        return !empty($this->post_ids);
    }

    /**
     * Get the array of post IDs.
     *
     * @return array An array containing the post IDs.
     */
    public function getPostIds() {
        return $this->post_ids;
    }
}

// Example usage:
// $post_type = 'member-spotlight';
// $number_of_posts = 3;
// $acf_filters = array(
//     'featured' => true,
// );
// $order = 'ASC';

// // Create an instance of LHLSimpleQuery.
// $lhl_query = new LHLSimpleQuery($post_type, $number_of_posts, $acf_filters, $order);

// // Check if there are posts.
// if ($lhl_query->have_posts()) {
//     // Get the array of post IDs.
//     $featured_posts = $lhl_query->getPostIds();

//     // Now $featured_posts is an array containing the Post IDs of the latest 3 "member-spotlight" posts with an active "featured" field, ordered by the specified order.
//     print_r($featured_posts);
// } else {
//     echo 'No posts found.';
// }
