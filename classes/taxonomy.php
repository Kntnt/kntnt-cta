<?php

namespace Kntnt\CTA;

class Taxonomy {

    private $slug = 'cta-group';

    private $slugs = 'cta-groups';

    public function run() {
        register_taxonomy( $this->slug, null, $this->custom_taxonomy() );
        foreach ( Plugin::option( 'post_types', [ 'cta-group' ] ) as $post_type ) {
            register_taxonomy_for_object_type( $this->slug, $post_type );
        }
    }

    private function custom_taxonomy() {
        return [

            // A short descriptive summary of what the taxonomy is for.
            'description' => 'Call To Action groups',

            // Whether the taxonomy is hierarchical.
            'hierarchical' => false,

            // Whether a taxonomy is intended for use publicly either via
            // the admin interface or by front-end users.
            'public' => true,

            // Whether the taxonomy is publicly queryable.
            'publicly_queryable' => true,

            // Whether to generate and allow a UI for managing terms in this
            // taxonomy in the admin.
            'show_ui' => true,

            // Whether to show the taxonomy in the admin menu.
            'show_in_menu' => true,

            // Makes this taxonomy available for selection in navigation menus.
            'show_in_nav_menus' => false,

            // Whether to list the taxonomy in the Tag Cloud Widget controls.
            'show_tagcloud' => false,

            // Whether to show the taxonomy in the quick/bulk edit panel.
            'show_in_quick_edit' => true,

            // Whether to display a column for the taxonomy on its post
            // type listing screens.
            'show_admin_column' => true,

            // Array of capabilities for this taxonomy.
            'capabilities' => [
                'manage_terms' => "manage_{$this->slugs}",
                'edit_terms' => "edit_{$this->slugs}",
                'delete_terms' => "delete_{$this->slugs}",
                'assign_terms' => "assign_{$this->slugs}",
            ],

            // Sets the query var key for this taxonomy. Default $taxonomy key.
            // If false, a taxonomy cannot be loaded
            // at ?{query_var}={term_slug}. If a string,
            // the query ?{query_var}={term_slug} will be valid.
            'query_var' => false,

            // Triggers the handling of rewrites for this taxonomy.
            // Replace the array with false to prevent handling of rewrites.
            'rewrite' => [

                // Customize the permastruct slug.
                'slug' => $this->slug,

                // Whether the permastruct should be prepended
                // with WP_Rewrite::$front.
                'with_front' => true,

                // Either hierarchical rewrite tag or not.
                'hierarchical' => false,

                // Endpoint mask to assign. If null and permalink_epmask
                // is set inherits from $permalink_epmask. If null and
                // permalink_epmask is not set, defaults to EP_PERMALINK.
                'ep_mask' => null,

            ],

            // Default term to be used for the taxonomy.
            'default_term' => [
                'name' => 'Standard',
                'slug' => 'standard',
                'description' => '',
            ],

            // An array of labels for this taxonomy.
            'labels' => [
                'name' => _x( 'CTA Groups', 'Plural name', 'kntnt-cta-custom-taxonomy' ),
                'singular_name' => _x( 'CTA Group', 'Singular name', 'kntnt-cta-custom-taxonomy' ),
                'search_items' => _x( 'Search CTAs', 'Search items', 'kntnt-cta-custom-taxonomy' ),
                'popular_items' => _x( 'Search CTAs', 'Search items', 'kntnt-cta-custom-taxonomy' ),
                'all_items' => _x( 'All CTAs', 'All items', 'kntnt-cta-custom-taxonomy' ),
                'parent_item' => _x( 'Parent CTA', 'Parent item', 'kntnt-cta-custom-taxonomy' ),
                'parent_item_colon' => _x( 'Parent CTA colon', 'Parent item colon', 'kntnt-cta-custom-taxonomy' ),
                'edit_item' => _x( 'Edit CTA', 'Edit item', 'kntnt-cta-custom-taxonomy' ),
                'view_item' => _x( 'View CTA', 'View item', 'kntnt-cta-custom-taxonomy' ),
                'update_item' => _x( 'Update CTA', 'Update item', 'kntnt-cta-custom-taxonomy' ),
                'add_new_item' => _x( 'Add new CTA', 'Add new item', 'kntnt-cta-custom-taxonomy' ),
                'new_item_name' => _x( 'New CTA name', 'New item name', 'kntnt-cta-custom-taxonomy' ),
                'separate_items_with_commas' => _x( 'Separate CTAs with commas', 'Separate items with commas', 'kntnt-cta-custom-taxonomy' ),
                'add_or_remove_items' => _x( 'Add or remove CTAs', 'Add or remove items', 'kntnt-cta-custom-taxonomy' ),
                'choose_from_most_used' => _x( 'Choose from most used', 'Choose from most used', 'kntnt-cta-custom-taxonomy' ),
                'not_found' => _x( 'Not found', 'Not found', 'kntnt-cta-custom-taxonomy' ),
                'no_terms' => _x( 'No terms', 'No terms', 'kntnt-cta-custom-taxonomy' ),
                'items_list_navigation' => _x( 'CTAs list navigation', 'Items list navigation', 'kntnt-cta-custom-taxonomy' ),
                'items_list' => _x( 'Items list', 'CTAs list', 'kntnt-cta-custom-taxonomy' ),
                'most_used' => _x( 'Most used', 'Most used', 'kntnt-cta-custom-taxonomy' ),
                'back_to_items' => _x( 'Back to CTAs', 'Back to items', 'kntnt-cta-custom-taxonomy' ),
            ],

        ];
    }

}
