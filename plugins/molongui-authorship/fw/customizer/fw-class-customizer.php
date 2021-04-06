<?php

namespace Molongui\Authorship\Fw\Customizer;
defined( 'ABSPATH' ) or exit;
if ( !class_exists( 'Molongui\Authorship\Fw\Customizer\Customizer' ) )
{
	class Customizer
	{
		private $plugin;
		public function __construct( $plugin_id )
		{
            if ( !is_customize_preview() ) return;
            $this->plugin = molongui_get_plugin( $plugin_id );
            foreach ( glob(plugin_dir_path(__FILE__) . 'controls/classes/*.php' ) as $file )
            {
                require_once $file;
            }
            require_once 'customizer-helper-callbacks.php';
            require_once $this->plugin->dir . 'customizer/plugin-customizer-callbacks.php';
            add_action( 'customize_register', array( $this, 'molongui_customizer_settings' ) );
            add_action( 'customize_controls_enqueue_scripts', array( $this, 'molongui_customizer_hide_settings' ) );
			add_action( 'customize_preview_init', array( $this, 'molongui_customizer_preview' ) );
		}
		public function molongui_customizer_settings( $wp_customize )
		{
			$styles = include( $this->plugin->dir . 'config/customizer.php' );
			if ( empty( $styles ) ) return;
			if ( $styles['add_panel'] )
			{
				$wp_customize->add_panel( $styles['id'], array
				(
					'title'           => $styles['title'],
					'description'     => $styles['description'],
					'priority'        => ( !empty( $styles['priority'] ) ? $styles['priority'] : '10' ),
					'capability'      => ( !empty( $styles['capability'] ) ? $styles['capability'] : 'manage_options' ),
					'active_callback' => ( !empty( $styles['active_callback'] ) ? $styles['active_callback'] : '' ),
				));
			}
			$wp_customize->add_panel( $styles['id'], array
			(
				'title'           => $styles['title'],
				'description'     => $styles['description'],
				'priority'        => ( !empty( $styles['priority'] ) ? $styles['priority'] : '10' ),
				'capability'      => ( !empty( $styles['capability'] ) ? $styles['capability'] : 'manage_options' ),
				'active_callback' => ( !empty( $styles['active_callback'] ) ? $styles['active_callback'] : '' ),
			));
			if ( !empty( $styles['sections'] ) )
			{
				foreach( $styles['sections'] as $section )
				{
					if ( !$section['display'] ) continue;
					$args = array
					(
						'title'              => $section['title'],
						'description'        => $section['description'],
						'priority'           => ( !empty( $section['priority'] ) ? $section['priority'] : 10 ),
						'type'               => ( !empty( $section['type'] ) ? $section['type'] : '' ),
						'capability'         => ( !empty( $section['capability'] ) ? $section['capability'] : 'manage_options' ),
						'active_callback'    => ( !empty( $section['active_callback'] ) ? $section['active_callback'] : '' ),
						'description_hidden' => ( !empty( $section['description_hidden'] ) ? $section['description_hidden'] : false ),
					);
					if ( $styles['add_panel'] ) $args['panel'] = $styles['id'];
					$wp_customize->add_section( $section['id'] , $args );
					if ( !empty( $section['fields'] ) )
					{
						foreach ( $section['fields'] as $field )
						{
							if ( !$field['display'] ) continue;
							if ( !empty( $field['setting'] ) )
							{
								$wp_customize->add_setting( $field['id'], array
								(
									'type'                 => ( !empty( $field['setting']['type'] ) ? $field['setting']['type'] : 'option' ),
									'capability'           => ( !empty( $field['setting']['capability'] ) ? $field['setting']['capability'] : 'manage_options' ),
									'default'              => ( !empty( $field['setting']['default'] ) ? $field['setting']['default'] : '' ),
									'transport'            => ( !empty( $field['setting']['transport'] ) ? $field['setting']['transport'] : 'refresh' ),
									'validate_callback'    => ( !empty( $field['setting']['validate_callback'] ) ? $field['setting']['validate_callback'] : '' ),
									'sanitize_callback'    => ( !empty( $field['setting']['sanitize_callback'] ) ? $field['setting']['sanitize_callback'] : '' ),
									'sanitize_js_callback' => ( !empty( $field['setting']['sanitize_js_callback'] ) ? $field['setting']['sanitize_js_callback'] : '' ),
									'dirty'                => ( !empty( $field['setting']['dirty'] ) ? $field['setting']['dirty'] : false ),
								));
								if ( !empty( $field['control'] ) )
								{
									$class = empty( $field['control']['class'] ) ? 'WP_Customize_Control' : $field['control']['class'];
									$wp_customize->add_control( new $class( $wp_customize, $field['id'], array
									(
										'settings'        => $field['id'], // or also: array( 'default' => $field['id'] ),
										'capability'      => ( !empty( $field['setting']['capability'] ) ? $field['setting']['capability'] : 'manage_options' ),
										'priority'        => ( !empty( $field['control']['priority'] ) ? $field['control']['priority'] : 10 ),
										'section'         => ( !empty( $field['control']['section'] ) ? $field['control']['section'] : $section['id'] ),
										'label'           => ( !empty( $field['control']['label'] ) ? $field['control']['label'] : '' ),
										'description'     => ( !empty( $field['control']['description'] ) ? $field['control']['description'] : '' ),
										'choices'         => ( !empty( $field['control']['choices'] ) ? $field['control']['choices'] : array() ),
										'input_attrs'     => ( !empty( $field['control']['input_attrs'] ) ? $field['control']['input_attrs'] : array() ),
										'allow_addition'  => ( !empty( $field['control']['allow_addition'] ) ? $field['control']['allow_addition'] : false ),
										'type'            => ( !empty( $field['control']['type'] ) ? $field['control']['type'] : 'text' ),
										'active_callback' => ( !empty( $field['control']['active_callback'] ) ? $field['control']['active_callback'] : '' ),
									)));
								}
							}
						}
					}
				}
			}
		}
		public function molongui_customizer_preview()
		{
			if ( !$this->plugin->is_pro )
			{
				$fpath = 'customizer/css/live-preview.min.css';
				if ( file_exists( $this->plugin->dir . $fpath ) )
				{
					wp_enqueue_style( $this->plugin->name.'-preview', $this->plugin->url . $fpath, array(), $this->plugin->version );
				}
			}
			$fpath = 'customizer/js/live-preview.min.js';
			if ( file_exists( $this->plugin->dir . $fpath ) )
			{
				wp_enqueue_script( $this->plugin->name.'-preview', $this->plugin->url . $fpath, array( 'jquery', 'customize-preview' ), $this->plugin->version );
			}
		}
		public function molongui_customizer_hide_settings()
		{
			$fw_version  = molongui_get_constant( $this->plugin->id, 'VERSION', true );
			$fpath = 'fw/customizer/css/styles.4506.min.css';
			if ( file_exists( $this->plugin->dir . $fpath ) )
			{
				wp_enqueue_style( 'molongui-framework-preview', $this->plugin->url . $fpath, array(), $fw_version );
			}
			$fpath = 'fw/customizer/js/scripts.da39.min.js';
			if ( file_exists( $this->plugin->dir . $fpath ) )
			{
				wp_enqueue_script( 'molongui-framework-preview', $this->plugin->url . $fpath, array( 'jquery', 'customize-preview' ), $fw_version );

				if ( $this->plugin->has_upgrade and !$this->plugin->is_pro )
				{
					$script = sprintf( '
								( function($) {
									$( window ).load( function()
									{
										$( "#sub-accordion-panel-%s" ).append(
											"<li class=\"molongui-accordion-section-divider\">" +
												"<span>%s</span>" +
												"<p>%s</p>" +
											"</li>" +
											"<li class=\"accordion-section control-section control-section- control-subsection molongui-upgrade-link\">" +
												"<a href=\"%s\" target=\"_blank\"><h3 class=\"accordion-section-title\" tabindex=\"0\">%s</h3></a>" +
											"</li>"
										);
									});
								})(jQuery);
								', $this->plugin->name, __( 'Pro features', 'molongui-authorship' ), __( 'Take a look at all the available Pro features.', 'molongui-authorship' ), $this->plugin->web, __( 'Go Pro', 'molongui-authorship' ) );
					wp_add_inline_script( 'molongui-framework-preview', $script );
				}
			}
			$fpath = 'customizer/js/customizer-scripts.min.js';
			if ( file_exists( $this->plugin->dir . $fpath ) )
			{
				wp_enqueue_script( $this->plugin->name.'-scripts', $this->plugin->url . $fpath, array( 'jquery', 'customize-preview' ), $this->plugin->version );
			}
		}

	}
}