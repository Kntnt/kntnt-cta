<?php

namespace Kntnt\CTA;

class JS {

    public function run() {
        wp_register_script( 'kntnt-cta-js', Plugin::plugin_url( 'js/kntnt-cta.js' ), [ 'jquery' ], Plugin::version(), true );
        wp_localize_script( 'kntnt-cta-js', 'kntnt_cta', [ 'ajaxurl' => admin_url( 'admin-ajax.php' ) ] );
    }

    static public function load() {
        wp_enqueue_script( 'kntnt-cta-js' );
    }

}
