<?php

namespace Kntnt\CTA;

class Ajax {

    public function run() {

        global $wp_query;

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

}
