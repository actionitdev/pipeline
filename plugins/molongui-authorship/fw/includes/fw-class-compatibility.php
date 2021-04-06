<?php

namespace Molongui\Authorship\Fw\Includes;
\defined( 'ABSPATH' ) or exit;
if ( !\class_exists( 'Molongui\Authorship\Fw\Includes\Compatibility' ) )
{
	class Compatibility
	{
		protected $plugin;
		public function __construct( $plugin_id )
		{
			$this->plugin = \molongui_get_plugin( $plugin_id );
			\add_action( 'admin_init', array( $this, 'check' ) );
		}
		public function check()
		{
			if ( !$this->compatible_version() )
			{
				if ( \is_plugin_active( $this->plugin->basename ) )
				{
					\deactivate_plugins( $this->plugin->basename );
					\add_action( 'admin_notices', array( $this, 'disabled_notice' ), 100 );
					if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
				}
			}
		}
		public function compatible_version()
		{
			$count = 0;
			$us = \explode( "/", $this->plugin->basename );
			$us = $us[1];
			if ( !\function_exists( 'get_plugins' ) ) require_once ABSPATH . 'wp-admin/includes/plugin.php';
			$plugins = \get_plugins();
			foreach ( $plugins as $basename => $plugin )
			{
				$basename = \explode( "/", $basename );
				$basename = ( !empty( $basename[1] ) ? $basename[1] : $basename[0] );
				if ( $basename == $us ) ++$count;
			}
			if ( $count > 1 ) return false;
			return true;
		}
		public function disabled_notice()
		{
			if ( !\current_user_can( 'manage_options' ) ) return;
			$n_slug = 'many-installations';
			$notice = array
			(
				'id'          => $n_slug.'-notice-dismissal',
				'type'        => 'error',
				'content'     => array
				(
					'image'   => '',
					'title'   => $this->plugin->title,
					'message' => \sprintf( __( 'There can be only one instance of %s plugin installed. Please, uninstall all other instances but the one you want to activate.', 'molongui-authorship' ), $this->plugin->title ),
					'buttons' => array(),
					'button'  => array
					(
						'id'     => '',
						'href'   => \admin_url( 'plugins.php' ),
						'target' => '_self',
						'class'  => '',
						'icon'   => '',
						'label'  => __( 'Manage plugins', 'molongui-authorship' ),
					),
				),
				'dismissible' => $this->plugin->config['notices'][$n_slug]['dismissible'],
				'dismissal'   => $this->plugin->config['notices'][$n_slug]['dismissal'],
				'class'       => 'molongui-notice-red molongui-notice-icon-alert',
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
			\molongui_display_notice( $this->plugin->id, $notice );
		}

	} // class
} // class_exists