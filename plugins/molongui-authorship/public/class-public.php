<?php

namespace Molongui\Authorship\FrontEnd;

use Molongui\Authorship\Fw\Includes\Loader;
use Molongui\Authorship\Includes\Guest;
use Molongui\Authorship\Includes\Post;
use Molongui\Authorship\Includes\Box;
\defined( 'ABSPATH' ) or exit;
class FrontEnd
{
    private $loader;
    private $handle = MOLONGUI_AUTHORSHIP_NAME;
    public function __construct()
    {
        $this->init();
	    $this->load();
	    $this->hook();
    }
    private function init()
    {
        $this->loader = Loader::get_instance();
    }
    private function load()
    {
        $this->loader->add_action( 'plugins_loaded', $this, 'load_plugin_compat' );
        if ( \molongui_authorship_is_feature_enabled( 'byline_tags' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/template-tags.php';
    }
	public function load_plugin_compat()
	{
        require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat.php';
	}
	private function hook()
	{
        $this->hook_front();
        $this->hook_box();
	}
    private function hook_front()
    {
        $this->loader->add_action( 'wp_head', $this, 'add_element_query_css' );
        $this->loader->add_action( 'wp_enqueue_scripts', $this, 'register_styles'  );
        $this->loader->add_action( 'wp_enqueue_scripts', $this, 'register_scripts' );
        $this->loader->add_action( 'wp_enqueue_scripts', $this, 'enqueue_styles'   );
        $this->loader->add_action( 'wp_enqueue_scripts', $this, 'enqueue_scripts'  );
        $this->loader->add_action( 'wp_footer', $this, 'add_footer_scripts' );
    }
    public function add_element_query_css()
    {
        $options = \molongui_get_plugin_settings( MOLONGUI_AUTHORSHIP_ID, array( 'main', 'box', 'compat' ) );
        if ( !$options['enable_author_boxes'] or !$options['enable_cdn_compat'] ) return;
        $container_breakpoint = empty( $options['breakpoint'] ) ? '600' : $options['breakpoint'];
        $breakpoint_low_limit = $container_breakpoint - 1;
        $item_spacing         = '20';
        $eqcss  = '<style id="molongui-authorship-eq-css-fallback" media="screen">';
        $eqcss .= '.m-a-box-container[min-width~="'.$container_breakpoint.'px"] .m-a-box-content .m-a-box-content-top,
                   .m-a-box-container[min-width~="'.$container_breakpoint.'px"] .m-a-box-content .m-a-box-content-middle,
                   .m-a-box-container[min-width~="'.$container_breakpoint.'px"] .m-a-box-content .m-a-box-content-bottom { flex-direction: row; flex-wrap: nowrap; }';
        $eqcss .= '.m-a-box-container[max-width~="'.$breakpoint_low_limit.'px"] .m-a-box-title > :first-child { text-align: center !important; }
                   .m-a-box-container[max-width~="'.$breakpoint_low_limit.'px"] .m-a-box-meta { text-align: center !important; }
        ';

        $eqcss .= '.m-a-box-container[min-width~="'.$container_breakpoint.'px"] .m-a-box-content.m-a-box-profile .m-a-box-avatar { flex: 0 0; padding: 0 '.$item_spacing.'px 0 0; }
                   .m-a-box-container[min-width~="'.$container_breakpoint.'px"] .m-a-box-content.m-a-box-profile .m-a-box-social { display: flex; flex-direction: column; margin-top: 0; padding: 0 '.$item_spacing.'px 0 0; }     
                   .m-a-box-container[min-width~="'.$container_breakpoint.'px"] .m-a-box-content.m-a-box-profile .m-a-box-data { flex: 1 0; margin-top: 0; }     
                   .m-a-box-container[min-width~="'.$container_breakpoint.'px"] .m-a-box-content.m-a-box-profile .m-a-box-data .m-a-box-title > :first-child { text-align: left; }     
                   .m-a-box-container[min-width~="'.$container_breakpoint.'px"] .m-a-box-content.m-a-box-profile .m-a-box-data .m-a-box-meta { text-align: left; }     
        ';
        $eqcss .= '.m-a-box-container[min-width~="'.$container_breakpoint.'px"] .m-a-box-content .m-a-box-social .m-a-box-social-icon { margin: 0.4em 0; }';
        $eqcss  = \apply_filters( 'authorship/eqcss/fallback', $eqcss, $container_breakpoint, $item_spacing );
        $eqcss .= '</style>';
        echo $eqcss;
    }
    private function hook_box()
    {
        if ( \molongui_authorship_is_feature_enabled( 'box' ) )
        {
            $box = new Box();
            $settings = \get_option( MOLONGUI_AUTHORSHIP_BOX_SETTINGS );
            if ( $settings['order'] <= 10 )
            {
                \remove_filter( 'the_content', 'wpautop' );
                \add_filter( 'the_content', 'wpautop', $settings['order'] - 1 );
            }
            $this->loader->add_filter( 'the_content', $box, 'render', $settings['order'], 1 );
        }
    }
    public function register_styles()
    {
        if ( \molongui_authorship_is_feature_enabled( 'box' ) and \molongui_authorship_is_feature_enabled( 'box_styles' ) )
        {
            $file = 'public/css/molongui-authorship.27a7.min.css';
            if ( is_rtl() ) $file = 'public/css/molongui-authorship-rtl.e99e.min.css';

            if ( \file_exists( MOLONGUI_AUTHORSHIP_DIR . $file ) )
            {
                \wp_register_style( $this->handle, MOLONGUI_AUTHORSHIP_URL . $file, array(), MOLONGUI_AUTHORSHIP_VERSION, 'all' );
                $onthefly_css = $this->generate_on_the_fly_css();
                if ( !empty( $onthefly_css ) )
                {
                    \wp_add_inline_style( $this->handle, $onthefly_css );
                }
            }
        }
        else
        {
            $file = 'public/css/molongui-authorship-flat.0314.min.css';
            if ( \is_rtl() ) $file = 'public/css/molongui-authorship-flat-rtl.65b6.min.css';

            if ( \file_exists( MOLONGUI_AUTHORSHIP_DIR . $file ) )
            {
                \wp_register_style( $this->handle, MOLONGUI_AUTHORSHIP_URL . $file, array(), MOLONGUI_AUTHORSHIP_VERSION, 'all' );
            }
        }
    }
    public function generate_on_the_fly_css()
    {
        $css = '';
        $settings = \get_option( MOLONGUI_AUTHORSHIP_BOX_SETTINGS );
        $bp   = empty( $settings['breakpoint'] ) ? '600' : $settings['breakpoint'];
        $css .= ":root{ --m-a-box-bp: " . $bp . "px; --m-a-box-bp-l: " . --$bp . "px; }";
        if ( $settings['layout'] === 'tabbed' )
        {
            if ( !empty( $settings['tabs_position'] ) ) $position = \explode('-', $settings['tabs_position'] );
            if ( !empty( $position[0] ) ) $position = $position[0];
            else $position = 'top';
            if ( !empty( $settings['tabs_border'] ) ) $border = $settings['tabs_border'];
            else $border = 'around';
            $nav_style    = '';
            $tab_style    = '';
            $active_style = '';
            if ( !empty( $settings['tabs_background'] ) ) $nav_style .= ' background-color:'.$settings['tabs_background'].';';
            if ( !empty( $settings['tabs_color'] ) )      $tab_style .= ' background-color:'.$settings['tabs_color'].';';
            $tabs_background_style        = 'background-color: '.$settings['tabs_color'].';';
            $tabs_active_background_style = 'background-color: '.$settings['tabs_active_color'].';';
            $css .= "
                .m-a-box .m-a-box-tabs nav.m-a-box-tabs-{$position} { {$nav_style} }
                .m-a-box .m-a-box-tabs nav label { {$tab_style} }
                .m-a-box .m-a-box-tabs input[id^='mab-tab-profile-']:checked ~ nav label[for^='mab-tab-profile-'],
                .m-a-box .m-a-box-tabs input[id^='mab-tab-related-']:checked ~ nav label[for^='mab-tab-related-'],
                .m-a-box .m-a-box-tabs input[id^='mab-tab-contact-']:checked ~ nav label[for^='mab-tab-contact-']
                {
                    {$active_style}
                }
                
                .m-a-box .m-a-box-tabs nav label.m-a-box-tab { {$tabs_background_style} }
                .m-a-box .m-a-box-tabs nav label.m-a-box-tab.m-a-box-tab-active { {$tabs_active_background_style} }
                
                .m-a-box .m-a-box-tabs .m-a-box-related .m-a-box-related-entry-title,
                .m-a-box .m-a-box-tabs .m-a-box-related .m-a-box-related-entry-title a
                {
                    color: {$settings['related_text_color']} !important;
                }
            ";
        }
        return $css;
    }
    public function register_scripts()
    {
	    \molongui_register_element_queries();
        $file = 'public/js/molongui-authorship.5a1d.min.js';
        if ( \file_exists( MOLONGUI_AUTHORSHIP_DIR . $file ) )
        {
            \wp_register_script( $this->handle, MOLONGUI_AUTHORSHIP_URL . $file, array( 'jquery' ), MOLONGUI_AUTHORSHIP_VERSION, true );
            $settings = \get_option( MOLONGUI_AUTHORSHIP_BYLINE_SETTINGS );
            \wp_localize_script( $this->handle, 'molongui_authorship', array
            (
                'byline_prefix'         => ( !empty( $settings['byline_prefix'] ) ? $settings['byline_prefix'] : '' ),
                'byline_suffix'         => ( !empty( $settings['byline_suffix'] ) ? $settings['byline_suffix'] : '' ),
                'byline_separator'      => ( !empty( $settings['byline_multiauthor_separator'] ) ? $settings['byline_multiauthor_separator'].' ' : ', ' ),
                'byline_last_separator' => ( !empty( $settings['byline_multiauthor_last_separator'] ) ? ' '.$settings['byline_multiauthor_last_separator'].' ' : ' '.__( "and", 'molongui-authorship' )." " ),
                'byline_link_title'     => __( "View all posts by", 'molongui-authorship' ),
                'byline_dom_tree'       => \apply_filters( 'authorship/byline/dom_tree', '' ),
            ));
            $onthefly_js = $this->generate_on_the_fly_js();
            if ( !empty( $onthefly_js ) )
            {
                \wp_add_inline_script( $this->handle, $onthefly_js );
            }
        }
    }
    public function enqueue_styles()
    {
        if ( !\apply_filters( 'authorship/front/enqueue_styles', true ) ) return;

        \wp_enqueue_style( $this->handle );
    }
    public function enqueue_scripts()
    {
        if ( !\apply_filters( 'authorship/front/enqueue_scripts', true ) ) return;

        \wp_enqueue_script( $this->handle );
    }
	public function add_footer_scripts()
	{
        $js = '';
        $js .= $this->js_hide_elements();
        if ( empty( $js ) ) return;
        echo '<script type="text/javascript">'.$js.'</script>';
	}
    private function js_hide_elements()
    {
        $settings = \get_option( MOLONGUI_AUTHORSHIP_COMPAT_SETTINGS );
        if ( empty( $settings['hide_elements'] ) ) return '';

        $selectors = $settings['hide_elements'];
        return "var s='{$selectors}', match=s.split(', '); for (var a in match) {jQuery(match[a]).hide();}";
    }
	private function generate_on_the_fly_js()
	{
		$js = '';
		return $js;
	}
}