<?php

namespace Molongui\Authorship\Fw\Includes;
defined( 'ABSPATH' ) or exit;
if ( !class_exists( 'Molongui\Authorship\Fw\Includes\Dependencies' ) )
{
	class Dependencies
	{
		protected $plugin;
		private $error;
		public function __construct( $plugin_id )
		{
            $this->plugin         = molongui_get_plugin( $plugin_id );
            $this->plugin->config = require $this->plugin->dir . 'config/config.php';
		}
		public function check()
		{
			if ( empty( $this->plugin->config['dependencies'] ) ) return true;
			foreach ( $this->plugin->config['dependencies'] as $type => $dependencies )
			{
				if ( empty( $dependencies ) ) continue;

				foreach ( $dependencies as $key => $dependency )
				{
					if ( !in_array( $dependency['basename'], apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
					{
						$this->error['type'] = $type;
						$this->error['name'] = $key;
						add_action( 'admin_notices', array( $this, 'disabled_notice' ), 100 );
						return false;
					}
					else
					{
						$dependency_data = get_file_data( WP_PLUGIN_DIR.'/'.$dependency['basename'], array
						(
							'Version' => 'Version'
						));

						if ( version_compare( $dependency_data['Version'], $dependency['version'], '<' ) )
						{
							$this->error['type']    = $type;
							$this->error['name']    = $key;
							$this->error['version'] = $dependency['version'];
							add_action( 'admin_notices', array( $this, 'version_notice' ), 100 );
							return false;
						}
					}
				}
			}
			return true;
		}
		public function disabled_notice()
		{
			if ( !current_user_can( 'manage_options' ) ) return;
			$n_slug = 'missing-dependency';
			$notice = array
			(
				'id'          => $n_slug.'-notice-dismissal',
				'type'        => 'error',
				'content'     => array
				(
					'image'   => '',
					'title'   => __( 'Plugin disabled', 'molongui-authorship' ),
					'message' => sprintf( __( '%s%s%s has been disabled because %s %s is not active. Please, install/activate %s to enable %s.', 'molongui-authorship' ),
								'<strong>', $this->plugin->title, '</strong>',
									ucwords( $this->error['name'] ), $this->error['type'],
									ucwords( $this->error['name'] ), $this->plugin->title ),
					'buttons' => array(),
					'button'  => array
					(
						'id'     => '',
						'href'   => admin_url( 'plugins.php' ),
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
			molongui_display_notice( $this->plugin->id, $notice );
		}
		public function version_notice()
		{
			$n_slug = 'missing-version';
			$notice = array
			(
				'id'          => $n_slug.'-notice-dismissal',
				'type'        => 'error',
				'content'     => array
				(
					'image'   => '',
					'title'   => __( 'Plugin disabled', 'molongui-authorship' ),
					'message' => sprintf( __( '%s%s%s has been disabled because the minimum required version of the %s %s is %s. Please, update %s to enable %s.', 'molongui-authorship' ),
									'<strong>', $this->plugin->title, '</strong>',
									ucwords( $this->error['name'] ), $this->error['type'], $this->error['version'],
									ucwords( $this->error['name'] ), $this->plugin->title ),
					'buttons' => array(),
					'button'  => array
					(
						'id'     => '',
						'href'   => admin_url( 'plugins.php' ),
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
			molongui_display_notice( $this->plugin->id, $notice );
		}

	} // class
} // class_exists