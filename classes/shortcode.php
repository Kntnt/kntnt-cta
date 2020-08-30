<?php

namespace Kntnt\CTA;

class Shortcode {

    private static $defaults = [
        'groups' => null,
    ];

    public function run() {
        add_shortcode( 'cta', [ $this, 'shortcode' ] );
    }

    public function shortcode( $atts ) {

        $output = '';

        // Fill in blanks with default values.
        $atts = $this->shortcode_atts( self::$defaults, $atts );

        // Randomly pick a CTA Group from the shortcode if provided, otherwise
        // from CTA Group taxonomy terms associated with current page.
        if ( $atts['groups'] ) {
            $cta_group = $this->randomly_pick( $atts['groups'] );
            Plugin::log( 'Randomly selected %s CTA Group from list provided in shortcode.', $cta_group );
        }
        else {
            $pid = get_the_ID();
            $groups = wp_get_object_terms( $pid, 'cta-group', [ 'fields' => 'slugs' ] );
            $cta_group = $this->randomly_pick( $groups );
            Plugin::log( 'Randomly selected %s CTA Group from terms on post %s.', $cta_group, $pid );
        }

        // Prepare for rendering a CTA if a CTA Group is picked and contains at
        // least one CTA.
        if ( $cta_group ) {
            $term = get_term_by( 'slug', $cta_group, 'cta-group' );
            if ( false !== $term || $term->count > 0 ) {
                JS::load();
                CSS::load();
                $output = "<div class=\"kntnt-cta\" data-cta-group=\"{$cta_group}\"></div>";
            }
        }

        return $output;

    }

    // A more forgiving version of WP's shortcode_atts().
    private function shortcode_atts( $pairs, $atts, $shortcode = '' ) {

        $atts = (array) $atts;
        $out = [];
        $pos = 0;
        while ( $name = key( $pairs ) ) {
            $default = array_shift( $pairs );
            if ( array_key_exists( $name, $atts ) ) {
                $out[ $name ] = $atts[ $name ];
            }
            else if ( array_key_exists( $pos, $atts ) ) {
                $out[ $name ] = $atts[ $pos ];
                ++ $pos;
            }
            else {
                $out[ $name ] = $default;
            }
        }

        if ( $shortcode ) {
            $out = apply_filters( "shortcode_atts_{$shortcode}", $out, $pairs, $atts, $shortcode );
        }

        return $out;

    }

    // Randomly pick an element from an array or a comma separated list.
    // Using mt_rand() since it allegedly is faster than array_rand().
    static private function randomly_pick( $list ) {
        if ( is_string( $list ) ) {
            $list = explode( ',', $list );
        }
        return is_array( $list ) && ! empty( $list ) ? $list[ mt_rand( 0, count( $list ) - 1 ) ] : null;
    }

}
