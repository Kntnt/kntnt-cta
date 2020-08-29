<?php

namespace Kntnt\CTA;

class Registrar {

    public function run() {

        if ( ( $css_file_info = Plugin::option( 'css_file_info' ) ) && $css_file_info['url'] ) {
            wp_register_style( 'kntnt-cta-css', $css_file_info['url'], [], Plugin::version() );
        }

        wp_register_script( 'kntnt-cta-js', Plugin::plugin_url( 'js/kntnt-cta.js' ), [ 'jquery' ], Plugin::version(), true );
        wp_localize_script( 'kntnt-cta-js', 'kntnt_cta', [ 'ajaxurl' => admin_url( 'admin-ajax.php' ) ] );

    }

}
