<?php

namespace Molongui\Authorship\Includes;

use Molongui\Authorship\Fw\Includes\Loader;
use Molongui\Authorship\Fw\Includes\DB_Update;
use Molongui\Authorship\Fw\Includes\i18n;
use Molongui\Authorship\Fw\Admin\Admin as Fw;
use Molongui\Authorship\Fw\Customizer\Customizer;
use Molongui\Authorship\Includes\Update_Post_Counters;
use Molongui\Authorship\Admin\Admin;
use Molongui\Authorship\FrontEnd\FrontEnd;
\defined( 'ABSPATH' ) or exit;
class Core
{
    private $loader;
    public function __construct()
    {
        $this->loader = Loader::get_instance();
        require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/functions.php';
        $this->loader->add_action( 'init', $this, 'update_db', 999 );
        $this->hook();
        $this->set_locale();
        $this->loader->add_action( 'plugins_loaded', $this, 'plugin_loaded', -1 );
    }
    private function hook()
    {
        new Update_Post_Counters();
        if ( $this->is_admin_request() )
        {
            new Fw( MOLONGUI_AUTHORSHIP_ID );
            new Admin();
        }
        if ( \molongui_authorship_is_feature_enabled( 'guest' ) )
        {
            new Guest();
        }
        new Post();
        if ( $this->check_license() )
        {
            new FrontEnd();
        }
        if ( \molongui_authorship_is_feature_enabled( 'box' ) and \molongui_authorship_is_feature_enabled( 'box_styles' ) )
        {
            $this->loader->add_action( 'init', $this, 'hook_customizer' );
        }
        $this->loader->add_action( 'after_setup_theme', $this, 'add_image_sizes' );
    }
    private function is_admin_request()
    {
        return ( \molongui_is_request( 'admin' ) or \molongui_is_request( 'ajax' ) );
    }
    private function is_frontend_request()
    {
        return \molongui_is_request( 'frontend' );
    }
    private function is_customizer_request()
    {
        return \molongui_is_request( 'customizer' );
    }
    public function update_db()
    {
        $update_db = new DB_Update( MOLONGUI_AUTHORSHIP_ID, MOLONGUI_AUTHORSHIP_DB );
        if ( $update_db->db_update_needed() ) $update_db->run_update();
    }
    private function check_license()
    {
        if ( MOLONGUI_AUTHORSHIP_IS_PRO ) return \molongui_is_active( MOLONGUI_AUTHORSHIP_PRO_DIR );
        return true;
    }
    public function hook_customizer()
    {
        $config = include MOLONGUI_AUTHORSHIP_DIR . 'config/config.php';

        if ( $config['customizer']['enable'] and $this->is_customizer_request() )
        {
            new Customizer( MOLONGUI_AUTHORSHIP_ID );
        }
    }
    private function set_locale()
    {
        $i18n = new i18n();
        $i18n->set_domain( 'molongui-authorship' );

        $this->loader->add_action( 'init', $i18n, 'load_plugin_textdomain' );
    }
    public function add_image_sizes()
    {
        if ( \apply_filters( 'authorship/add_image_size/skip', false ) ) return;
        \add_theme_support( 'post-thumbnails' );
        \add_image_size( 'authorship-box-avatar', 150, 150, true );
        \add_image_size( 'authorship-box-related', 70, 70, true );
        $settings = \molongui_get_plugin_settings( MOLONGUI_AUTHORSHIP_ID, array( 'box' ) );
        if ( $settings['avatar_width'] != 150 or $settings['avatar_height'] != 150 )
        {
            \add_image_size( 'authorship-custom-avatar', $settings['avatar_width'], $settings['avatar_height'], true );
        }
        \do_action( 'authorship/add_image_size' );
    }
    public function plugin_loaded()
    {
        \do_action( 'authorship/loaded' );
    }
    public function get_plugin_name()
    {
        return MOLONGUI_AUTHORSHIP_ID;
    }
    public function get_plugin_version()
    {
        return MOLONGUI_AUTHORSHIP_VERSION;
    }
    public function get_loader()
    {
        return $this->loader;
    }
    public function run()
    {
        $this->loader->run();
    }
}