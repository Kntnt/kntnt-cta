<?php

namespace Kntnt\CTA;

class CSS {

    public function run() {
        if ( ( $css_file_info = Plugin::option( 'css_file_info' ) ) && $css_file_info['url'] ) {
            wp_register_style( 'kntnt-cta-css', $css_file_info['url'], [], Plugin::version() );
            if ( is_singular( 'cta' ) ) {
                wp_enqueue_style( 'kntnt-cta-css' );
            }
        }
    }

    static public function load() {
        if ( wp_style_is( 'kntnt-cta-css', 'registered' ) ) {
            wp_enqueue_style( 'kntnt-cta-css' );
        }
    }

}
