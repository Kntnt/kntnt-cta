<?php

namespace Kntnt\CTA;

class Ajax {

    public function run() {

        global $wp_query;

        // This filter is used within WP_Query::get_posts() immediately after
        // posts are fetched and internally processed. If a fetched post has no
        // content, the default content on the settings page is used.
        // WP_Query::get_posts() is called by $wp_query->the_post() below.
        add_filter( 'the_posts', [ $this, 'filter_the_post' ], 0, 2 );

        $wp_query = new \WP_Query( [
            'post_type' => 'cta',
            'post_status' => 'publish',
            'tax_query' => [
                'taxonomy' => 'cta-group',
                'field' => 'slug',
                'terms' => $_POST['cta_group'], // WP_Query does sanitization
            ],
            'posts_per_page' => 1,
            'orderby' => 'rand',
        ] );

        if ( $wp_query->have_posts() ) {
            $wp_query->the_post();
            the_content();
            Plugin::log( "Randomly selected %s in the group %s", $wp_query->post->ID, $_POST['cta_group'] );
        }
        else {
            Plugin::log( "No CTAs in the group %s", $_POST['cta_group'] );
        }

        die;

    }

    public function filter_the_post( $posts, $query ) {
        foreach ( $posts as &$post ) {
            if ( ! $post->post_content ) {
                $post->post_content = Plugin::option( 'content' );
                Plugin::log( "Post content empty; fallback to default value." );
            }
        }
        return $posts;
    }

}
