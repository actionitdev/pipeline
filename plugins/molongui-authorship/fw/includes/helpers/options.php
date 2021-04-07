<?php
defined( 'ABSPATH' ) or exit;
if ( !function_exists( 'molongui_get_options' ) )
{
    function molongui_get_options( $id )
    {
        if ( empty( $id ) ) return false;
        $prefix = molongui_get_constant( $id, "DB_PREFIX" );
        if ( empty( $prefix ) ) return false;
        global $wpdb;
        $ret = array();
        $options = $wpdb->get_results
        (
            $wpdb->prepare( "SELECT option_name,option_value FROM {$wpdb->options} WHERE option_name LIKE %s", $prefix.'%' ),
            ARRAY_A
        );

        if ( !empty( $options ) )
        {
            foreach ( $options as $v ) $ret[$v['option_name']] = maybe_unserialize( $v['option_value'] );
        }
        return ( !empty( $ret ) ) ? $ret : false;
    }
}
if ( !function_exists( 'molongui_export_options' ) )
{
    function molongui_export_options( $id )
    {
        $options = molongui_get_options( $id );
        $prefix = molongui_get_constant( $id, 'DB_PREFIX' );
        unset( $options[$prefix.'license']    );
        unset( $options[$prefix.'instance']   );
        unset( $options[$prefix.'activated']  );
        unset( $options[$prefix.'product_id'] );
        $options['plugin_id']      = molongui_get_constant( $id, "ID" );
        $options['plugin_version'] = molongui_get_constant( $id, "VERSION" );

        return $options;
    }
}
if ( !function_exists( 'molongui_import_options' ) )
{
    function molongui_import_options( $id )
    {
    }
}
if ( !function_exists( 'molongui_reset_options' ) )
{
    function molongui_reset_options( $id )
    {
        $rc = true;
        $prefix   = molongui_get_constant( $id, 'DB_PREFIX' );
        $defaults = call_user_func( $prefix.'get_default_settings' );
        foreach ( $defaults as $option => $value )
        {

            $r = update_option( $prefix.$option, $value );
            if ( !$r )
            {
                if ( $value !== get_option( $prefix.$option ) and maybe_serialize( $value ) !== maybe_serialize( get_option( $prefix.$option ) ) )
                {
                    $rc = false;
                }
            }
        }
        echo $rc;
    }
}
if ( !function_exists( 'molongui_options_merge' ) )
{
    function molongui_options_merge( $prefix, $array, $default )
    {
        $merged = array();
        foreach( $array as $key => $value )
        {
            $default_key = str_replace( $prefix, '', $key );
            if ( is_array( $value ) and !empty( $default[$default_key] ) )
            {
                $merged[$key] = array_merge( $default[$default_key], $array[$key] );
            }
            else
            {
                $merged[$key] = $value;
            }
            unset( $default[$default_key] );
        }
        foreach( $default as $key => $value )
        {
            $merged[$prefix.$key] = $value;
        }
        return $merged;
    }
}