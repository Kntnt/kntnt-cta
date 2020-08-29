<?php

namespace Kntnt\CTA;

abstract class Abstract_Plugin {

    static private $ns;

    static private $plugin_dir;

    static private $plugin_url;

    static private $unsatisfied_dependencies = null;

    static private $is_debugging = null;

    static private $lang = null;

    public function __construct() {

        // This plugin's machine name a.k.a. slug.
        self::$ns = strtr( strtolower( __NAMESPACE__ ), '_\\', '--' );

        // Path to this plugin's directory relative file system root.
        self::$plugin_dir = strtr( dirname( __DIR__ ), '\\', '/' );

        // URL to this plugin's directory
        self::$plugin_url = plugins_url( '', self::$plugin_dir . '/' . self::$ns . '.php' );

        // Install script runs only on install (not activation).
        // Uninstall script runs "magically" on uninstall.
        if ( is_readable( self::$plugin_dir . '/install.php' ) ) {
            register_activation_hook( self::$plugin_dir . '/' . self::$ns . '.php', function () {
                if ( null === get_option( self::$ns, null ) ) {
                    require self::$plugin_dir . '/install.php';
                }
            } );
        }

        // Setup localization.
        add_action( 'plugins_loaded', function () {
            load_plugin_textdomain( self::$ns, false, self::$ns . '/languages' );
        } );

        // Setup this plugin to run.
        foreach ( $this->classes_to_load() as $context => $hoooks_and_classes ) {
            if ( $this->is_context( $context ) ) {
                foreach ( $hoooks_and_classes as $hook => $classes ) {
                    foreach ( $classes as $class ) {
                        add_action( $hook, [ $this->instance( $class ), 'run' ] );
                    }
                }
            }

        }

    }

    // Name space of plugin.
    public static final function ns() {
        return self::$ns;
    }

    // Plugin version.
    public static final function version() {
        $key = self::$ns . '-plugin-version';
        $version = get_transient( $key );
        if ( ! $version ) {
            $version = get_plugin_data( self::plugin_dir( self::$ns . '.php' ), false, false )['Version'];
            set_transient( $key, $version, YEAR_IN_SECONDS );
        }
        return $version;
    }

    // Return an array of not active plugins that this plugin is dependent on.
    public static final function unsatisfied_dependencies() {
        if ( null === self::$unsatisfied_dependencies ) {
            self::$unsatisfied_dependencies = [];
            foreach ( static::dependencies() as $slug => $name ) {
                if ( ! is_plugin_active( $slug ) ) {
                    self::$unsatisfied_dependencies[ $slug ] = $name;
                }
            }
        }
        return self::$unsatisfied_dependencies;
    }

    // This plugin's path relative file system root, with no trailing slash.
    // If $rel_path is given, with or without leading slash, it is appended
    // with leading slash.
    public static final function plugin_dir( $rel_path = '' ) {
        return self::str_join( self::$plugin_dir, $rel_path );
    }

    // This plugin's URL with no trailing slash. If $rel_path is given, with
    // or without leading slash, it is appended with leading slash.
    public static final function plugin_url( $rel_path = '' ) {
        return self::str_join( self::$plugin_url, $rel_path );
    }

    // This plugin's path relative WordPress root, with leading slash but no
    // trailing slash. If $rel_path is given, with or without leading slash,
    // it is appended with leading slash.
    public static final function rel_plugin_dir( $rel_path = '' ) {
        return self::str_join( substr( self::$plugin_dir, strlen( ABSPATH ) - 1 ), ltrim( $rel_path, '/' ), '/' );
    }

    // The WordPress' root relative file system root, with no trailing slash.
    // If $rel_path is given, with or without leading slash, it is appended
    // with leading slash.
    public static final function rel_wp_dir( $rel_path = '' ) {
        return self::str_join( ABSPATH, ltrim( $rel_path, '/' ), '/' );
    }

    // Returns the truth value of the statement that we are running in the
    // context asserted by $context.
    public static final function is_context( $context ) {
        return 'any' == $context ||
               'public' == $context && ( ! defined( 'WP_ADMIN' ) || ! WP_ADMIN ) ||
               'ajax' == $context && defined( 'DOING_AJAX' ) && DOING_AJAX ||
               'admin' == $context && defined( 'WP_ADMIN' ) && WP_ADMIN && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ||
               'cron' == $context && defined( 'DOING_CRON' ) && DOING_CRON ||
               'cli' == $context && defined( 'WP_CLI' ) && WP_CLI ||
               isset( $_SERVER ) && isset( $_SERVER['SCRIPT_FILENAME'] ) && pathinfo( $_SERVER['SCRIPT_FILENAME'], PATHINFO_FILENAME ) == $context;
    }

