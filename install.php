<?php

defined( 'WPINC' ) && new Installer;

class Installer {

    public function __construct() {

        add_option( 'kntnt-cta', [] );

        $this->add_capabilities( [

            // Roles that get all capabilities by default.
            'administrator',
            'editor',

        ], [

            // Capabilities for managing CTAs (custom post type)
            'kntnt_edit_ctas',
            'kntnt_edit_others_ctas',
            'kntnt_edit_private_ctas',
            'kntnt_edit_published_ctas',
            'kntnt_publish_ctas',
            'kntnt_read_private_ctas',
            'kntnt_delete_ctas',
            'kntnt_delete_others_ctas',
            'kntnt_delete_private_ctas',
            'kntnt_delete_published_ctas',

            // Capabilities for managing CTA Groups (custom taxonomy)
            'kntnt_manage_cta_groups',
            'kntnt_edit_cta_groups',
            'kntnt_delete_cta_groups',
            'kntnt_assign_cta_groups',

        ] );

    }

    private function add_capabilities( $roles, $capabilities ) {
        foreach ( $roles as $role ) {
            $role = get_role( $role );
            foreach ( $capabilities as $capability ) {
                $role->add_cap( $capability );
            }
        }
    }

}