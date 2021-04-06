<?php

namespace Molongui\Authorship\Fw\Includes;
\defined( 'ABSPATH' ) or exit;
if ( !\class_exists( 'Molongui\Authorship\Fw\Includes\Options' ) )
{
    class Options
    {
        private $plugin;
        private $loader;
        private $vars;
        public $_options;
        public $_parent;
        public $_title;
        public $_name;
        public $_role;
        public $_slug;
        public $_prefix;
        public $_tab;
        public $_url;
        public function __construct( $plugin )
        {
            $this->plugin = $plugin;
            $this->loader = Loader::get_instance();
            $this->vars   = new \stdClass();
            $this->vars->handle = 'molongui-fw';
            $this->vars->config = include $this->plugin->dir . 'config/config.php';
            $this->vars->fw_dir     = \molongui_get_constant( $this->plugin->id, 'DIR'    , true );
            $this->vars->fw_url     = \molongui_get_constant( $this->plugin->id, 'URL'    , true );
            $this->vars->fw_version = \molongui_get_constant( $this->plugin->id, 'VERSION', true );
            $this->_parent = 'molongui';                                    // Parent menu.
            $this->_title  = \ucfirst( $this->plugin->title ).' Settings';  // Settings page title.
            $this->_name   = \ucfirst( $this->plugin->label ).' Settings';  // Menu item label.
            $this->_role   = 'manage_options';                              // Capability to access settings page.
            $this->_slug   = $this->plugin->name;                           // Settings page URL slug.
            $this->_prefix = $this->plugin->id;
            $this->_tab    = isset( $_GET['tab'] ) ? $_GET['tab'] : '';     // Tab to display by default.
            $this->_url    = \molongui_get_constant( $this->plugin->id, 'URL', true );
            $this->loader->add_action( 'admin_menu', $this, 'add_menu_item' );
            $this->loader->add_action( 'current_screen', $this, 'load' );
            $this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_semantic' );
            $this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_styles'   );
            $this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_scripts'  );
            $this->loader->add_action( 'admin_footer', $this, 'enqueue_tidio' );
            $this->loader->add_action( 'wp_ajax_'.$this->_prefix.'_save_options'  , $this, 'save_options'   );
            $this->loader->add_action( 'wp_ajax_'.$this->_prefix.'_export_options', $this, 'export_options' );
            $this->loader->add_action( 'wp_ajax_'.$this->_prefix.'_import_options', $this, 'import_options' );
            $this->loader->add_action( 'wp_ajax_'.$this->_prefix.'_reset_options' , $this, 'reset_options'  );
        }
        private function is_options_page()
        {
            $current_screen = \get_current_screen();
            return ( \strpos( $current_screen->id, $this->_slug ) );
        }
        public function load()
        {
            if ( !$this->is_options_page() ) return;
            if ( \file_exists( $file = \molongui_get_constant( $this->plugin->id, 'DIR', true ) . 'config/options.php' ) ) include $file;
            if ( \file_exists( $file = $this->plugin->dir . 'config/options.php' ) ) include $file;
            $this->_options = \array_merge_recursive( isset( $options ) ? $options : array(), isset( $fw_options ) ? $fw_options : array() );
        }
        public function add_menu_item()
        {
            if ( !current_user_can( 'manage_options' ) ) return;
            if ( empty( $GLOBALS['admin_page_hooks']['molongui'] ) )
            {
                $position = 30;
                \add_menu_page( __( "Molongui", 'molongui-authorship' ), __( "Molongui", 'molongui-authorship' ), $this->_role, 'molongui', array( $this, 'render_page_plugins' ), \molongui_get_base64_svg( $this->get_admin_menu_icon() ), $position );
                \add_submenu_page( $this->_parent, __( "Plugins", 'molongui-authorship' ), __( 'Plugins', 'molongui-authorship' ), $this->_role, 'molongui', array( $this, 'render_page_plugins' ) );
                \add_submenu_page( $this->_parent, __( "Support", 'molongui-authorship' ), __( 'Support', 'molongui-authorship' ), $this->_role, 'molongui-support', array( $this, 'render_page_support' ) );

                global $submenu;
                $submenu[$this->_parent]['molongui-docs'] = array( __( "Docs", 'molongui-authorship' ), $this->_role, \molongui_get_constant( $this->plugin->id, 'URL_DOCS', true ) );
                if ( !$this->plugin->is_pro )
                {
                    $submenu[$this->_parent]['molongui-demos'] = array( __( "Test Pro!", 'molongui-authorship' ), $this->_role, \molongui_get_constant( $this->plugin->id, 'URL_DEMOS', true ) );
                }
            }
            \add_submenu_page( $this->_parent, $this->_title, $this->_name, $this->_role, $this->_slug, array( $this, 'render_settings_page' ) );
            $this->reorder_submenu_items();
        }
        private function get_admin_menu_icon()
        {
            return '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
					 viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
						<g>
							<path d="M50,0C22.4,0,0,22.4,0,50c0,27.6,22.4,50,50,50s50-22.4,50-50C100,22.4,77.6,0,50,0z M27.8,66.3v0.4
								c-0.1,1.4-0.6,2.5-1.5,3.4c-1,0.9-2.1,1.4-3.5,1.4c-1.3,0-2.5-0.5-3.4-1.4c-1-0.9-1.5-2.1-1.5-3.4v-35c0.1-1.4,0.6-2.5,1.6-3.4
								c0.9-0.9,2.1-1.4,3.5-1.4c1.3,0,2.5,0.5,3.4,1.4c0.9,0.9,1.5,2.1,1.6,3.4V66.3z M81.9,66.5c0,1.4-0.5,2.6-1.5,3.5
								c-1,1-2.2,1.4-3.6,1.4c-1.4,0-2.6-0.5-3.6-1.5c-1-1-1.4-2.2-1.4-3.4v-19c0-1.2-0.1-2.5-0.3-3.8c-0.2-1.3-0.6-2.5-1.1-3.6
								c-0.6-1.1-1.4-2-2.5-2.7c-1.1-0.7-2.5-1.1-4.4-1.1c-1.8,0-3.3,0.3-4.4,1c-1.1,0.7-2,1.5-2.6,2.6c-0.6,1-1.1,2.2-1.3,3.5
								c-0.2,1.3-0.4,2.6-0.4,3.8v19c0,1.4-0.5,2.7-1.4,3.7c-0.9,1-2.1,1.6-3.7,1.6c-1.4,0-2.6-0.5-3.5-1.4c-1-1-1.4-2.1-1.4-3.5V47.2
								c0-1.2-0.1-2.4-0.3-3.7c-0.2-1.3-0.6-2.5-1.2-3.5c-0.6-1-1.4-1.9-2.5-2.6c-1.1-0.7-2.5-1-4.2-1c-1.5,0-2.8,0.3-3.8,0.8
								c-1,0.5-1.9,1.2-2.6,1.9v-9c1.1-0.8,2.4-1.5,3.9-2.2c1.5-0.6,3.3-1,5.4-1c1.1,0,2.2,0.1,3.4,0.4c1.2,0.3,2.4,0.7,3.6,1.3
								c1.1,0.6,2.2,1.4,3.2,2.5s1.9,2.4,2.6,4c0.6-1.1,1.4-2.1,2.3-3.1c0.9-1,1.9-1.9,3-2.6c1.1-0.7,2.4-1.3,3.8-1.8
								c1.4-0.5,3-0.7,4.7-0.7c1.8,0,3.7,0.3,5.7,0.9c2,0.6,3.8,1.7,5.4,3.4c1,1,1.7,2,2.4,3c0.6,1,1.1,2.1,1.4,3.3
								c0.3,1.2,0.5,2.6,0.7,4.2c0.1,1.6,0.2,3.4,0.2,5.6V66.5z"/>
						</g>
					</svg>';
        }
        private function reorder_submenu_items()
        {
            global $submenu;
            if ( empty( $submenu['molongui'] ) ) return;
            $titles = array();
            foreach ( $submenu['molongui'] as $items )
            {
                $titles[] = $items[0];
            }
            \array_multisort( $titles, SORT_ASC, $submenu['molongui'] );
            foreach ( $submenu['molongui'] as $key => $value )
            {
                if ( $value[2] == 'molongui'         ) { $plugins_key = $key; $plugins = $value; }
                if ( $value[2] == 'molongui-support' ) { $support_key = $key; $support = $value; }
                if ( $key == 'molongui-demos'        ) { $demos_key   = $key; $demos   = $value; }
                if ( $key == 'molongui-docs'         ) { $docs_key    = $key; $docs    = $value; }
            }
            unset( $submenu['molongui'][$plugins_key] );
            unset( $submenu['molongui'][$docs_key] );
            unset( $submenu['molongui'][$support_key] );
            unset( $submenu['molongui'][$demos_key] );
            \array_unshift( $submenu['molongui'], $plugins );             // Set "plugins" submenu at the top of the list.
            \array_push( $submenu['molongui'], $docs, $support, $demos ); // Set "support" and "demos" submenus at the bottom.
        }
        public function render_page_plugins()
        {
            $upsell  = new Upsell( $this->plugin );
            $upsells = $upsell->get() ;

            include \molongui_get_constant( $this->plugin->id, 'DIR', true ) . 'admin/views/html-page-plugins.php';
        }
        public function render_page_support()
        {
            $tidio_url = 'https://www.tidiochat.com/chat/foioudbu7xqepgvwseufnvhcz6wkp7am';

            include \molongui_get_constant( $this->plugin->id, 'DIR', true ) . 'admin/views/html-page-support.php';
        }
        public function render_settings_page()
        {
            if ( $this->_options )
            {
                foreach ( $this->_options as $key => $value )
                {
                    if ( $value['type'] == 'section' )
                    {
                        if ( isset( $value['display'] ) and !$value['display'] ) continue;
                        $tabs[$value['id']] = array( 'display' => $value['display'], 'id' => $value['id'], 'name' => \ucfirst( $value['name'] ) );
                        $parent = $value['id'];
                    }
                    else
                    {
                        if ( isset( $value['display'] ) and !$value['display'] ) continue;
                        if ( !isset( $parent ) ) $parent = 0;
                        ${'tab_'.$parent}[$key] = $value;
                    }
                }
                if ( isset( $tabs ) )
                {
                    $nav_items    = '';
                    $div_contents = null;
                    if ( $this->_tab == '' )
                    {
                        \reset( $tabs );
                        $this->_tab = \key( $tabs );
                        while ( !$tabs[$this->_tab]['display'] )
                        {
                            \next( $tabs );
                            $this->_tab = \key( $tabs );
                        }
                    }
                    foreach ( $tabs as $tab )
                    {
                        $nav_items    .= '<li class="m-section-nav-tab '.( $tab['id'] == $this->_tab ? 'is-selected' : '' ).'"><a class="m-section-nav-tab__link" href="#'.$tab['id'].'" data-id="'.$tab['id'].'" role="menuitem"><span class="m-section-nav-tab__text">' . $tab['name'] . '</span></a></li>';
                        $div_contents .= '<section id="'.$tab['id'].'" class="m-tab '.( $tab['id'] == $this->_tab ? 'current' : '' ).'">';
                        if ( isset( ${'tab_'.$tab['id']} ) )
                        {
                            foreach ( ${'tab_'.$tab['id']} as $option )
                            {
                                $html = new Option( $tab['id'], $option, $this->plugin->db_prefix, $this->plugin->is_pro );
                                $div_contents .= $html;
                            }
                        }
                        else
                        {
                            $div_contents .= __( 'There are no settings defined for this tab.', 'molongui-authorship' );
                        }

                        $div_contents .= '</section>';
                    }
                }
                else
                {
                    $no_tab = true;
                    $div_contents = '<div class="m-no-tab">';

                    foreach ( ${'tab_0'} as $tab_content )
                    {
                        $option = new Option( 0, $tab_content, $this->_prefix, false );
                        $div_contents .= $option;
                    }

                    $div_contents .= '</div>';
                }

            }
            require_once \molongui_get_constant( $this->plugin->id, 'DIR', true ) . 'admin/views/html-page-options.php';
        }
        public function enqueue_semantic()
        {
            if ( !$this->is_options_page() ) return;
            \molongui_register_semantic_ui_dropdown();
            \molongui_register_semantic_ui_transition();
            \molongui_register_semantic_ui_icon();
            \molongui_register_semantic_ui_label();
            \molongui_register_semantic_ui_icon();
            \molongui_register_semantic_ui_popup();
            \molongui_enqueue_semantic_ui_dropdown();
            \molongui_enqueue_semantic_ui_transition();
            \molongui_enqueue_semantic_ui_icon();
            \molongui_enqueue_semantic_ui_label();
            \molongui_enqueue_semantic_ui_icon();
            \molongui_enqueue_semantic_ui_popup();
        }
        public function enqueue_styles()
        {
            if ( !$this->is_options_page() ) return;
            if ( !empty( $this->vars->config['options']['colorpicker'] ) ) \wp_enqueue_style( 'wp-color-picker' );
            $file = 'admin/css/mcf-options.xxxx.min.css';
            if ( \is_rtl() ) $file = 'admin/css/mcf-options-rtl.xxxx.min.css';

            if ( \file_exists( $this->vars->fw_dir.$file ) )
            {
                \wp_enqueue_style( $this->vars->handle.'-options'  , $this->vars->fw_url.$file, array(), $this->vars->fw_version, 'all' );
                $onthefly_css = $this->get_on_the_fly_css();
                if ( !empty( $onthefly_css ) ) \wp_add_inline_style( $this->vars->handle.'-options', $onthefly_css );
            }
            $file = 'assets/css/options.'.$this->plugin->version.'.min.css';
            if ( \is_rtl() ) $file = 'assets/css/options-rtl.'.$this->plugin->version.'.min.css';
            if ( \file_exists( $this->plugin->dir.$file ) )
            {
                \wp_enqueue_style( $this->plugin->name.'-options', $this->plugin->url.$file, array(), $this->plugin->version, true );
            }
        }
        private function get_on_the_fly_css()
        {
            $css = '';
            return $css;
        }
        public function enqueue_scripts( $hook )
        {
            if ( !$this->is_options_page() ) return;
            if ( !empty( $this->vars->config['options']['colorpicker'] ) ) \wp_enqueue_script( 'wp-color-picker' );
            $file = 'admin/js/mcf-options.98e6.min.js';
            if ( \file_exists( $this->vars->fw_dir.$file ) )
            {
                $fw_config = include $this->plugin->dir . 'fw/config/config.php';
                \wp_enqueue_script( $this->vars->handle.'-options', $this->vars->fw_url.$file, array( 'jquery' ), $this->vars->fw_version, true );
                \wp_localize_script( $this->vars->handle.'-options', 'mfw_options', array
                (
                    'plugin_id'      => $this->plugin->id,
                    'plugin_version' => $this->plugin->version,
                    'is_pro'         => $this->plugin->is_pro,
                    'options_page'   => \esc_url( \admin_url( $fw_config['menu']['slug'] . $this->plugin->name . '&tab=' . 'molongui_' . $this->plugin->id . '_pro_' . $fw_config['tabs']['license'] ) ),
                    1 => __( 'Premium feature', 'molongui-authorship' ),
                    2 => __( 'This feature is available only for Premium users. Upgrade to Premium to unlock it!', 'molongui-authorship' ),
                    101 => $this->_url,
                    102 => __( 'Saving', 'molongui-authorship' ),
                    103 => __( 'You are about to leave this page without saving. All changes will be lost.', 'molongui-authorship' ),
                    104 => __( 'WARNING: You are about to delete all your settings! Please confirm this action.', 'molongui-authorship' ),
                    105 => $this->plugin->db_prefix,//$this->_prefix,
                    106 => __( 'WARNING: You are about to restore your backup. This will overwrite all your settings! Please confirm this action.', 'molongui-authorship' ),
                    107 => __( 'WARNING: You are about to delete your backup. All unsaved options will be lost. We recommend that you save your options before deleting a backup. Please confirm this action.', 'molongui-authorship' ),
                    108 => __( 'WARNING: You are about to create a backup. All unsaved options will be lost. We recommend that you save your options before deleting a backup. Please confirm this action.', 'molongui-authorship' ),
                    109 => __( 'Delete', 'molongui-authorship' ),
                    110 => $this->plugin->id,
                    111 => \wp_create_nonce( 'mfw_import_options_nonce' ),
                    112 => __( 'File upload failed', 'molongui-authorship' ),
                    113 => __( 'Failed to load file.', 'molongui-authorship' ),
                    114 => __( 'Wrong file type', 'molongui-authorship' ),
                    115 => __( 'Only valid .JSON files are accepted.', 'molongui-authorship' ),
                    116 => __( 'Warning', 'molongui-authorship' ),
                    117 => __( 'You are about to restore your settings. This will overwrite all your existing configuration! Please confirm this action.', 'molongui-authorship' ),
                    118 => __( "Cancel", 'molongui-authorship' ),
                    119 => __( "OK", 'molongui-authorship' ),
                    120 => __( "Success!", 'molongui-authorship' ),
                    121 => __( "Plugin settings have been imported successfully. Click on the OK button and the page will be reloaded automatically.", 'molongui-authorship' ),
                    122 => __( "Error", 'molongui-authorship' ),
                    123 => __( "Something went wrong and plugin settings couldn't be restored. Please, make sure uploaded file has content and try uploading the file again.", 'molongui-authorship' ),
                    124 => \sprintf( __( "Either the uploaded backup file is for another plugin or it is from a newer version of the plugin. Please, make sure you are uploading a file generated with %s version lower or equal to %s.", 'molongui-authorship' ), $this->plugin->title, $this->plugin->version ),
                    125 => __( "Some settings couldn't be restored. Please, try uploading the file again.", 'molongui-authorship' ),
                    126 => __( 'You are about to restore plugin default settings. This will overwrite all your existing configuration! Please confirm this action.', 'molongui-authorship' ),
                    127 => \wp_create_nonce( 'mfw_reset_options_nonce' ),
                    128 => __( "Plugin settings have been restored to defaults successfully. Click on the OK button and the page will be reloaded automatically.", 'molongui-authorship' ),
                    129 => __( "Something went wrong and plugin defaults couldn't be restored. Please, try again.", 'molongui-authorship' ),
                    130 => __( "Something went wrong and couldn't connect to the server. Please, try again.", 'molongui-authorship' ),
                    200 => \wp_create_nonce( 'mfw_license_nonce' ),
                    201 => __( "Something is missing...", 'molongui-authorship' ),
                    202 => __( "You need to provide both values, License Key and License E-mail", 'molongui-authorship' ),
                    203 => __( "Activated!", 'molongui-authorship' ),
                    204 => __( "Oops... activation failed", 'molongui-authorship' ),
                    205 => __( "Oops!", 'molongui-authorship' ),
                    206 => __( "Something went wrong and the license has not been activated.", 'molongui-authorship' ),
                    207 => __( "Deactivate license", 'molongui-authorship' ),
                    208 => __( "Submit to deactivate your license now", 'molongui-authorship' ),
                    209 => __( "No, cancel!", 'molongui-authorship' ),
                    210 => __( "Yes, deactivate it!", 'molongui-authorship' ),
                    211 => __( "Deactivated!", 'molongui-authorship' ),
                    212 => __( "Oops... something weird happened!", 'molongui-authorship' ),
                    213 => __( "Something went wrong and the license has not been deactivated.", 'molongui-authorship' ),
                    214 => __( "Activate", 'molongui-authorship' ),
                    215 => __( "Deactivate", 'molongui-authorship' ),
                ));
            }
            $file = 'assets/js/options.'.$this->plugin->version.'.min.js';
            if ( \file_exists( $this->plugin->dir.$file ) )
            {
                \wp_enqueue_script( $this->plugin->name.'-options', $this->plugin->url.$file, array( 'jquery' ), $this->plugin->version, true );
            }
        }
        public function enqueue_tidio()
        {
            $screen  = \get_current_screen();
            $screens = array( 'molongui_page_molongui-support' );

            if ( \in_array( $screen->id, $screens ) )
            {
                echo '<script src="//code.tidio.co/foioudbu7xqepgvwseufnvhcz6wkp7am.js" async></script>';
            }
        }
        public function save_options()
        {
            if ( !isset( $_POST['nonce'] ) ) return;
            if ( !\wp_verify_nonce( $_POST['nonce'], 'mfw_save_options_nonce' ) ) return;
            $data = $_POST['data'];

            if ( isset( $data ) and \is_array( $data ) )
            {
                unset( $data['license'] );

                foreach ( $data as $tab => $settings )
                {
                    $settings = \call_user_func( $this->plugin->id.'_validate_settings', $tab, $settings );
                    \update_option( $tab, $settings );
                }
            }
            \wp_die();
        }
        public function export_options()
        {
            $options = \molongui_export_options( $_POST['plugin'] );
            echo \json_encode( $options );
            \wp_die();
        }
        public function import_options()
        {
            \check_ajax_referer( 'mfw_import_options_nonce', 'nonce', true );
            $rc             = false;
            $plugin_id      = $_POST['id'];
            $plugin_version = $_POST['version'];
            $options        = \json_decode( \wp_unslash( $_POST['file'] ), true );
            $prefix         = 'molongui_'.\str_replace( '-', '_', $plugin_id ).'_';
            if ( isset( $options ) )
            {
                if ( !empty( $options['plugin_id'] ) and $options['plugin_id'] == $plugin_id and
                     !empty( $options['plugin_version'] ) and \version_compare( $options['plugin_version'], $plugin_version, '<=' ) )
                {
                    unset( $options['plugin_id'] );
                    unset( $options['plugin_version'] );
                    $defaults = \call_user_func( $prefix.'get_default_settings' );
                    $options = \molongui_options_merge( $prefix, $options, $defaults );
                    foreach ( $options as $option => $value )
                    {
                        $r = \update_option( $option, \maybe_unserialize( $value ) );
                        if ( !$r )
                        {
                            if ( $value !== \get_option( $option ) and $value !== \maybe_serialize( \get_option( $option ) ) )
                            {
                                $rc = 'update';
                            }
                        }
                    }
                }
                else
                {
                    $rc = 'plugin';
                }
            }
            else
            {
                $rc = 'file';
            }
            echo $rc;
            \wp_die();
        }
        public function reset_options()
        {
            \check_ajax_referer( 'mfw_reset_options_nonce', 'nonce', true );
            $plugin_id = $_POST['id'];
            $rc = \molongui_reset_options( $plugin_id );
            echo $rc;
            \wp_die();
        }

    } // End of class
} // End if_class_exists