    // Returns true if and only if the debug flag is set.
    // The debug flag is a constant with the plugin's namespace with `/`
    // replaced with `_` and all letters in uppercase.
    public static final function is_debugging() {
        if ( null == self::$is_debugging ) {
            $kntnt_debug = strtr( strtoupper( self::$ns ), '-', '_' ) . '_DEBUG';
            self::$is_debugging = defined( 'WP_DEBUG' ) && constant( 'WP_DEBUG' ) && defined( $kntnt_debug ) && constant( $kntnt_debug );
        }
        return self::$is_debugging;
    }

    // Returns an instance of the class with the provided name.
    public static final function instance( $class_name ) {
        $n = strtr( strtolower( $class_name ), '_', '-' );
        $class_name = __NAMESPACE__ . '\\' . $class_name;
        require_once self::$plugin_dir . "/classes/$n.php";
        return new $class_name();
    }

    // Returns the absolute path to the file with the path `$file` relative
    // the plugin's includes directory to a file.
    public static final function template( $file ) {
        return Plugin::plugin_dir( "includes/$file" );
    }

    // Import the file with the absolute path `$template_file`. If the file
    // contains PHP-code, it is evaluated in a context where the each element
    // of associative array `$template_variables` is converted into a variable
    // with the name and value of the elements key and value, respectively. The
    // resulting content is included at the point of execution of this function
    // if  `$return_template_as_string` is false (default), otherwise returned
    // as a string.
    public static final function include_template( $template_file, $template_variables = [], $return_template_as_string = false ) {
        extract( $template_variables, EXTR_SKIP );
        if ( $return_template_as_string ) {
            ob_start();
        }
        require self::template( $template_file );
        if ( $return_template_as_string ) {
            return ob_get_clean();
        }
    }

    // Saves $content to a file in a subdirectory of WordPress' upload
    // directory. If `$subdir` is given, it's used as the subdirectory's path
    // relative the upload directory (any leading slash is removed), otherwise
    // the plugin's name space is used as name of the subdirectory. If `$name`
    // is given, it's used as the name of the file exclusive its suffix,
    // otherwise the plugin's name space is used as name of the file. If
    // `$suffix` is given, it's used as the file's suffix, otherwise "txt" is
    // used. If `$replace` is `true`, an existing file with the same name is
    // deleted before saving, otherwise the file name is enumerated. If
    // `$save_empty_file` is `true`, `$content` is saved whether it has content
    // or not, otherwise it's saved only if it's non-empty.
    public static final function save_to_file( $content, $suffix = 'txt', $name = null, $subdir = null, $replace = true, $save_empty_file = false ) {
        $subdir = trim( $subdir ?: self::$ns, '/' );
        $upload_dir_filter = function ( $upload_dir ) use ( $subdir ) {
            $upload_dir['path'] = "{$upload_dir['basedir']}/$subdir";
            $upload_dir['url'] = "{$upload_dir['baseurl']}/$subdir";
            $upload_dir['subdir'] = "$subdir";
            return $upload_dir;
        };
        add_filter( 'upload_dir', $upload_dir_filter );
        $file_name = ( $name ?: self::$ns ) . ".$suffix";
        $file_path = wp_upload_dir()['path'] . "/$file_name";
        $file_info = [
            'file' => '',
            'url' => '',
            'type' => '',
            'error' => false,
        ];
        if ( $replace && file_exists( $file_path ) ) {
            $file_info['error'] = ! @unlink( $file_path );
        }
        if ( ! $file_info['error'] && ( $content || $save_empty_file ) ) {
            $file_info = wp_upload_bits( $file_name, null, $content );
            $file_info['error'] = (bool) $file_info['error'];
        }
        remove_filter( 'upload_dir', $upload_dir_filter );
        return $file_info;
    }

    // Returns a slug identifying current language if WPML or Polylang is
    // installed and activated. Returns $default if WPML or Polylang isn't
    // installed or no current language is set.
    public static final function current_language( $default = '' ) {
        if ( null == self::$lang ) {
            self::$lang = apply_filters( 'wpml_current_language', null );
        }
        return self::$lang ?: $default;
    }

    // Returns the provided slug/key with a suffix indicating the current
    // language. If current language is not set, the original slug/key is
    // returned.
    public static final function slug_with_lang( $slug ) {
        if ( $lang = self::current_language() ) {
            $slug = "$slug:$lang";
        }
        return $slug;
    }

