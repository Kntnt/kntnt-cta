<?php

namespace Kntnt\CTA;

class Shortcode {

    public function run() {
        add_shortcode( 'cta', [ $this, 'shortcode' ] );
    }

    public function shortcode( $atts ) {
        $output = '';
        if ( $cta_group = $this->cta_group() ) {
            CSS::load();
            JS::load();
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
