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
                    'CSS',
                    'JS',
                ],
            ],
            'rest' => [
                'rest_api_init' => [
                    'REST',
                ],
            ],
            'admin' => [
                'init' => [
                    'Settings',
                ],
            ],
        ];
    }

}
