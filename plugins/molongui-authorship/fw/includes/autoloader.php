<?php
defined( 'ABSPATH' ) or exit;
spl_autoload_register( 'molongui_authorship_autoload' );
function molongui_authorship_autoload( $class_name )
{
    if ( defined( 'MOLONGUI_AUTHORSHIP_PRO_VERSION' ) and false === strpos( $class_name, 'Molongui\Authorship\\' ) )
    {
        if ( version_compare( MOLONGUI_AUTHORSHIP_PRO_VERSION, '1.1.5', '<' ) )
        {
            if ( false === strpos( $class_name, 'Molongui\\' ) ) return;
            $part  = 1;
            $is_fw = $is_update = $is_pro = false;
            $is_fw = strpos( $class_name, '\Fw\\' ) !== false ? true : false;
            $is_library = strpos( $class_name, '\Libraries\\' ) !== false ? true : false;
            $is_update = strpos( $class_name, '\Update\\' ) !== false ? true : false;
            if ( strpos( $class_name, '\Pro\\' ) !== false )
            {
                $is_pro = true;
                $part   = 2;
            }
            $file_parts = explode( '\\', $class_name );
            $namespace = '';
            for ( $i = count( $file_parts ) - 1; $i > $part; $i-- )
            {
                $current = strtolower( $file_parts[ $i ] );
                $current = str_ireplace( '_', '-', $current );
                $current = $current === 'frontend' ? 'public' : $current;
                if ( count( $file_parts ) - 1 === $i )
                {
                    $file_name = ( ( $is_fw and !$is_library ) ? 'fw-' : '' ).( $is_library ? '' : 'class-' ).$current.'.php';
                }
                else $namespace = '/' . $current . $namespace;
            }
            $filepath  = trailingslashit( dirname( dirname( dirname( __FILE__ ) ) ) . ( ( $is_pro or $is_update ) ? '-pro' : '') . ( ( $is_fw and !$is_update ) ? '/fw' : '') . $namespace );
            $filepath .= $file_name;
            if ( file_exists( $filepath ) )
            {
                require_once $filepath;
            }
            else
            {

                wp_die( esc_html( "The file attempting to be loaded at $filepath does not exist." ) );
            }
            return;
        }
    }
    if ( false === strpos( $class_name, 'Molongui\Authorship\\' ) ) return;
    $part   = 1;
    $is_pro = $is_fw = $is_library = $is_update = false;
    if ( strpos( $class_name, '\Pro\\' ) !== false )
    {
        $is_pro = true;
        $part   = 2;
    }
    if ( strpos( $class_name, '\Fw\\' ) !== false )
    {
        $is_fw = true;
        $part  = 2;
    }
    $is_library = strpos( $class_name, '\Libraries\\' ) !== false ? true : false;
    $is_update = strpos( $class_name, '\Update\\' ) !== false ? true : false;
    $file_parts = explode( '\\', $class_name );
    $namespace = '';
    for ( $i = count( $file_parts ) - 1; $i > $part; $i-- )
    {
        $current = strtolower( $file_parts[ $i ] );
        $current = str_ireplace( '_', '-', $current );
$current = $current === 'frontend' ? 'public' : $current;
        if ( count( $file_parts ) - 1 === $i )
        {
            $file_name = ( ( $is_fw and !$is_library ) ? 'fw-' : '' ).( $is_library ? '' : 'class-' ).$current.'.php';
        }
        else $namespace = '/' . $current . $namespace;
    }
    $filepath  = trailingslashit( dirname( dirname( dirname( __FILE__ ) ) ) . ( ( $is_pro or $is_update ) ? '-pro' : '') . ( ( $is_fw and !$is_update ) ? '/fw' : '') . $namespace );
    $filepath .= $file_name;
    if ( file_exists( $filepath ) )
    {
        require_once $filepath;
    }
    else
    {

        wp_die( esc_html( "The file attempting to be loaded at $filepath does not exist." ) );
    }
}
