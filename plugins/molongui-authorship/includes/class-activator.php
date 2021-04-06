<?php

namespace Molongui\Authorship\Includes;
\defined( 'ABSPATH' ) or exit;
class Activator
{
    public static function activate( $network_wide )
    {
	    if ( \function_exists('is_multisite') and \is_multisite() and $network_wide )
	    {
		    if ( !\is_super_admin() ) return;
		    foreach ( \molongui_get_sites() as $site_id )
		    {
			    \switch_to_blog( $site_id );
			    self::activate_single_blog();
			    \restore_current_blog();
		    }
        }
        else
        {
	        if ( !\current_user_can( 'activate_plugins' ) ) return;

	        self::activate_single_blog();
        }
	    \set_transient( MOLONGUI_AUTHORSHIP_NAME.'-activated', 1 );
    }
	private static function activate_single_blog()
	{
		\flush_rewrite_rules();
        \wp_cache_flush();
		$update_db = new \Molongui\Authorship\Fw\Includes\DB_Update( MOLONGUI_AUTHORSHIP_ID, MOLONGUI_AUTHORSHIP_DB );
		if ( $update_db->db_update_needed() ) $update_db->run_update();
		self::save_installation_data();
		self::add_default_options();
		self::update_post_counters();
	}
	public static function activate_on_new_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta )
	{
		if ( \is_plugin_active_for_network( MOLONGUI_AUTHORSHIP_BASENAME ) )
		{
			\switch_to_blog( $blog_id );
			self::activate_single_blog();
			\restore_current_blog();
		}
	}
	public static function save_installation_data()
	{
		if ( \get_option( MOLONGUI_AUTHORSHIP_INSTALLATION ) ) return;
		$installation = array
		(
			'install_date'    => \time(),
			'install_version' => MOLONGUI_AUTHORSHIP_VERSION,
		);
		\add_option( MOLONGUI_AUTHORSHIP_INSTALLATION, $installation );
	}
    public static function add_default_options()
    {
        \molongui_authorship_add_default_settings();
    }
    public static function update_post_counters()
    {
        if ( \defined( 'DISABLE_WP_CRON' ) and DISABLE_WP_CRON ) return;
        \add_option( 'molongui_authorship_update_post_counters', true );
    }

} // class