<?php

namespace Kntnt\CTA;

class REST {

    public function run() {
        register_rest_route( 'kntnt-cta/v1', '/cta/(?P<cta_group>[a-zA-Z_-]+)', [
            'methods' => 'GET',
            'callback' => [ $this, 'get_cta' ],
            'args' => [
                'cta_group' => [
                    'required' => true,
                ],
            ],
        ] );
    }

    public function get_cta( $request ) {

        global $wp_query;

        $wp_query = new \WP_Query( [
            'post_type' => 'cta',
            'post_status' => 'publish',
            'tax_query' => [
                'taxonomy' => 'cta-group',
                'field' => 'slug',
                'terms' => $request['cta_group'],
            ],
            'posts_per_page' => 1,
            'orderby' => 'rand',
        ] );

        $content = '';

        if ( $wp_query->have_posts() ) {
            $wp_query->the_post();
            ob_start();
            the_content();
            $content = ob_get_clean();
            Plugin::log( "Randomly selected %s in the group %s", $wp_query->post->ID, $request['cta_group'] );
        }
        else {
            Plugin::log( "No CTAs in the group %s", $_POST['cta_group'] );
        }

        return new \WP_REST_Response( [ 'content' => $content ], 200 );

    }

}
