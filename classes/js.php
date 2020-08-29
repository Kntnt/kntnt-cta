<?php

namespace Kntnt\CTA;

class JS {

    public function run() {
        wp_register_script( 'kntnt-cta-js', Plugin::plugin_url( 'js/kntnt-cta.js' ), [ 'jquery', 'wp-api-request' ], Plugin::version(), true );
    }

    static public function load() {
        wp_enqueue_script( 'kntnt-cta-js' );
    }

}
