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

        $fields['submit'] = [
            'type' => 'submit',
        ];

        return $fields;

    }

    protected final function actions_after_saving( $opt, $fields ) {
        $info = Plugin::save_to_file( $opt['style'], 'css' );
        Plugin::set_option( 'css_file_info', $info );
    }

}
