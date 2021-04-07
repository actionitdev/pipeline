<?php

use Molongui\Authorship\Fw\Includes\Dependencies;
use Molongui\Authorship\Includes\Activator;
use Molongui\Authorship\Includes\Deactivator;
use Molongui\Authorship\Includes\Core;
defined( 'ABSPATH' ) or exit;

/*!
 * Plugin Name:       Molongui Authorship
 * Description:       Co-authors, guest authors, author box, local avatar and more. All you need for your authors in just one plugin.
 * Plugin URI:        https://www.molongui.com/authorship/
 * Text Domain:       molongui-authorship
 * Domain Path:       /i18n/
 * Version:           4.2.17
 * Requires at least: 4.5.0
 * Tested up to:      5.7
 * Author:            Molongui
 * Author URI:        https://www.molongui.com/authorship
 * Plugin Base:       _boilerplate 3.0.0
 * License:           GPL v3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * This plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, either version 3 of the License, or any later version.
 *
 * This plugin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this plugin. If not, see
 * http://www.gnu.org/licenses/
 */
if ( !class_exists( 'MolonguiAuthorship' ) )
{
    class MolonguiAuthorship
    {
        private $min_php = '5.3.0';
        function __construct()
        {
	        require __DIR__ . '/config/plugin.php';
	        require __DIR__ . '/fw/config/fw.php';
            if ( $this->fail_php() ) return false;
	        require_once __DIR__ . '/fw/includes/fw-helper-functions.php';
            require_once __DIR__ . '/fw/includes/autoloader.php';
            $deps = new Dependencies( MOLONGUI_AUTHORSHIP_ID );
			if ( !$deps->check() ) return false;
            register_activation_hook(   __FILE__, array( $this, 'activate'   ) );
            register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
	        add_action( 'wpmu_new_blog', array( $this, 'activate_on_new_blog' ), 10, 6 );
            $core = new Core();
            $core->run();
	        return true;
        }
        private function fail_php()
        {
            if ( version_compare( PHP_VERSION, $this->min_php, '<' ) )
            {
                add_action( 'admin_notices', array( $this, 'fail_php_notice' ) );
                return true;
            }
            return false;
        }
        public function fail_php_notice()
        {
            $howto_url = 'https://www.molongui.com/docs/troubleshooting/how-to-update-my-php-version/';

            $message  = '<p>' . sprintf( __( '%s is not working because you are running a PHP version (%s) on your web host older than %s. Please update your PHP version.', 'molongui-authorship' ), MOLONGUI_AUTHORSHIP_NAME, PHP_VERSION, $this->min_php ) . '</p>';
            $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $howto_url, __( 'How to update your PHP version', 'molongui-authorship' ) ) . '</p>';
            echo '<div class="error"><p>' . $message . '</p></div>';
        }
        public function activate( $network_wide )
        {
            Activator::activate( $network_wide );
        }
        public function deactivate( $network_wide )
        {
            Deactivator::deactivate( $network_wide );
        }
	    public function activate_on_new_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta )
	    {
		    Activator::activate_on_new_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta );
	    }

    } // class
} // class_exists
if ( class_exists( 'MolonguiAuthorship' ) )
{
    $plugin = new MolonguiAuthorship();
}