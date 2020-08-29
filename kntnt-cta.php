<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Kntnt Call To Action (CTA)
 * Plugin URI:        https://github.com/kntnt/kntnt-cta
 * GitHub Plugin URI: https://github.com/kntnt/kntnt-cta
 * Description:       Provides post type, taxonomy and shortcode to allow dynamic insertion of Call To Action (CTA).
 * Version:           1.0.0
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       kntnt-cta
 * Domain Path:       /languages
 */

namespace Kntnt\CTA;

defined( 'WPINC' ) || die;

// Uncomment following line to debug this plugin.
define( 'KNTNT_CTA_DEBUG', true );

spl_autoload_register( function ( $class ) {
    $ns_len = strlen( __NAMESPACE__ );
    if ( 0 == substr_compare( $class, __NAMESPACE__, 0, $ns_len ) ) {
        require_once __DIR__ . '/classes/' . strtr( strtolower( substr( $class, $ns_len + 1 ) ), '_', '-' ) . '.php';
    }
} );

new Plugin();