    // The call `option()` returns an option named as this plugin if it exists
    // and is an array. If it doesn't exists or is not an array, false is
    // returned.
    //
    // The call `option($key)` returns option()[$key] if the key exists.
    // If the $key is null or false or empty or don't exists, false is returned.
    //
    // The call `option($key, $default)` behave as `option($key)` with the
    // change that if the $key is null or false or empty or don't exists,
    // following happens: If $default is a callable, it is called and its
    // return value is returned. Otherwise the $default itself is returned.
    //
    // The call `option($key, $default, $update)` behave as
    // `option($key, $default)` with the change that the returned value is
    // stored if $key is not null nor false nor empty but don't exists and
    // $update == true.
    //
    // The call `option($key, $default, $update, $plugin)` where $plugin is a
    // non-empty string and the plugin directory of Wordpress contains a plugin
    // main file named "$plugin/$plugin.php" and this plugin is active, behaves
    // as if `option($key, $default, $update)` where called on this plugin.
    public static final function option( $key = null, $default = false, $update = false, $plugin = null ) {

        // Return default value if the provided plugin isn't active.
        // Use this plugin if no plugin is provided.
        if ( $plugin ) {
            if ( ! is_plugin_active( "$plugin/$plugin.php" ) ) {
                return self::evaluate( $default );
            }
        }
        else {
            $plugin = self::$ns;
        }

        // Get the options of the plugin.
        $opt = get_option( $plugin, null );

        // Return default value if the options isn't an array.
        if ( ! is_array( $opt ) ) {
            return self::evaluate( $default );
        }

        // If key is provided, return it's corresponding value. Return default
        // if the key is missing, and add the default value to options if
        // the update flag is set.
        if ( $key ) {
            if ( ! isset( $opt[ $key ] ) ) {
                $opt[ $key ] = self::evaluate( $default );
                if ( $update ) {
                    update_option( $plugin, $opt );
                }
            }
            return $opt[ $key ];
        }

        return $opt;

    }

    // Saves the `$key` and `$value` as a key/value-pair in an array named
    // as this plugin and stored in as WordPress option.
    public static final function set_option( $key, $value ) {
        $opt = get_option( self::$ns, [] );
        $opt[ $key ] = $value;
        return update_option( self::$ns, $opt );
    }

    // Deletes a key/value-pair, where the key is `$key`, in an array named as
    // this plugin, stored as WordPress option.
    public static final function delete_option( $key ) {
        $opt = get_option( self::$ns, [] );
        if ( isset( $opt[ $key ] ) ) {
            unset( $opt[ $key ] );
            return update_option( self::$ns, $opt );
        }
        return false;
    }

    // Returns the value of the custom field `$field`. If a `get_field()`-
    // function is provided by a plugin, e.g. ACF, it is called with `$field`
    // as the only argument. If a `get_field()`-function doesn't exist,
    // WordPress `get_metadata()`-function is called with all provided
    // arguments.
    public static final function get_field( $field, $post_id, $single = false, $type = 'post' ) {
        if ( function_exists( 'get_field' ) ) {
            // If ACF is installed, let it get the field.
            return get_field( $field, $post_id );
        }
        else {
            // If ACF not installed, let's do it ourselves.
            return get_metadata( $type, $post_id, $field, $single );
        }
    }

    // Returns $value(...$args) if $value is callable, and $value if it is not
    // callable.
    public static final function evaluate( $value, ...$args ) {
        return is_callable( $value ) ? call_user_func( $value, ...$args ) : $value;
    }

    // Return the string "{$lhs}{$sep}{$rhs}" after any trailing $sep in $lhs
    // and any leading $sep in $rhs. By default $sep is forward slash.
    public static final function str_join( $lhs, $rhs, $sep = '/' ) {
        return rtrim( $lhs, $sep ) . $sep . ltrim( $rhs, $sep );
    }

    // If `$message` isn't a string, its value is printed. If `$message` is
    // a string, it is written with each occurrence of '%s' replaced with
    // the value of the corresponding additional argument converted to string.
    // Any percent sign that should be written must be escaped with another
    // percent sign, that is `%%`. This method do nothing if debug flag isn't
    // set.
    public static final function log( $message = '', ...$args ) {
        if ( self::is_debugging() ) {
            static::_log( $message, ...$args );
        }
    }

    // If `$message` isn't a string, its value is printed. If `$message` is
    // a string, it is written with each occurrence of '%s' replaced with
    // the value of the corresponding additional argument converted to string.
    // Any percent sign that should be written must be escaped with another
    // percent sign, that is `%%`. This method works independent of
    // the debug flag.
    public static final function error( $message = '', ...$args ) {
        static::_log( $message, ...$args );
    }

    // Returns context => hook => class relationships for classes to load.
    protected abstract function classes_to_load();

    // Returns an array of 'plugin_slug' => 'Plugin Name' for each plugin that
    // must be active for his plugin to work.
    protected static function dependencies() { return []; }

    private static final function _log( $message = '', ...$args ) {
        if ( ! is_string( $message ) ) {
            $args = [ $message ];
            $message = '%s';
        }
        $caller = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 3 );
        $caller = $caller[2]['class'] . '->' . $caller[2]['function'] . '()';
        foreach ( $args as &$arg ) {
            if ( is_array( $arg ) || is_object( $arg ) ) {
                $arg = print_r( $arg, true );
            }
        }
        $message = sprintf( $message, ...$args );
        error_log( "$caller: $message" );
    }

}
