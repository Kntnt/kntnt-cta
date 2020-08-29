<?php

namespace Kntnt\CTA;

class Post_Type {

    private $slug = 'cta';

    private $slugs = 'ctas';

    public function run() {

        register_post_type( $this->slug, $this->custom_post_type() );

        // This filter is used within WP_Query::get_posts() immediately after
        // posts are fetched and internally processed. If a fetched post has no
        // content, the default content on the settings page is used.
        add_filter( 'the_posts', [ $this, 'filter_the_post' ], 0, 2 );

    }

    public function filter_the_post( $posts, $query ) {
        foreach ( $posts as &$post ) {
            if ( $this->slug == $post->post_type && ! $post->post_content ) {
                $post->post_content = Plugin::option( 'content' );
                Plugin::log( "Post content empty; fallback to default value." );
            }
        }
        return $posts;
    }

    private function custom_post_type() {
        return [

            // A short descriptive summary of what the post type is.
            'description' => 'Call To Actions',

            // Whether the post type is hierarchical
            'hierarchical' => false,

            // Custom capabilities.
            'capability_type' => [ $this->slug, $this->slugs ],
            'map_meta_cap' => true,

            // Core feature(s) the post type supports. Core features include
            // 'title', 'editor', 'comments', 'revisions', 'trackbacks',
            // 'author', 'excerpt', 'page-attributes', 'thumbnail',
            // 'custom-fields', and 'post-formats'. Additionally, the
            // 'revisions' feature dictates whether the post type will store
            // revisions, and the 'comments' feature dictates whether the
            // comments count will show on the edit screen.
            'supports' => [
                'title',
                'editor',
                'custom-fields',
            ],

            // Whether a post type is intended for use publicly either via
            // the admin interface or by front-end users.
            'public' => true,

            // Whether queries can be performed on the front end for the post type
            // as part of parse_request().
            'publicly_queryable' => true,

            // Whether to exclude posts with this post type from front end
            // search results.
            'exclude_from_search' => true,

            // Makes this post type available for selection in navigation menus.
            'show_in_nav_menus' => false,

            // Whether to generate and allow a UI for managing this post type in the admin.
            'show_ui' => true,

            // Where to show the post type in the admin menu if 'show_ui' => true.
            'show_in_menu' => true,

            // The position in the menu order the post type should appear.
            // Use null to add it to the bottom.
            'menu_position' => null,

            // The url to the icon to be used for this menu. Pass a
            // base64-encoded SVG using a data URI, which will be colored
            // to match the color scheme -- this should begin with
            // 'data:image/svg+xml;base64,'. Pass the name of a Dashicons
            // helper class to use a font icon, e.g. 'dashicons-chart-pie'.
            // See https://developer.wordpress.org/resource/dashicons.
            // Pass 'none' to leave div.wp-menu-image empty so an icon can be
            // added via CSS. Pass null to use the posts icon.
            'menu_icon' => 'dashicons-megaphone',

            // Makes this post type available via the admin bar.
            'show_in_admin_bar' => true,

            // Whether to allow this post type to be exported.
            'can_export' => true,

            // Whether to delete posts of this type when deleting a user. If
            // true, posts of this type belonging to the user will be moved to
            // Trash when then user is deleted. If false, posts of this type
            // belonging to the user will *not* be trashed or deleted. If null,
            // posts are trashed if post_type_supports('author'). Otherwise
            // posts are not trashed or deleted.
            'delete_with_user' => false,

            // Whether there should be post type archives, or if a string,
            // the archive slug to use. Will generate the proper rewrite rules
            // if $rewrite is enabled.
            'has_archive' => false,

            // Sets the query_var key for this post type. If false, a post type
            // cannot be loaded at ?{query_var}={post_slug}. If specified as a
            // string, the query ?{query_var_string}={post_slug} will be valid.
            'query_var' => false,

            // Triggers the handling of rewrites for this post type.
            // Replace the array with false to prevent handling of rewrites.
            'rewrite' => [

                // Customize the permastruct slug.
                'slug' => $this->slug,

                // Whether the permastruct should be prepended with WP_Rewrite::$front.
                'with_front' => true,

                // Whether the feed permastruct should be built for this post type.
                'feeds' => false,

                // Whether the permastruct should provide for pagination.
                'pages' => true,

                // Endpoint mask to assign. If null and permalink_epmask is set,
                // inherits from $permalink_epmask. If null and permalink_epmask
                // is not set, defaults to EP_PERMALINK.
                'ep_mask' => null,

            ],

            //  An array of labels for this post type. If not set, post labels are
            // inherited for non-hierarchical types and page labels for
            // hierarchical ones.
            'labels' => [
                'name' => _x( 'CTAs', 'Plural name', 'kntnt-cta-custom-post-type' ),
                'singular_name' => _x( 'CTA', 'Singular name', 'kntnt-cta-custom-post-type' ),
                'menu_name' => _x( 'CTAs', 'Menu name (plural)', 'kntnt-cta-custom-post-type' ),
                'add_new' => _x( 'Add New', 'Add new', 'kntnt-cta-custom-post-type' ),
                'add_new_item' => _x( 'Add New CTA', 'Add new item', 'kntnt-cta-custom-post-type' ),
                'new_item' => _x( 'New CTA', 'New item', 'kntnt-cta-custom-post-type' ),
                'edit_item' => _x( 'Edit CTA', 'Edit item', 'kntnt-cta-custom-post-type' ),
                'all_items' => _x( 'All CTAs', 'All item', 'kntnt-cta-custom-post-type' ),
                'view_item' => _x( 'View CTA', 'View item', 'kntnt-cta-custom-post-type' ),
                'search_items' => _x( 'Search CTAs', 'Search items', 'kntnt-cta-custom-post-type' ),
                'not_found' => _x( 'No CTA found', 'No item found', 'kntnt-cta-custom-post-type' ),
                'not_found_in_trash' => _x( 'No CTA found in Trash', 'No item found in Trash', 'kntnt-cta-custom-post-type' ),
                'parent_item_colon' => _x( 'Parent CTA:', 'Parent item:', 'kntnt-cta-custom-post-type' ),
                'archives' => _x( 'CTA Archives', 'Items Archives', 'kntnt-cta-custom-post-type' ),
                'attributes' => _x( 'CTA attributes', 'Item Attributes', 'kntnt-cta-custom-post-type' ),
                'insert_into_item' => _x( 'Insert into CTA', 'insert into item', 'kntnt-cta-custom-post-type' ),
                'uploaded_to_this_item' => _x( 'Uploaded to this CTA', 'uploaded to this item', 'kntnt-cta-custom-post-type' ),
                'featured_image' => _x( 'Featured image', 'featured image', 'kntnt-cta-custom-post-type' ),
                'set_featured_image' => _x( 'Set featured image', 'set featured image', 'kntnt-cta-custom-post-type' ),
                'remove_featured_image' => _x( 'Remove featured image', 'Remove featured image', 'kntnt-cta-custom-post-type' ),
                'use_featured_image' => _x( 'Use as featured image', 'Use as featured image', 'kntnt-cta-custom-post-type' ),
                'filter_items_list' => _x( 'Filter CTAs list', 'Filter items list', 'kntnt-cta-custom-post-type' ),
                'items_list_navigation' => _x( 'CTAs list navigation', 'Items list navigation', 'kntnt-cta-custom-post-type' ),
                'items_list' => _x( 'CTAs list', 'Items list', 'kntnt-cta-custom-post-type' ),
                'item_published' => _x( 'CTA published', 'Item published', 'kntnt-cta-custom-post-type' ),
                'item_published_privately' => _x( 'CTA published privately', 'Item published privately', 'kntnt-cta-custom-post-type' ),
                'item_reverted_to_draft' => _x( 'CTA reverted to draft', 'Item reverted to draft', 'kntnt-cta-custom-post-type' ),
                'item_scheduled' => _x( 'CTA scheduled', 'Item scheduled', 'kntnt-cta-custom-post-type' ),
                'item_updated' => _x( 'CTA updated', 'Item updated', 'kntnt-cta-custom-post-type' ),
            ],

        ];
    }

}
