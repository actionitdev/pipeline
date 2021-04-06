<?php
defined( 'ABSPATH' ) or exit;

$fw_options = array();
$this->plugin->config = include( $this->plugin->dir . 'config/config.php' );
if ( true )
{
    $fw_options[] = array
    (
        'display' => true,
        'type'    => 'section',
        'id'      => $this->plugin->db_prefix . 'main',
        'name'    => __( "Main", 'molongui-authorship' ),
    );
    $fw_options[] = array
    (
        'display' => $this->plugin->config['fw']['settings']['uninstalling'],
        'type'    => 'header',
        'label'   => __( "Uninstall", 'molongui-authorship' ),
        'button'  => array(),
    );
    $fw_options[] = array
    (
        'display' => $this->plugin->config['fw']['settings']['keep_config'],
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'keep_config',
        'title'   => '',
        'desc'    => '',
        'help'    => sprintf( __( "%sKeep this setting enabled to prevent config loss when removing the plugin from your site.%s %sKeeping plugin config might be useful on plugin reinstall or site migration.%s %sIf you want to completely remove all plugin config, uncheck this setting and then remove the plugin.%s", 'molongui-authorship' ), '<p>', '</p>', '<p>', '</p>', '<p>', '</p>' ),
        'label'   => __( "Keep plugin configuration for future use upon plugin uninstall.", 'molongui-authorship' ),
    );
    $fw_options[] = array
    (
        'display' => $this->plugin->config['fw']['settings']['keep_data'],
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'keep_data',
        'title'   => '',
        'desc'    => '',
        'help'    => sprintf( __( "%sKeep this setting enabled to prevent data loss when removing the plugin from your site.%s %sKeeping plugin data might be useful on plugin reinstall or site migration.%s %sIf you want to completely remove any data added by the plugin since it was installed, uncheck this setting and then remove the plugin.%s", 'molongui-authorship' ), '<p>', '</p>', '<p>', '</p>', '<p>', '</p>' ),
        'label'   => __( "Keep plugin data for future use upon plugin uninstall.", 'molongui-authorship' ),
    );
}
if ( true )
{
    $fw_options[] = array
    (
        'display' => true,
        'type'    => 'section',
        'id'      => $this->plugin->db_prefix . 'tools',
        'name'    => __( 'Tools' ),
    );
    $fw_options[] = array
    (
        'display' => true,
        'type'    => 'header',
        'label'   => __( "Plugin settings", 'molongui-authorship' ),
        'button'  => array(),
    );
    $fw_options[] = array
    (
        'display' => true,
        'type'    => 'export',
        'class'   => 'is-compact',
        'label'   => __( "Export plugin configuration to have a backup or restore it on another installation", 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'id'       => 'export_options',
            'label'    => __( "Backup", 'molongui-authorship' ),
            'title'    => __( "Backup Plugin Configuration", 'molongui-authorship' ),
            'class'    => 'm-export-options same-width',
            'disabled' => false,
        ),
    );
    $plugin_tools   = array();
    $plugin_tools[] = array
    (
        'display' => apply_filters( 'fw/options/display/banners', true ),
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'import_options',
        'title'   => __( "Easily import previously saved plugin configuration with just 1 click", 'molongui-authorship' ),
        'desc'    => '',
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade same-width',
            'href'   => $this->plugin->web,
            'target' => '_blank',
        ),
    );
    $plugin_tools[] = array
    (
        'display' => apply_filters( 'fw/options/display/banners', true ),
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'import_options',
        'title'   => __( "Reset plugin settings to their defaults", 'molongui-authorship' ),
        'desc'    => '',
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade same-width',
            'href'   => $this->plugin->web,
            'target' => '_blank',
        ),
    );

    $fw_options = array_merge( $fw_options, apply_filters( '_fw/options/plugin/tools/markup', $plugin_tools ) );
}
if ( $this->plugin->has_pro and is_plugin_active( $this->plugin->slug.'-pro/'.$this->plugin->slug.'-pro.php' ) )
{
    $pro_dir    = rtrim( $this->plugin->dir, '/' ) . '-pro/';
    $license_on = molongui_is_active( $pro_dir );
    $this->plugin->update = include( $pro_dir . 'config/update.php' );
    $fw_options[] = array
    (
        'display' => true,
        'type'    => 'section',
        'id'      => apply_filters( $this->plugin->id.'/admin/options/license/db_key', $this->plugin->db_prefix . 'pro_license' ),
        'name'    => __( "License", 'molongui-authorship' ),
    );
    $fw_options[] = array
    (
        'display' => true,
        'type'    => 'header',
        'label'   => __( "License", 'molongui-authorship' ),
        'button'  => array(),
    );
    if ( $license_on )
    {
        $fw_options[] = array
        (
            'display' => true,
            'type'    => 'notice',
            'class'   => '',
            'default' => '',
            'id'      => 'license_notice',
            'title'   => '',
            'desc'    => __( "Your license is active. You might want to deactivate your license key on this site to use it in different installation. Should you want to re-activate the plugin here, you will need to input your credentials again.", 'molongui-authorship' ),
            'help'    => '',
            'link'    => '',
        );
    }
    else
    {
        $fw_options[] = array
        (
            'display' => true,
            'type'    => 'notice',
            'class'   => '',
            'default' => '',
            'id'      => 'license_notice',
            'title'   => '',
            'desc'    => sprintf( __( "Provide a valid license to activate the plugin. You can find them on your %sPrivate Customer Console%s", 'molongui-authorship' ), '<a href="'.molongui_get_constant( $this->plugin->id, 'URL_MYACCOUNT', true ).'" target="_blank">', '</a>' ),
            'help'    => '',
            'link'    => '',
        );
    }
    $fw_options[] = array
    (
        'display'     => true,
        'type'        => 'text',
        'placeholder' => __( "Type here your license key", 'molongui-authorship' ),
        'default'     => '',
        'class'       => $license_on ? ' m-license-on' : '',
        'id'          => isset( $this->plugin->update['db']['activation_key'] ) ? $this->plugin->update['db']['activation_key'] : 'activation_key',
        'title'       => '',
        'desc'        => '',
        'help'        => array
        (
            'text'    => sprintf( __( "%sThe license key you got by e-mail upon purchase.%s %sYou can also retrieve your key from your Customer Account.%s", 'molongui-authorship' ), '<p>', '</p>', '<p>', '</p>' ),
            'link'    => \molongui_get_constant( $this->plugin->id, 'URL_WEB', true ).'/my-account',
        ),
        'label'       => __( "License KEY", 'molongui-authorship' ),
    );
    $fw_options[] = array
    (
        'display'     => true,
        'type'        => 'text',
        'placeholder' => __( "Type here the e-mail address you provided upon purchase", 'molongui-authorship' ),
        'default'     => '',
        'class'       => $license_on ? ' m-license-on' : '',
        'id'          => isset( $this->plugin->update['db']['activation_email'] ) ? $this->plugin->update['db']['activation_email'] : 'activation_email',
        'title'       => '',
        'desc'        => '',
        'help'        => array
        (
            'text'    => sprintf( __( "%sThe e-mail address provided upon purchase.%s %sShould you have changed the e-mail address from your profile, you still have to provide the e-mail address provided upon purchase.%s", 'molongui-authorship' ), '<p>', '</p>', '<p>', '</p>' ),
            'link'    => '',
        ),
        'label'       => __( "License EMAIL", 'molongui-authorship' ),
    );
    if ( $license_on )
    {
        $fw_options[] = array
        (
            'display' => true,
            'type'    => 'header',
            'class'   => 'is-compact',
            'label'   => __( "Deactivating the license will make the plugin to stop working on this site.", 'molongui-authorship' ),
            'button'  => array
            (
                'display'  => true,
                'type'     => 'action',
                'id'       => 'deactivate_license_button',
                'label'    => __( "Deactivate", 'molongui-authorship' ),
                'title'    => __( "Deactivate License", 'molongui-authorship' ),
                'class'    => 'm-license m-deactivate same-width',
                'disabled' => false,
            ),
        );
    }
    else
    {
        $fw_options[] = array
        (
            'display' => true,
            'type'    => 'header',
            'class'   => 'is-compact',
            'label'   => '',
            'button'  => array
            (
                'display'  => true,
                'type'     => 'action',
                'id'       => 'activate_license_button',
                'label'    => __( "Activate", 'molongui-authorship' ),
                'title'    => __( "Activate License", 'molongui-authorship' ),
                'class'    => 'is-primary m-license m-activate same-width',
                'disabled' => false,
            ),
        );
        $options[] = array
        (
            'display' => true,
            'type'    => 'link',
            'class'   => '',
            'default' => '',
            'id'      => '',
            'title'   => '',
            'desc'    => '',
            'help'    => __( "Click here to get some help", 'molongui-authorship' ),
            'label'   => __( "Can't activate your license? Get help", 'molongui-authorship' ),
            'href'    => 'https://www.molongui.com/docs/'.$this->plugin->slug.'/license-activation/',
            'target'  => '_blank',
        );
    }
    $fw_options[] = array
    (
        'display' => true,
        'type'    => 'header',
        'label'   => __( "Retention", 'molongui-authorship' ),
        'button'  => array(),
    );
    $fw_options[] = array
    (
        'display' => true,
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'keep_license',
        'title'   => '',
        'desc'    => '',
        'help'    => sprintf( __( '%sWhether to deactivate the licence key upon plugin deactivation.%s %sRegardless of this setting, the license will be released when uninstalling the plugin.%s', 'molongui-authorship' ), '<p>', '</p>', '<p>', '</p>' ),
        'label'   => __( "Keep plugin license active upon plugin deactivation.", 'molongui-authorship' ),
    );
}