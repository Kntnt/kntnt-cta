<?php

namespace Kntnt\CTA;

class Settings extends Abstract_Settings {

    /**
     * Returns the settings menu title.
     */
    protected function menu_title() {
        return __( 'CTA', 'kntnt-cta' );
    }

    /**
     * Returns the settings page title.
     */
    protected function page_title() {
        return __( 'Kntnt Call To Action (CTA)', 'kntnt-cta' );
    }

    /**
     * Returns all fields used on the settings page.
     */
    protected function fields() {

        $fields['content'] = [
            'type' => 'text area',
            'label' => __( "Default content", 'kntnt-cta' ),
            'cols' => 80,
            'rows' => 15,
            'description' => __( 'Default content used when the content of a CTA is empty.', 'kntnt-cta' ),
        ];

        $fields['style'] = [
            'type' => 'text area',
            'label' => __( "Extra CSS", 'kntnt-cta' ),
            'cols' => 80,
            'rows' => 15,
            'description' => __( 'Extra CSS rules added to pages containing a CTA.', 'kntnt-cta' ),
        ];

        $fields['post_types'] = [
            'type' => 'checkbox group',
            'label' => __( "Enabled post types", 'kntnt-cta' ),
            'description' => __( 'Select post types that can have CTAs.', 'kntnt-cta' ),
            'options' => $this->get_post_types(),
            'default' => 'post',
            'filter-after' => function ( $post_types ) {
                $post_types['cta'] = 'cta';
                return $post_types;
            },
        ];

        $fields['submit'] = [
            'type' => 'submit',
        ];

        return $fields;

    }

    protected final function actions_after_saving( $opt, $fields ) {
        $info = Plugin::save_to_file( $opt['style'], 'css' );
        Plugin::set_option( 'css_file_info', $info );
    }

    public function get_post_types() {
        $post_types = wp_list_pluck( get_post_types( [ 'public' => true ], 'objects' ), 'label' );
        unset( $post_types['cta'] );
        return $post_types;
    }
}
