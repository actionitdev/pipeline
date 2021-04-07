<?php
defined( 'WP_UNINSTALL_PLUGIN' ) or exit;
require_once( plugin_dir_path( __FILE__ ) . 'config/plugin.php' );
require_once( plugin_dir_path( __FILE__ ) . 'fw/includes/fw-helper-functions.php' );
if ( function_exists( 'is_multisite' ) and is_multisite() )
{
	foreach ( molongui_get_sites() as $site_id )
	{
		switch_to_blog( $site_id );
		molongui_authorship_uninstall_single_site();
		restore_current_blog();
	}
}
else
{
	molongui_authorship_uninstall_single_site();
}
function molongui_authorship_uninstall_single_site()
{
	global $wpdb;
	$settings = get_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS );
	if ( $settings['keep_config'] == 0 )
	{
		$like = MOLONGUI_AUTHORSHIP_DB_PREFIX.'%';

		$wpdb->query( "DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE '{$like}';" );
	}
	if ( $settings['keep_data'] == 0 )
	{
		$like = MOLONGUI_AUTHORSHIP_DB_PREFIX.'%';
		$ids = $wpdb->get_results
        (
        "SELECT ID
                FROM {$wpdb->prefix}posts
                WHERE post_type LIKE 'guest_author'
                ",
                ARRAY_A
		);
		if ( !empty( $ids ) )
        {
            $postids = '';
            foreach ( $ids as $key => $id )
            {
                if ( $key == 0 ) $postids = $id['ID'];
                else $postids = $postids . ', ' . $id['ID'];
            }
            $wpdb->query( "DELETE FROM {$wpdb->prefix}postmeta WHERE post_id IN ( $postids );" );
            $wpdb->query( "DELETE FROM {$wpdb->prefix}posts WHERE ID IN ( $postids );" );
            $wpdb->query( "DELETE FROM {$wpdb->prefix}postmeta WHERE meta_key = '_molongui_author';" );
            $wpdb->query( "DELETE FROM {$wpdb->prefix}postmeta WHERE meta_key = '_molongui_main_author';" );
            $wpdb->query( "DELETE FROM {$wpdb->prefix}postmeta WHERE meta_key LIKE '%_molongui_guest_author%';" );
            $wpdb->query( "DELETE FROM {$wpdb->prefix}postmeta WHERE meta_key = '_molongui_author_box_display';" );
            $wpdb->query( "DELETE FROM {$wpdb->prefix}postmeta WHERE meta_key = '_molongui_author_box_position';" );
        }
	}
	$like = '_site_transient_'.strtolower( str_replace( ' ', '-', MOLONGUI_AUTHORSHIP_ID ) ).'%';
	$wpdb->query( "DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE '{$like}';" );
}