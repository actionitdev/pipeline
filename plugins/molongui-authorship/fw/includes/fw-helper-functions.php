<?php
defined( 'ABSPATH' ) or exit;
require_once 'deprecated.php';
require_once 'helpers/options.php';
if ( !function_exists( 'molongui_get_constant' ) )
{
    function molongui_get_constant( $plugin_id, $constant, $fw = false )
    {
        $const = "MOLONGUI_" . strtoupper( strtr( $plugin_id, array( 'molongui-' => '', ' ' => '_', '-' => '_' ) ) . "_" . ( $fw ? "FW_" : "" ) . $constant );
	    $value = defined( $const ) ? constant( $const ) : false;
	    return $value;
    }
}
if ( !function_exists( 'molongui_get_plugin' ) )
{
    function molongui_get_plugin( $id )
    {
        $plugin              = new stdClass();
        $plugin->id          = $id;
        $plugin->slug        = molongui_get_constant( $id, "SLUG" );
        $plugin->name        = molongui_get_constant( $id, "NAME" );
        $plugin->title       = molongui_get_constant( $id, "TITLE" );
        $plugin->label       = molongui_get_constant( $id, "LABEL" );
        $plugin->version     = molongui_get_constant( $id, "VERSION" );
        $plugin->web         = molongui_get_constant( $id, "WEB" );

        $plugin->dir         = molongui_get_constant( $id, "DIR" );
        $plugin->url         = molongui_get_constant( $id, "URL" );
        $plugin->basename    = molongui_get_constant( $id, "BASENAME" );
        $plugin->namespace   = molongui_get_constant( $id, "NAMESPACE" );

        $plugin->db_version  = molongui_get_constant( $id, "DB" );
        $plugin->db_prefix   = molongui_get_constant( $id, "DB_PREFIX" );
        $plugin->db_settings = molongui_get_constant( $id, "DB_VERSION" );

        $plugin->license     = molongui_get_constant( $id, "LICENSE" );
        $plugin->is_pro      = molongui_get_constant( $id, "IS_PRO" );
        $plugin->has_pro     = molongui_get_constant( $id, "HAS_PRO" );
        $plugin->has_upgrade = $plugin->has_pro ? false : molongui_get_constant( $id, "HAS_UPGRADE" );

        $plugin->base_id     = strtolower( str_replace( ' ', '_', molongui_get_constant( $id, "BASE_ID" ) ) );
        $plugin->base_name   = $plugin->base_id ? 'molongui-'.strtolower( str_replace( ' ', '-', $plugin->base_id ) ) : false;
        $plugin->base_dir    = ( $plugin->base_id and file_exists( $plugin->dir.'../molongui-'.$plugin->base_id ) ) ? $plugin->dir.'../molongui-'.$plugin->base_id.'/' : false;

        return $plugin;
    }
}
if ( !function_exists( 'molongui_is_active' ) )
{
    function molongui_is_active( $plugin_dir )
    {
        if ( file_exists( $file = $plugin_dir.'config/update.php' ) ) $config = include $file;

        return ( isset( $config ) and get_option( $config['db']['activated_key'] ) == 'Activated' ) ? true : false;
    }
}
if ( !function_exists( 'molongui_is_rest_api_request' ) )
{
    function molongui_is_rest_api_request()
    {
        if ( empty( $_SERVER['REQUEST_URI'] ) ) return false;

        $rest_prefix         = trailingslashit( rest_get_url_prefix() );
        $is_rest_api_request = ( false !== strpos( $_SERVER['REQUEST_URI'], $rest_prefix ) ); // phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

        return apply_filters( 'molongui_is_rest_api_request', $is_rest_api_request );
    }
}
if ( !function_exists( 'molongui_is_request' ) )
{
    function molongui_is_request( $type )
    {
        switch ( $type )
        {
            case 'admin':
            case 'backend':
                return ( is_admin() and ( !defined( 'DOING_AJAX' ) or !DOING_AJAX ) );
            case 'ajax':
                return ( is_admin() and defined( 'DOING_AJAX' ) and DOING_AJAX );
            case 'api':
                return molongui_is_rest_api_request();
            case 'cron':
                return defined( 'DOING_CRON' );
            case 'customizer':
                return ( is_customize_preview() );
            case 'front':
            case 'frontend':
                return ( !is_admin() or defined( 'DOING_AJAX' ) ) and !molongui_is_rest_api_request() and !defined( 'DOING_CRON' );
        }
    }
}
if ( !function_exists( 'molongui_display_notice' ) )
{
	function molongui_display_notice( $plugin_id, $notice )
	{
		if ( empty( $notice ) ) return;
		$fw_dir     = molongui_get_constant( $plugin_id, 'DIR', true );
		$fw_url     = molongui_get_constant( $plugin_id, 'URL', true );
		$fw_version = molongui_get_constant( $plugin_id, 'VERSION', true );
        $file = 'admin/css/molongui-fw-common.242a.min.css';
        if ( is_rtl() ) $file = 'admin/css/molongui-fw-common-rtl.cee3.min.css';
		if ( file_exists( $fw_dir.$file ) ) wp_enqueue_style( 'mcf-notices', $fw_url.$file, array(), $fw_version, 'all' );
		Molongui\Authorship\Fw\Includes\Notice::display( $notice['id'], $notice['type'], $notice['content'], $notice['dismissible'], $notice['dismissal'], $notice['class'], $notice['pages'], $plugin_id );
	}
}
if ( !function_exists( 'molongui_help_tip' ) )
{
    function molongui_help_tip( $tip, $allow_html = false )
    {
        if ( $allow_html )
        {
            $tip = molongui_sanitize_tooltip( $tip );
        }
        else
        {
            $tip = esc_attr( $tip );
        }
        return '<i class="molongui-icon-tip m-help-tip" data-tip="' . $tip . '"></i>';
    }
}
if ( !function_exists( 'molongui_premium_tip' ) )
{
    function molongui_premium_tip()
    {
        return sprintf( __( '%sPremium feature%s. You are using the free version of this plugin. Consider purchasing the Premium Version to enable this feature.', 'molongui-authorship' ), '<strong>', '</strong>' );
    }
}
if ( !function_exists( 'molongui_sanitize_tooltip' ) )
{
    function molongui_sanitize_tooltip( $var )
    {
        return htmlspecialchars( wp_kses( html_entity_decode( $var ), array
        (
            'br'     => array(),
            'em'     => array(),
            'strong' => array(),
            'small'  => array(),
            'span'   => array(),
            'ul'     => array(),
            'li'     => array(),
            'ol'     => array(),
            'p'      => array(),
        )));
    }
}
if ( !function_exists( 'molongui_request_data' ) )
{
    function molongui_request_data( $url )
    {
        $response = null;
	    $args = array
        (
		    'method'      => 'GET',
		    'timeout'     => 20,
		    'redirection' => 10,
		    'httpversion' => '1.1',
		    'sslverify'   => false,
        );
	    $response = wp_remote_get( $url, $args );
	    if( is_wp_error( $response ) or !isset( $response ) or empty( $response ) )
	    {

		    $response = 0;
	    }
	    else
	    {
		    $response = unserialize( wp_remote_retrieve_body( $response ) );
	    }
        return $response;
    }
}
if ( !function_exists( 'molongui_curl' ) )
{
    function molongui_curl( $url )
    {
        $curl = curl_init( $url );

        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_HEADER, 0 );
        curl_setopt( $curl, CURLOPT_USERAGENT, '' );
        curl_setopt( $curl, CURLOPT_TIMEOUT, 10 );
        curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );

        $response = curl_exec( $curl );
        if( 0 !== curl_errno( $curl ) || 200 !== curl_getinfo( $curl, CURLINFO_HTTP_CODE ) )
        {
            $response = null;
        }
        curl_close( $curl );

        return $response;
    }
}
if ( !function_exists( 'molongui_is_bool' ) )
{
    function molongui_is_bool( $var )
    {
        if ( '0' === $var or '1' === $var ) return true;

        return false;
    }
}
if ( !function_exists( 'molongui_get_post_types' ) )
{
    function molongui_get_post_types( $type = 'all', $output = 'names', $setting = false )
    {
        $wp_post_types     = ( ( $type == 'wp'  or $type == 'all' ) ? get_post_types( array( 'public' => true, '_builtin' => true  ), $output ) : array() );
        $custom_post_types = ( ( $type == 'cpt' or $type == 'all' ) ? get_post_types( array( 'public' => true, '_builtin' => false ), $output ) : array() );
        $post_types = array_merge( $wp_post_types, $custom_post_types );
        if ( $setting )
        {
            $options = array();

            foreach ( $post_types as $post_type )
            {
                $options[] = array( 'id' => $post_type->name, 'label' => $post_type->labels->name );
            }

            return $options;
        }
        return $post_types;
    }
}
if ( !function_exists('molongui_supported_post_types') )
{
    function molongui_supported_post_types( $plugin_id, $type = 'all', $setting = false )
    {
        $post_types = $options = array();
        $settings = (array) get_option( molongui_get_constant( $plugin_id, 'MAIN_SETTINGS', false ) );
        if ( !isset( $settings['post_types'] ) ) return ( $setting ? $options : $post_types );
        foreach ( molongui_get_post_types( $type, 'objects', false ) as $post_type_name => $post_type_object )
        {
            if ( in_array( $post_type_name, explode( ",", $settings['post_types'] ) ) )
            {
                $post_types[] = $post_type_name;
                $options[]    = array( 'id' => $post_type_name, 'label' => $post_type_object->labels->name, 'singular' => $post_type_object->labels->singular_name );
            }
        }
        return ( $setting ? $options : $post_types );
    }
}
if ( !function_exists('molongui_enabled_post_screens') )
{
    function molongui_enabled_post_screens( $plugin_id, $type = 'all' )
    {
        $screens = molongui_supported_post_types( $plugin_id, $type );
        foreach ( $screens as $screen ) $screens[] = 'edit-'.$screen;
        return $screens;
    }
}
if ( !function_exists('molongui_post_categories') )
{
    function molongui_post_categories( $setting = false )
    {
        $categories = $options = array();
	    $post_categories = get_categories( array
        (
		    'orderby' => 'name',
            'order'   => 'ASC',
	    ));
        foreach ( $post_categories as $category )
        {
	        $categories[] = $category->name;
            $premium      = true;//in_array( $category->name, array( 'post', 'page' ) ) ? false : true;
            $options[]    = array( 'id' => $category->cat_ID, 'label' => $category->name, 'premium' => $premium );
        }
        return ( $setting ? $options : $categories );
    }
}
if ( !function_exists('molongui_is_post_type_enabled') )
{
    function molongui_is_post_type_enabled( $plugin, $post_type = null, $post_types = null )
    {
        if ( !$post_type  ) $post_type  = get_post_type();
        if ( !$post_types ) $post_types = molongui_supported_post_types( $plugin, 'all' );

        return (bool) in_array( $post_type, $post_types );
    }
}
if ( !function_exists( 'molongui_debug' ) )
{
    function molongui_debug( $vars, $backtrace = false, $in_admin = true, $die = false, $check = true )
    {
        if ( molongui_is_request( 'ajax' ) or molongui_is_request( 'api' ) or wp_is_json_request() ) return;
        if ( is_admin() )
        {
            echo '<style>#wpwrap > .m_debug { margin: 3em 1em 3em 14em; padding: 1em; border: 2px dashed green; background: #fbfbfb; }' .
                 '       #wpcontent .m_debug { margin: 3em 1em 3em 0em; padding: 1em; border: 2px dashed green; background: #fbfbfb; }</style>';
        }

        if ( $check )
        {
	        if ( !function_exists( 'is_user_logged_in' ) or !function_exists( 'current_user_can' ) )
	        {
		        echo '<pre class="m_debug">';
		        _e( 'Too early to run molongui_debug() function. Set the $check parameter to FALSE to display debug data to everyone (not just to logged in administrator).', 'molongui-authorship' );
		        echo '</pre>';

		        return;
	        }
	        if ( !is_user_logged_in() and !current_user_can( 'administrator' ) ) return;
        }
	    if ( !$in_admin and is_admin() ) return;
	    echo '<pre class="' . ( is_admin() ? 'm_debug' : '' ) . '">';
	    if ( $backtrace )
        {
	        $dbt  = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 );
	        $info = array
            (
                'file'        => ( isset( $dbt[0]['file'] )     ? $dbt[0]['file'] : '' ),
                'class'       => ( isset( $dbt[1]['class'] )    ? $dbt[1]['class'] : '' ),
                'function'    => ( isset( $dbt[1]['function'] ) ? $dbt[1]['function'] : '' ),
                'filter'      => current_filter(),
                'is_admin'    => molongui_is_request( 'admin' ),
                'is_front'    => molongui_is_request( 'front' ),
                'is_ajax'     => molongui_is_request( 'ajax'  ),
                'is_cron'     => molongui_is_request( 'cron'  ),
                'in_the_loop' => in_the_loop(),
                'backtrace'   => wp_debug_backtrace_summary( null, 0, false ),
            );
	        $vars = array_merge( $info, ( is_array( $vars ) ? $vars : (array)$vars ) );
        }
        print_r( $vars );
        echo "</pre>";
        if ( $die ) die;
    }
}
if ( !function_exists( 'molongui_get_sites' ) )
{
    function molongui_get_sites()
    {
	    if ( function_exists( 'get_sites' ) && function_exists( 'get_current_network_id' ) )
	    {
		    $site_ids = get_sites( array( 'fields' => 'ids', 'network_id' => get_current_network_id() ) );
	    }
	    else
	    {
		    global $wpdb;
		    $site_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs WHERE site_id = $wpdb->siteid;" );
	    }

	    return $site_ids;
    }
}
if ( !function_exists( 'molongui_get_acronym' ) )
{
    function molongui_get_acronym ( $words, $length = 3 )
    {
        $acronym = '';
        foreach ( explode( ' ', $words ) as $word ) $acronym .= mb_substr( $word, 0, 1, 'utf-8' );

        return strtoupper( mb_substr( $acronym, 0, $length ) );
    }
}
if ( !function_exists( 'molongui_let_to_num' ) )
{
    function molongui_let_to_num( $size )
    {
        $l   = substr( $size, - 1 );
        $ret = substr( $size, 0, - 1 );
        switch ( strtoupper( $l ) )
        {
            case 'P':
                $ret *= 1024;
            case 'T':
                $ret *= 1024;
            case 'G':
                $ret *= 1024;
            case 'M':
                $ret *= 1024;
            case 'K':
                $ret *= 1024;
        }

        return $ret;
    }
}
if ( !function_exists( 'molongui_get_ip' ) )
{
    function molongui_get_ip()
    {
        $ip = '127.0.0.1';

        if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) )
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        elseif( !empty( $_SERVER['REMOTE_ADDR'] ) )
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return apply_filters( 'molongui_get_ip', $ip );
    }
}
if ( !function_exists( 'molongui_get_domain' ) )
{
    function molongui_get_domain()
    {
        $scheme    = isset( $_SERVER['REQUEST_SCHEME'] ) ? $_SERVER['REQUEST_SCHEME'].'://' : '';
        $host      = isset( $_SERVER['HTTP_HOST']      ) ? $_SERVER['HTTP_HOST'] : '';
        $subfolder = isset( $_SERVER['DOCUMENT_URI']   ) ? explode( 'wp-admin', $_SERVER['DOCUMENT_URI'] ) : '';
        $subfolder = is_array( $subfolder ) ? $subfolder[0] : '';

        echo $scheme.$host.$subfolder;
    }
}
if ( !function_exists( 'molongui_get_base64_svg' ) )
{
    function molongui_get_base64_svg( $svg, $base64 = true )
    {
        if ( $base64 )
        {
            return 'data:image/svg+xml;base64,' . base64_encode( $svg );
        }

        return $svg;
    }
}
if ( !function_exists( 'molongui_clean' ) )
{
    function molongui_clean( $var )
    {
        if ( is_array( $var ) ) return array_map( 'molongui_clean', $var );
        else return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
    }
}
if ( !function_exists( 'molongui_sort_array' ) )
{
    function molongui_sort_array( $array = array(), $order = 'ASC', $orderby = 'key' )
    {
        if ( empty( $array ) ) return $array;
        switch ( $orderby )
        {
            case 'key':
                ksort( $array );
            break;

            default:
                uasort( $array , function ( $item1, $item2 ) use ( $orderby )
                {
                    if ( $item1[$orderby] == $item2[$orderby] ) return 0;
                    return $item1[$orderby] < $item2[$orderby] ? -1 : 1;
                });
            break;
        }
        if ( 'desc' === strtolower( $order ) ) $array = array_reverse( $array );
        return $array;
    }
}
if ( !function_exists( 'molongui_are_arrays_equal' ) )
{
    function molongui_are_arrays_equal( $array1, $array2, $sort = false )
    {
        if ( $sort )
        {
            if ( !empty( $array1 ) ) array_multisort( $array1 );
            if ( !empty( $array2 ) ) array_multisort( $array2 );
        }

        return ( serialize( $array1 ) === serialize( $array2 ) );
    }
}
if ( !function_exists( 'molongui_parse_array_attribute' ) )
{
    function molongui_parse_array_attribute( $string )
    {
        $no_whitespaces = preg_replace( '/\s*,\s*/', ',', filter_var( $string, FILTER_SANITIZE_STRING ) );
        $array = explode( ',', $no_whitespaces );
        return $array;
    }
}
if ( !function_exists( 'molongui_ascii_encode' ) )
{
	function molongui_ascii_encode( $input )
	{
		$output = '';
		for ( $i = 0; $i < strlen( $input ); $i++ ) $output .= '&#'.ord( $input[$i] ).';';
		return $output;
	}
}
if ( !function_exists( 'molongui_get_support' ) )
{
	function molongui_get_support()
	{
        return admin_url( '/admin.php?page=molongui-support' );
	}
}
if ( !function_exists( 'molongui_get_plugin_settings' ) )
{
	function molongui_get_plugin_settings( $id = '', $names = '' )
    {
        if ( empty( $id ) or empty( $names ) ) return;
        $settings = array();
        if ( is_array( $names ) ) foreach ( $names as $name ) $settings = array_merge( $settings, (array) get_option( molongui_get_constant( $id, $name.'_SETTINGS', false ) ) );
        else $settings = get_option( molongui_get_constant( $id, $names.'_SETTINGS', false ) );
        return $settings;
    }
}
if ( !function_exists( 'get_molongui_id_from_filepath' ) )
{
    function get_molongui_id_from_filepath( $filepath )
    {
        if ( !isset( $filepath ) ) return false;
        $plugin_id = explode( '/', $filepath );
        $plugin_id = strtolower( strtr( $plugin_id[0], array( 'molongui-' => '', ' ' => '_', '-' => '_' ) ) );
        if ( $plugin_id == "bump_offer" ) $plugin_id = "order_bump";
        return $plugin_id;
    }
}
if ( !function_exists( 'get_molongui_plugins' ) )
{
    function get_molongui_plugins( $field = 'all' )
    {
        if ( !function_exists( 'get_plugins' ) ) require_once ABSPATH . 'wp-admin/includes/plugin.php';
        $plugins = get_plugins();
        if ( version_compare( PHP_VERSION, '5.6.0', '<' ) )
        {
            foreach ( $plugins as $plugin_file => $plugin )
            {
                if ( $plugin['Author'] == 'Amitzy' )
                {
                    $molongui_plugins[$plugin_file] = $plugin;
                    $molongui_plugins[$plugin_file]['id'] = \get_molongui_id_from_filepath( $plugin_file );
                }
            }
        }
        else
        {
            $molongui_plugins = array_filter( $plugins, function( $value, $key )
            {
                return ( $value['Author'] == 'Amitzy' );
            }, ARRAY_FILTER_USE_BOTH);
        }
        if ( $field != 'all' )
        {
            if ( $field == 'keys' ) return array_keys( $molongui_plugins );

            $data = array();
            foreach ( $molongui_plugins as $plugin_file => $plugin )
            {
                $data[$plugin_file] = $plugin[$field];
            }
            $molongui_plugins = $data;
        }
        return $molongui_plugins;
    }
}
if ( !function_exists( 'molongui_rand' ) )
{
    function molongui_rand()
    {
        return substr( number_format( time() * mt_rand(), 0, '', '' ), 0, 10 );
    }
}
if ( !function_exists( 'molongui_enqueue_tiptip' ) )
{
	function molongui_enqueue_tiptip()
    {
	    if ( !wp_script_is( 'molongui-tiptip', 'enqueued' ) )
	    {
		    $version = '1.3';
            $tiptip_js_url  = plugins_url( '/', plugin_dir_path( __FILE__ ) ).'admin/js/tipTip.min.js';
            $tiptip_css_url = plugins_url( '/', plugin_dir_path( __FILE__ ) ).'admin/css/tipTip.min.css';
            if ( is_rtl() ) $tiptip_css_url = plugins_url( '/', plugin_dir_path( __FILE__ ) ).'admin/css/tipTip-rtl.min.css';
            wp_enqueue_script( 'molongui-tiptip', $tiptip_js_url, array( 'jquery' ), $version, true );
            wp_enqueue_style( 'molongui-tiptip', $tiptip_css_url, array(), $version, 'all' );
		    wp_add_inline_script( 'molongui-tiptip',
                "
                    jQuery(document).ready(function($)
                    {
                        var tiptip_args =
                        {
                            attribute:       'data-tip',
                            defaultPosition: 'top',
                            fadeIn:           50,
                            fadeOut:          50,
                            delay:            100,
                        };
                        $( '.tips, .help_tip, .molongui-tip, .m-help-tip, .m-tiptip' ).tipTip( tiptip_args );
                    });
                "
            );
        }
    }
}
if ( !function_exists( 'molongui_enqueue_sweetalert' ) )
{
	function molongui_enqueue_sweetalert()
    {
	    if ( !wp_script_is( 'molongui-sweetalert', 'enqueued' ) )
	    {
		    $version = '2.1.2';
		    $sweetalert_js_url = 'https://cdn.jsdelivr.net/npm/sweetalert@'.$version.'/dist/sweetalert.min.js';
		    wp_enqueue_script( 'molongui-sweetalert', $sweetalert_js_url, array( 'jquery' ), $version, true );
		    wp_add_inline_script( 'molongui-sweetalert', 'var molongui_swal = swal;' );
	    }
    }
}
if ( !function_exists( 'molongui_enqueue_selectr' ) )
{
	function molongui_enqueue_selectr()
    {
	    if ( !wp_script_is( 'molongui-selectr', 'enqueued' ) )
	    {
		    $version = '2.4.13';
		    $selectr_js_url  = 'https://cdn.jsdelivr.net/npm/mobius1-selectr@'.$version.'/dist/selectr.min.js';
		    $selectr_css_url = 'https://cdn.jsdelivr.net/npm/mobius1-selectr@'.$version.'/dist/selectr.min.css';
		    wp_enqueue_script( 'molongui-selectr', $selectr_js_url, array(), $version, true );
		    wp_enqueue_style( 'molongui-selectr', $selectr_css_url, array(), $version, 'all' );
		    wp_add_inline_script( 'molongui-selectr', 'var MolonguiSelectr = Selectr; Selectr = undefined;' );
	    }
    }
}
if ( !function_exists( 'molongui_enqueue_select2' ) )
{
	function molongui_enqueue_select2()
    {
	    if ( !wp_script_is( 'molongui-select2', 'enqueued' ) )
	    {
		    $version = '4.0.5';
		    $select2_js_url  = 'https://cdnjs.cloudflare.com/ajax/libs/select2/'.$version.'/js/select2.min.js';
		    $select2_css_url = 'https://cdnjs.cloudflare.com/ajax/libs/select2/'.$version.'/css/select2.min.css';
		    wp_enqueue_script( 'molongui-select2', $select2_js_url, array(), $version, true );
		    wp_enqueue_style( 'molongui-select2', $select2_css_url, array(), $version, 'all' );
		    wp_add_inline_script( 'molongui-select2',
                'var molongui_select2 = jQuery.fn.select2; delete jQuery.fn.select2;' .
                "
                    jQuery(document).ready(function($)
                    {
                        if ( typeof molongui_select2 !== 'undefined' )
                        {
                            molongui_select2.call( $('#molongui-settings select'), { dropdownAutoWidth: true, minimumResultsForSearch: Infinity } );
                            molongui_select2.call( $('.molongui-metabox select'),  { dropdownAutoWidth: true, minimumResultsForSearch: Infinity } );
                            molongui_select2.call( $('#molongui-settings select.searchable'), { dropdownAutoWidth: true } );
                            molongui_select2.call( $('.molongui-metabox select.searchable'),  { dropdownAutoWidth: true } );
                            molongui_select2.call( $('#molongui-settings select.multiple'), { dropdownAutoWidth: true, multiple: true, allowClear: true } );
                            molongui_select2.call( $('.molongui-metabox select.multiple'),  { dropdownAutoWidth: true, multiple: true, allowClear: true } );
                        }
                    });
                "
            );
	    }
    }
}
if ( !function_exists( 'molongui_enqueue_sortable' ) )
{
	function molongui_enqueue_sortable()
    {
        $version = '1.10.2';
	    $sortable_js_url = 'https://cdn.jsdelivr.net/npm/sortablejs@'.$version.'/Sortable.min.js';
	    wp_enqueue_script( 'molongui-sortable', $sortable_js_url, array( 'jquery' ), $version, true );
    }
}
if ( !function_exists( 'molongui_register_element_queries' ) )
{
	function molongui_register_element_queries()
    {
	    $version = '1.2.2';
	    $resizesensor_js_url   = 'https://cdn.jsdelivr.net/npm/css-element-queries@'.$version.'/src/ResizeSensor.min.js';
	    $elementqueries_js_url = 'https://cdn.jsdelivr.net/npm/css-element-queries@'.$version.'/src/ElementQueries.min.js';
        wp_register_script( 'molongui-resizesensor',   $resizesensor_js_url,   array( 'jquery' ), $version, true );
        wp_register_script( 'molongui-elementqueries', $elementqueries_js_url, array( 'jquery' ), $version, true );
    }
}
if ( !function_exists( 'molongui_enqueue_element_queries' ) )
{
	function molongui_enqueue_element_queries()
    {
        wp_enqueue_script( 'molongui-resizesensor'   );
        wp_enqueue_script( 'molongui-elementqueries' );
    }
}
if ( !function_exists( 'molongui_register_semantic_ui_dropdown' ) )
{
	function molongui_register_semantic_ui_dropdown()
    {
	    $version = '2.4.1';
	    $dropdown_js_url  = 'https://cdn.jsdelivr.net/npm/semantic-ui-dropdown@'.$version.'/dropdown.min.js';
	    $dropdown_css_url = 'https://cdn.jsdelivr.net/npm/semantic-ui-dropdown@'.$version.'/dropdown.min.css';
        wp_register_script( 'molongui-dropdown', $dropdown_js_url , array( 'jquery' ), $version, true );
        wp_register_style( 'molongui-dropdown' , $dropdown_css_url, array(), $version, 'all' );
    }
}
if ( !function_exists( 'molongui_enqueue_semantic_ui_dropdown' ) )
{
	function molongui_enqueue_semantic_ui_dropdown()
    {
        wp_enqueue_script( 'molongui-dropdown' );
        wp_enqueue_style( 'molongui-dropdown'  );
    }
}
if ( !function_exists( 'molongui_register_semantic_ui_transition' ) )
{
	function molongui_register_semantic_ui_transition()
    {
	    $version = '2.3.1';
        $transition_js_url  = 'https://cdn.jsdelivr.net/npm/semantic-ui-transition@'.$version.'/transition.min.js';
        $transition_css_url = 'https://cdn.jsdelivr.net/npm/semantic-ui-transition@'.$version.'/transition.min.css';
        wp_register_script( 'molongui-transition', $transition_js_url , array( 'jquery' ), $version, true );
        wp_register_style( 'molongui-transition' , $transition_css_url, array(), $version, 'all' );
    }
}
if ( !function_exists( 'molongui_enqueue_semantic_ui_transition' ) )
{
	function molongui_enqueue_semantic_ui_transition()
    {
        wp_enqueue_script( 'molongui-transition' );
        wp_enqueue_style( 'molongui-transition'  );
    }
}
if ( !function_exists( 'molongui_register_semantic_ui_icon' ) )
{
	function molongui_register_semantic_ui_icon()
    {
	    $version = '2.3.3';
        $transition_css_url = 'https://cdn.jsdelivr.net/npm/semantic-ui-icon@'.$version.'/icon.min.css';
        wp_register_style( 'molongui-icon', $transition_css_url, array(), $version, 'all' );
    }
}
if ( !function_exists( 'molongui_enqueue_semantic_ui_icon' ) )
{
	function molongui_enqueue_semantic_ui_icon()
    {
        wp_enqueue_style( 'molongui-icon' );
    }
}
if ( !function_exists( 'molongui_register_semantic_ui_label' ) )
{
	function molongui_register_semantic_ui_label()
    {
	    $version = '2.3.2';
        $label_css_url = 'https://cdn.jsdelivr.net/npm/semantic-ui-label@'.$version.'/label.min.css';
        wp_register_style( 'molongui-label', $label_css_url, array(), $version, 'all' );
    }
}
if ( !function_exists( 'molongui_enqueue_semantic_ui_label' ) )
{
	function molongui_enqueue_semantic_ui_label()
    {
        wp_enqueue_style( 'molongui-label' );
    }
}
if ( !function_exists( 'molongui_register_semantic_ui_popup' ) )
{
	function molongui_register_semantic_ui_popup()
    {
	    $version = '2.3.1';
	    $popup_js_url  = 'https://cdn.jsdelivr.net/npm/semantic-ui-popup@'.$version.'/popup.min.js';
	    $popup_css_url = 'https://cdn.jsdelivr.net/npm/semantic-ui-popup@'.$version.'/popup.min.css';
        wp_register_script( 'molongui-popup', $popup_js_url , array( 'jquery' ), $version, true );
        wp_register_style( 'molongui-popup' , $popup_css_url, array(), $version, 'all' );
    }
}
if ( !function_exists( 'molongui_enqueue_semantic_ui_popup' ) )
{
	function molongui_enqueue_semantic_ui_popup()
    {
        wp_enqueue_script( 'molongui-popup' );
        wp_enqueue_style( 'molongui-popup'  );
    }
}
if ( !function_exists( 'molongui_get_language' ) )
{
    function molongui_get_language()
    {
        $language = '';
        if ( false )
        {

        }
        elseif ( function_exists( 'pll_current_language' ) )
        {
            $language = pll_current_language();
        }
        elseif ( defined( 'ICL_LANGUAGE_CODE' ) )
        {
            $language = ICL_LANGUAGE_CODE;
        }
        elseif ( has_filter( 'wpml_current_language' ) )
        {
            $language = apply_filters( 'wpml_current_language', NULL );
        }
        elseif ( array_key_exists( 'TRP_LANGUAGE', $GLOBALS ) )
        {
            $language = $GLOBALS['TRP_LANGUAGE'];
        }
        elseif ( function_exists( 'qtrans_getLanguage' ) )
        {
            $language = qtrans_getLanguage();
        }
        elseif ( array_key_exists( 'q_config', $GLOBALS ) )
        {
            $language = $GLOBALS['q_config']['language'];
        }
        elseif ( function_exists( 'weglot_get_current_language' ) )
        {
            $language = weglot_get_current_language();
        }
        elseif ( has_filter( 'mlp_language_api' ) )
        {
            $language = apply_filters( 'mlp_language_api', NULL );
        }

        return $language;
    }
}
if ( !function_exists( 'molongui_get_image_sizes' ) )
{
    function molongui_get_image_sizes( $type = 'all' )
    {
        $image_sizes = array();
        $type = in_array( $type, array( 'all', 'default', 'additional' ) ) ? $type : 'all';
        if ( in_array( $type, array( 'all', 'default' ) ) )
        {
            $default_image_sizes = get_intermediate_image_sizes();

            foreach ( $default_image_sizes as $size )
            {
                $image_sizes[ $size ][ 'width' ]  = intval( get_option( "{$size}_size_w" ) );
                $image_sizes[ $size ][ 'height' ] = intval( get_option( "{$size}_size_h" ) );
                $image_sizes[ $size ][ 'crop' ]   = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
            }
        }
        if ( in_array( $type, array( 'all', 'additional' ) ) )
        {
            global $_wp_additional_image_sizes;

            if ( isset( $_wp_additional_image_sizes ) and count( $_wp_additional_image_sizes ) )
            {
                $image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
            }
        }
        return $image_sizes;
    }
}