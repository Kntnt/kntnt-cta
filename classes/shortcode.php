<?php

namespace Kntnt\CTA;

class Shortcode {

    public function run() {
        add_shortcode( 'cta', [ $this, 'shortcode' ] );
    }

    public function shortcode( $atts ) {
        $output = '';
        if ( $cta_group = $this->cta_group() ) {
            if ( wp_style_is( 'kntnt-cta-css', 'registered' ) ) {
                wp_enqueue_style( 'kntnt-cta-css' );
            }
            wp_enqueue_script( 'kntnt-cta-js' );
            $output = "<div class=\"kntnt-cta\" data-cta-group=\"{$cta_group}\"></div>";
        }
        return $output;
    }

    private function cta_group() {
        $pid = get_the_ID();
        $groups = wp_get_object_terms( $pid, 'cta-group', [ 'fields' => 'slugs' ] );
        $group = $this->randomly_pick( $groups );
        Plugin::log( "Randomly selected CTA Group %s for post %s.", $group, $pid );
        return $group;
    }

    // Is supposedly faster than PHP's array_rand().
    static public function randomly_pick( $array ) {
        return $array[ mt_rand( 0, count( $array ) - 1 ) ];
    }

}
