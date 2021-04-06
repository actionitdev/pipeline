<?php
defined( 'ABSPATH' ) or exit;
if ( !function_exists( 'molongui_init_plugin' ) )
{
    function molongui_init_plugin( $id, $plugin )
    {
        return !empty( $plugin ) ? $plugin : molongui_get_plugin( $id );
    }
}
if ( !function_exists( 'molongui_is_premium' ) )
{
    function molongui_is_premium( $plugin_dir )
    {
        $path = $plugin_dir.'premium';

        if ( file_exists( $path ) ) return true;

        return false;
    }
}
if ( !function_exists( 'molongui_has_upgrade' ) )
{
    function molongui_has_upgrade()
    {
        return molongui_is_premium( $plugin->dir ) ? false : constant( $prefix . "UPGRADABLE" );
    }
}
if ( !function_exists( 'molongui_display_badge' ) )
{
    function molongui_display_badge( $label, $is_premium, $href = '', $echo = true )
    {
        $badge  = '';
        $badge .= '<span class="molongui-badge ' . ( $is_premium ? '' : 'molongui-badge-premium' ) . '">';
        $badge .= ( !empty( $href ) ? '<a href="'.$href.'" target="_blank">' : '' );
        $badge .= $label;
        $badge .= ( !empty( $href ) ? '</a>' : '' );
        $badge .= '</span>';

        if ( $echo ) echo $badge;
        else return $badge;
    }
}
if ( !function_exists( 'molongui_get_our_plugins' ) )
{
    function molongui_get_our_plugins( $plugins = array() )
    {
        $molonguis = array();
        $plugin    = new StdClass();
        if ( empty( $plugins ) ) $plugins = get_plugins();
        foreach ( $plugins as $filepath => $data )
        {
            if ( $data['Author'] == 'Amitzy' )
            {
                $plugin_id = explode( '/', $filepath );
                $plugin_id = substr( $plugin_id[1], 0 , -4 );
                $plugin_id = str_replace( 'molongui-', '' , $plugin_id );
                $plugin_id = str_replace( '-', '_' , $plugin_id );
                if ( !is_plugin_active( $filepath ) ) continue;
                if ( $plugin_id == "bump_offer" ) $plugin_id = "order_bump";
                $plugin = molongui_get_plugin( $plugin_id );
                $data['id']          = $plugin_id;
                $data['is_premium']  = $plugin->is_pro;
                $data['has_upgrade'] = $plugin->has_upgrade;
                $data['fw_name']     = molongui_get_constant( $plugin_id, "NAME", true );
                $data['fw_version']  = molongui_get_constant( $plugin_id, "VERSION", true );
                $data['db_version']  = $plugin->db_version;
                $molonguis[$filepath] = $data;
            }
        }
        return $molonguis;
    }
}