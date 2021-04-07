<?php

namespace Molongui\Authorship\Fw\Admin;

use Molongui\Authorship\Fw\Includes\Loader;
use Molongui\Authorship\Fw\Includes\Options;
use Molongui\Authorship\Fw\Includes\Debug;
use Molongui\Authorship\Fw\Includes\Notice;
defined( 'ABSPATH' ) or exit;
if ( !class_exists( 'Molongui\Authorship\Fw\Admin\Admin' ) )
{
	class Admin
	{
		protected $plugin;
		private $loader;
		private $classes;
		public function __construct( $plugin_id )
		{
            $this->plugin         = molongui_get_plugin( $plugin_id );
            $this->plugin->config = require $this->plugin->dir . 'config/config.php';
            $this->loader = Loader::get_instance();
            if ( $this->plugin->is_pro )
            {
                $file = molongui_get_constant( $this->plugin->id . ' Pro', 'DIR', false ) . 'includes/fw/fw-options-functions.php';
                if ( \file_exists( $file ) ) require $file;
            }
            $this->classes['notice'] = new Notice();
            $this->classes['debug']  = new Debug();
            $this->classes['menu']   = new Options( $this->plugin );
			$this->hook();
		}
		private function hook()
		{
            $this->loader->add_filter( 'admin_body_class', $this, 'add_body_class' );
            $this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_styles'  );
            $this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_scripts' );
			$this->loader->add_filter( 'plugin_action_links_'.$this->plugin->basename, $this, 'add_action_links' );
			$this->loader->add_filter( 'admin_footer_text', $this, 'admin_footer_text', 999 );
			if ( get_transient( $this->plugin->name . '-activated' ) ) $this->loader->add_action( 'admin_notices', $this, 'display_install_notice', 999 );
			if ( get_transient( $this->plugin->name . '-updated' ) ) $this->loader->add_action( 'admin_notices', $this, 'display_whatsnew_notice', 999 );
			$this->loader->add_filter( 'upgrader_post_install', $this, 'reset_whatsnew_notice', 10, 3 );
			$this->loader->add_action( 'wp_ajax_send_support_report', $this->classes['debug'], 'send_report' );
			$this->loader->add_action( 'wp_ajax_molongui_send_mail', $this, 'send_mail' );
			$this->loader->add_action( 'wp_ajax_dismiss_notice', $this->classes['notice'], 'dismiss_notice' );
		}
		public function enqueue_styles()
		{
			$screen = get_current_screen();
			$fw_dir     = molongui_get_constant( $this->plugin->id, 'DIR', true );
			$fw_url     = molongui_get_constant( $this->plugin->id, 'URL', true );
			$fw_version = molongui_get_constant( $this->plugin->id, 'VERSION', true );
            $cpts = array();
            foreach ( $this->plugin->config['cpt'] as $cpt ) $cpts[] = $cpt;
            $post_types = molongui_supported_post_types( $this->plugin->id, 'all' );
			$post_types = array_merge( $post_types, $cpts );
			foreach ( $post_types as $post_type ) $post_types[] = 'edit-'.$post_type;
			if ( in_array( $screen->id, array_merge( $post_types,
													 array( 'dashboard', 'update-core', 'plugins',
			                                                'toplevel_page_molongui', 'molongui_page_molongui-support',
			                                                'molongui_page_' . $this->plugin->name ) )
				) )
			{
				$file = 'admin/css/molongui-fw-common.6c6f.min.css';
                if ( is_rtl() ) $file = 'admin/css/molongui-fw-common-rtl.adca.min.css';
				if ( file_exists( $fw_dir.$file ) ) wp_enqueue_style( 'molongui-fw-notices', $fw_url.$file, array(), $fw_version, 'all' );
			}
            if ( $this->check_screen( $screen ) )
            {
                $file = 'admin/css/molongui-fw-pages.b551.min.css';
                if ( is_rtl() ) $file = 'admin/css/molongui-fw-pages-rtl.fbfb.min.css';
                if ( file_exists( $fw_dir.$file ) ) wp_enqueue_style( 'molongui-fw-pages', $fw_url.$file, array(), $fw_version, 'all' );
                $onthefly_css = $this->generate_on_the_fly_css();
                if ( !empty( $onthefly_css ) ) wp_add_inline_style( 'molongui-fw-pages', $onthefly_css );
            }
		}
        private function generate_on_the_fly_css()
        {
            $css = $scheme = '';
            global $_wp_admin_css_colors;
            if ( $_wp_admin_css_colors )
            {
                $admin_color = get_user_option('admin_color');
                $admin_color = empty( $admin_color ) ? 'fresh' : $admin_color;
                $colors      = $_wp_admin_css_colors[$admin_color]->colors;

                foreach ( $colors as $key => $color )
                {
                    $scheme .= '--m-admin-color-' . $key . ':' . $color . ';';
                }
                $css .= ":root{ " . $scheme . " }";
            }
            return $css;
        }
		public function enqueue_scripts( $hook )
		{
			$screen = get_current_screen();
			$post_types = molongui_supported_post_types( $this->plugin->id, 'all' );
			$post_types = array_merge( $post_types, $this->plugin->config['cpt'] );
			foreach ( $post_types as $post_type ) $post_types[] = 'edit-'.$post_type;
			if ( in_array( $screen->id, array_merge( $post_types,                                                                                       // Post types where plugin functionality is extended to.
													 array( 'dashboard', 'update-core', 'plugins',                                                      // WP admin screens.
													        'toplevel_page_molongui', 'molongui_page_molongui-support', // Molongui common screens.
													        'molongui_page_' . $this->plugin->name ) )                                                  // Plugin settings page.
			) )
			{
				molongui_enqueue_sweetalert();
			}
			if ( $screen->id == 'molongui_page_' . $this->plugin->name )
			{
			}
			elseif ( $screen->id == 'molongui_page_molongui-support' )
			{
				$file = 'admin/js/mcf-support.7a0d.min.js';
				if ( file_exists( molongui_get_constant( $this->plugin->id, 'DIR', true ).$file ) ) wp_enqueue_script( 'molongui-license', molongui_get_constant( $this->plugin->id, 'URL', true ).$file, array( 'jquery' ), molongui_get_constant( $this->plugin->id, 'VERSION', true ), true );
			}
			$file = 'admin/js/mcf-common.f4f8.min.js';
			if ( file_exists( molongui_get_constant( $this->plugin->id, 'DIR', true ).$file ) )
			{
				wp_enqueue_script( 'molongui-authorship', molongui_get_constant( $this->plugin->id, 'URL', true ).$file, array( 'jquery' ), molongui_get_constant( $this->plugin->id, 'VERSION', true ), true );
				wp_localize_script( 'molongui-authorship', 'molongui_fw_params', array
				(
					'ajax_nonce' => wp_create_nonce( 'molongui-ajax-nonce' ),
				));
			}
		}
		public function add_action_links( $links )
		{
			$fw_config = include molongui_get_constant( $this->plugin->id, 'DIR', true ) . 'config/config.php';

			$more_links = array
			(
				'settings' => '<a href="' . admin_url( $fw_config['menu']['slug'] . $this->plugin->name ) . '">' . __( 'Settings' ) . '</a>',
				'docs'     => '<a href="' . molongui_get_constant( $this->plugin->id, 'URL_DOCS', true ) . '" target="blank" >' . __( 'Docs', 'molongui-authorship' ) . '</a>'
			);

            if ( apply_filters( $this->plugin->id.'/admin/action_links/add_go_pro', true ) )
            {
                $more_links['gopro'] = '<a href="' . $this->plugin->web . '/" target="blank" style="font-weight:bold;color:orange">' . __( 'Go Pro', 'molongui-authorship' ) . '</a>';
            }

			return array_merge( $more_links, $links );
		}
		public function admin_footer_text( $footer_text )
		{
			global $current_screen;
			$common_fw_pages = array( 'toplevel_page_molongui', 'molongui_page_molongui-support' );
			if ( in_array( $current_screen->id, $common_fw_pages ) )
			{
				return ( sprintf( __( 'Molongui is a trademark of %1$s Amitzy%2$s.', 'molongui-authorship' ),
					             '<a href="https://www.amitzy.com" target="_blank" class="molongui-admin-footer-link">',
					             '</a>' )
				);
			}
			if ( $current_screen->id == 'molongui_page_' . $this->plugin->name )
			{
				return ( sprintf( __( 'If you like <strong>%s</strong> please leave us a %s&#9733;&#9733;&#9733;&#9733;&#9733;%s rating. A huge thank you in advance!', 'molongui-authorship' ),
								  $this->plugin->title,
								  '<a href="https://wordpress.org/support/view/plugin-reviews/'.$this->plugin->name.'?filter=5#postform" target="_blank" class="molongui-admin-footer-link" data-rated="' . esc_attr__( 'Thanks :)', 'molongui-authorship' ) . '">',
								  '</a>' )
				);
			}
			return $footer_text;
		}
		public function display_install_notice()
		{
			$n_content = array();
			$plugin_function = "highlights_plugin";
			$class_name = '\Molongui\\'.$this->plugin->namespace.'\Includes\Highlights';
			if ( method_exists( $class_name, $plugin_function ) )
			{
				$plugin_class = new $class_name();
				$n_content    = $plugin_class->{$plugin_function}();
			}
			if ( empty( $n_content ) ) return;
			$n_slug = 'install';
			$notice = array
			(
				'id'          => $n_slug.'-notice-dismissal',
				'type'        => 'success',
				'content'     => $n_content,
				'dismissible' => $this->plugin->config['notices'][$n_slug]['dismissible'],
				'dismissal'   => $this->plugin->config['notices'][$n_slug]['dismissal'],
				'class'       => 'molongui-notice-activated',
				'pages'       => array
				(
					'dashboard' => 'dashboard',
					'updates'   => 'update-core',
					'plugins'   => 'plugins',
					'plugin'    => 'molongui_page_' . $this->plugin->name,
				),
			);
			Notice::display( $notice['id'], $notice['type'], $notice['content'], $notice['dismissible'], $notice['dismissal'], $notice['class'], $notice['pages'], $this->plugin->id );
		}
		public function display_whatsnew_notice()
		{
			$n_content = array();
			$current_release = str_replace('.', '', $this->plugin->version );
			$plugin_function = "highlights_release_{$current_release}";
			$class_name = '\Molongui\\'.$this->plugin->namespace.'\Includes\Highlights';
			if ( method_exists( $class_name, $plugin_function ) )
			{
				$plugin_class = new $class_name();
				$n_content    = $plugin_class->{$plugin_function}();
			}
			if ( empty( $n_content ) ) return;
			$n_slug = 'whatsnew';
			$notice = array
			(
				'id'          => $n_slug.'-notice-dismissal',
				'type'        => 'success',
				'content'     => $n_content,
				'dismissible' => $this->plugin->config['notices'][$n_slug]['dismissible'],
				'dismissal'   => $this->plugin->config['notices'][$n_slug]['dismissal'],
				'class'       => 'molongui-notice-whatsnew',
				'pages'       => array
				(
					'dashboard' => 'dashboard',
					'updates'   => 'update-core',
					'plugins'   => 'plugins',
					'plugin'    => 'molongui_page_' . $this->plugin->name,
				),
			);
			Notice::display( $notice['id'], $notice['type'], $notice['content'], $notice['dismissible'], $notice['dismissal'], $notice['class'], $notice['pages'], $this->plugin->id );
		}
		public function reset_whatsnew_notice( $response, $hook_extra, $result )
		{
			if ( isset( $hook_extra['plugin'] ) and $hook_extra['plugin'] != $this->plugin->basename ) return $result;
			delete_transient( $this->plugin->name . '-activated' );
			set_transient( $this->plugin->name . '-updated', 1 );
			$key = molongui_get_constant( $this->plugin->id, 'NOTICES', false );
			$notices = get_option( $key );
			unset( $notices['whatsnew-notice-dismissal'] );
			update_option( $key, $notices );
			return $result;
		}
		public function display_upgrade_notice()
		{
			$n_slug = 'upgrade';
			$notice = array
			(
				'id'          => $n_slug.'-notice-dismissal',
				'type'        => 'info',
				'content'     => array
				(
					'image'   => '',
					'icon'    => '',
					'title'   => sprintf( __( "%sMore features? It's time to upgrade your %s plugin to Premium vesion!%s", 'molongui-authorship' ),
									'<a href="'.$this->plugin->web.'" target="_blank" >',
									$this->plugin->title,
									'</a>' ),
					'message' => __( 'Extend standard plugin functionality with new great options.', 'molongui-authorship' ),
					'buttons' => array(),
					'button'  => array
					(
						'id'     => '',
						'href'   => $this->plugin->web,
						'target' => '_blank',
						'class'  => '',
						'icon'   => '',
						'label'  => __( 'Learn more', 'molongui-authorship' ),
					),
				),
				'dismissible' => $this->plugin->config['notices'][$n_slug]['dismissible'],
				'dismissal'   => $this->plugin->config['notices'][$n_slug]['dismissal'],
				'class'       => 'molongui-notice-orange molongui-notice-icon-star',
				'pages'       => array
				(
					'dashboard' => 'dashboard',
					'updates'   => 'update-core',
					'plugins'   => 'plugins',
					'plugin'    => 'molongui_page_'.$this->plugin->name,
				),
			);
			if ( !empty( $this->plugin->config['cpt'] ) )
			{
				foreach ( $this->plugin->config['cpt'] as $cpt_name => $cpt_id )
				{
					$notice['pages'][$cpt_name]         = $cpt_id;
					$notice['pages']['edit-'.$cpt_name] = 'edit-'.$cpt_id;
				}
			}
			Notice::display( $notice['id'], $notice['type'], $notice['content'], $notice['dismissible'], $notice['dismissal'], $notice['class'], $notice['pages'], $this->plugin->id );
		}
		public function display_rate_notice()
		{
            $installation = get_option( molongui_get_constant( $this->plugin->id, 'INSTALLATION', false ) );

			if ( empty( $installation ) or !isset( $installation['installation_date'] ) )
			{
				$installation = array
				(
					'installation_date'    => time(),
					'installation_version' => $this->plugin->version,
				);

				update_option( molongui_get_constant( $this->plugin->id, 'INSTALLATION', false ), $installation );
				return;
			}
			else
			{
				$installation_date = $installation['installation_date'];
			}
			$threshold_date = strtotime( '+'.$this->plugin->config['notices']['rate']['trigger'].' days', $installation_date );
			if ( !empty( $installation_date ) and ( time() <= $threshold_date ) ) return;
			global $current_user;
			$n_slug = 'rate';
			$notice = array
			(
				'id'          => $n_slug.'-notice-dismissal',
				'type'        => 'info',
				'content'     => array
				(
					'image'   => '',
					'icon'    => '',
					'title'   => sprintf( __( "Like %s?", 'molongui-authorship' ), $this->plugin->title ),
					'message' => sprintf( __( "Hey %s, hope you're happy with %s plugin. We would really appreciate it if you dropped us a quick rating!", 'molongui-authorship' ),
										  $current_user->display_name,
						                  $this->plugin->title ),
					'buttons' => array(),
					'button'  => array
					(
						'id'     => $this->plugin->name.'-rate-button',
						'href'   => 'https://wordpress.org/support/plugin/'.$this->plugin->name.'/reviews/?filter=5#new-post',
						'target' => '_blank',
						'class'  => 'molongui-notice-rate-button',
						'icon'   => '',
						'label'  => __( 'Rate plugin', 'molongui-authorship' ),
					),
				),
				'dismissible' => $this->plugin->config['notices'][$n_slug]['dismissible'],
				'dismissal'   => $this->plugin->config['notices'][$n_slug]['dismissal'],
				'class'       => 'molongui-notice-blue molongui-notice-icon-heart',
				'pages'       => array
				(
					'dashboard' => 'dashboard',
					'updates'   => 'update-core',
					'plugins'   => 'plugins',
					'plugin'    => 'molongui_page_'.$this->plugin->name,
				),
			);
			if ( !empty( $this->plugin->config['cpt'] ) )
			{
				foreach ( $this->plugin->config['cpt'] as $cpt_name => $cpt_id )
				{
					$notice['pages'][$cpt_name]         = $cpt_id;
					$notice['pages']['edit-'.$cpt_name] = 'edit-'.$cpt_id;
				}
			}
			Notice::display( $notice['id'], $notice['type'], $notice['content'], $notice['dismissible'], $notice['dismissal'], $notice['class'], $notice['pages'], $this->plugin->id );
		}
public function premium_setting_tip( $type = 'full', $default = '' )
		{
			switch ( $type )
			{
				case 'full':
					$tip = sprintf( __( '%sPremium setting%s. You are using the free version of this plugin, so changing this setting will have no effect and default value will be used. Consider purchasing the %sPremium Version%s.', 'molongui-authorship' ),
						'<strong>',
						'</strong>',
						'<a href="'.$this->plugin->web.'" target="_blank">',
						'</a>' );
					break;

				case 'part':
					$tip = sprintf( __( '%sPremium setting%s. You are using the free version of this plugin, so selecting any option marked as "PREMIUM" will have no effect and default value will be used. Consider purchasing the %sPremium Version%s.', 'molongui-authorship' ),
						'<strong>',
						'</strong>',
						'<a href="'.$this->plugin->web.'" target="_blank">',
						'</a>' );
					break;

				default:
					$tip = '';
					break;
			}

			return $tip;
		}
		public function send_mail()
		{
			check_ajax_referer( 'molongui-ajax-nonce', 'security', true );
			if ( !is_admin() and !isset( $_POST['form'] ) and $_POST['type'] == 'ticket' )
			{
				echo 'error';
				wp_die();
			}
            switch( $_POST['type'] )
            {
                case 'ticket':
                    $params = array();
                    parse_str( $_POST['form'], $params );
                    $subject = sprintf( __( "Support Ticket %s: %s", 'molongui-authorship' ), sanitize_text_field( $params['ticket-id'] ), sanitize_text_field( $params['your-subject'] ) );
                    $message = esc_html( $params['your-message'] );
                    $headers = array
                    (
                        'From: '         . $params['your-name'] . ' <' . $params['your-email'] . '>',
                        'Reply-To: '     . $params['your-name'] . ' <' . $params['your-email'] . '>',
                        'Content-Type: ' . 'text/html; charset=UTF-8',
                    );
                    $message .= '<br><br>---<br><br>';
                    $message .= '<small>'.sprintf( __( "This support ticket was sent using form at the Support Page (%s)", 'molongui-authorship' ), $_POST['domain'] ).'</small>';
                    $message .= '<br><br><hr><br><br>';

                    $user = array( 'name' => $params['your-name'], 'mail' => $params['your-email'] );

                break;

                case 'report':
                    $current_user = wp_get_current_user();
                    $subject = sprintf( __( "Support Report for %s", 'molongui-authorship' ), sanitize_text_field( $_POST['domain'] ) );
                    $message = '';
                    $headers = array
                    (
                        'From: ' . $current_user->display_name . ' <' . 'noreply@' . sanitize_text_field( $_POST['domain'] ) . '>',
                        'Reply-To: ' . $current_user->display_name . ' <' . $current_user->user_email . '>',
                        'Content-Type: ' . 'text/html; charset=UTF-8',
                    );

                    $user = array( 'name' => $current_user->user_firstname, 'mail' => $current_user->user_email );

                break;
            }
            $message .= $this->classes['debug']->get_mail_appendix();
			$sent = wp_mail( molongui_get_constant( $this->plugin->id, 'MAIL_SUPPORT', true ), $subject, $message, $headers );
            if ( $sent and !empty( $user ) ) $this->send_autoresponder( $user );
			echo( $sent ? 'sent' : 'error' );
			wp_die();
		}
        public function send_autoresponder( $user )
        {
            $subject = __( "We got your email! Hang tight!", 'molongui-authorship' );
            $message = sprintf( __( "Hi %s! %s This is an automatic email just to let you know we've got your help request . We'll get you an answer back shortly.", 'molongui-authorship' ), $user['name'], '<br><br>' );
            $headers = array
            (
                'From: Molongui Support <support@molongui.com>',
                'Reply-To: Molongui Support <support@molongui.com>',
                'Content-Type: text/html; charset=UTF-8',
            );
            $sent = wp_mail( $user['mail'], $subject, $message, $headers );
            return $sent;
        }
        private function check_screen( $screen = null )
        {
            $pages = array
            (
                'toplevel_page_molongui',             // Molongui Plugins page.
                'molongui_page_molongui-support',     // Molongui Support page.
                'molongui_page_'.$this->plugin->name, // Molongui Plugin Options page.
            );
            if ( is_null( $screen ) ) $screen = get_current_screen();

            return ( in_array( $screen->id, $pages ) );
        }
        public function add_body_class( $classes )
        {
            if ( $this->check_screen() )
            {
                $classes .= ' ';
                $classes .= 'molongui-page';
                $classes .= ' ';
            }

            return $classes;
        }

    } // End of class
} // End if_class_exists