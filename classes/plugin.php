<?php

namespace Kntnt\CTA;

class Plugin extends Abstract_Plugin {

    public function classes_to_load() {
        return [
            'any' => [
                'init' => [
                    'Post_Type',
                    'Taxonomy',
                ],
            ],
            'public' => [
                'init' => [
                    'Shortcode',
                ],
                'wp_head' => [
                    'Registrar',
                ],
            ],
            'admin' => [
                'init' => [
                    'Settings',
                ],
            ],
            'ajax' => [
                'wp_ajax_kntnt_cta' => [
                    'Ajax',
                ],
                'wp_ajax_nopriv_kntnt_cta' => [
                    'Ajax',
                ],
            ],
        ];
    }

}
