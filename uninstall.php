<?php

defined( 'WP_UNINSTALL_PLUGIN' ) && new Uninstaller;

class Uninstaller {

    public function __construct() {

        delete_option( 'kntnt-cta' );

        $this->remove_posts( [ 'cta' ], true );

        $this->remove_terms( [ 'cta-groups' ] );

        $this->remove_capabilities( [

            // Roles that get all capabilities by default.
            'administrator',
            'editor',

        ], [

            // Capabilities for managing CTAs (custom post type)
            'edit_ctas',
            'edit_others_ctas',
            'edit_private_ctas',
            'edit_published_ctas',
            'publish_ctas',
            'read_private_ctas',
            'delete_ctas',
            'delete_others_ctas',
            'delete_private_ctas',
            'delete_published_ctas',

            // Capabilities for managing CTA Groups (custom taxonomy)
            'manage_cta_groups',
            'edit_cta_groups',
            'delete_cta_groups',
            'assign_cta_groups',

        ] );

    }

    private function remove_posts( $post_types, $force_delete = false ) {
        foreach ( $post_types as $post_type ) {
            foreach ( get_posts( [ 'post_type' => $post_type, 'posts_per_page' => - 1 ] ) as $post ) {
                wp_delete_post( $post->ID, $force_delete );
            }
        }
    }

    private function remove_terms( $taxonomies ) {
        global $wpdb;
        foreach ( $taxonomies as $taxonomy ) {
            $terms = $wpdb->get_results( $wpdb->prepare( "SELECT t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN ('%s') ORDER BY t.name ASC", $taxonomy ) );
            if ( $terms ) {
                foreach ( $terms as $term ) {
                    $wpdb->delete( $wpdb->term_taxonomy, [ 'term_taxonomy_id' => $term->term_taxonomy_id ] );
                    $wpdb->delete( $wpdb->term_relationships, [ 'term_taxonomy_id' => $term->term_taxonomy_id ] );
                    $wpdb->delete( $wpdb->terms, [ 'term_id' => $term->term_id ] );
                }
            }
            $wpdb->delete( $wpdb->term_taxonomy, [ 'taxonomy' => $taxonomy ], [ '%s' ] );
        }
    }

    private function remove_capabilities( $roles, $capabilities ) {
        foreach ( $roles as $role ) {
            $role = get_role( $role );
            foreach ( $capabilities as $capability ) {
                $role->remove_cap( $capability );
            }
        }
    }

}