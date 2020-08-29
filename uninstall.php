<?php

defined( 'WP_UNINSTALL_PLUGIN' ) && new Uninstaller;

class Uninstaller {

    public function __construct() {

        $this->remove_options( [ 'kntnt-cta' ] );
        $this->remove_files( [ 'kntnt-cta' ] );
        $this->remove_terms( [ 'cta-groups' ] );
        $this->remove_posts( [ 'cta' ], true );
        $this->remove_capabilities( [

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

    private function remove_options( $options ) {
        foreach ( $options as $option ) {
            delete_option( $option );
        }
    }

    private function remove_files( $subdirs ) {
        $upload_dir = wp_upload_dir()['path'];
        foreach ( $subdirs as $subdir ) {
            $base_dir = "$upload_dir/$subdir";
            $dir_it = new RecursiveDirectoryIterator( $base_dir, RecursiveDirectoryIterator::SKIP_DOTS );
            $files = new RecursiveIteratorIterator( $dir_it, RecursiveIteratorIterator::CHILD_FIRST );
            foreach ( $files as $file ) {
                if ( $file->isDir() ) {
                    rmdir( $file->getRealPath() );
                }
                else {
                    unlink( $file->getRealPath() );
                }
            }
            @rmdir( $base_dir );
        }
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