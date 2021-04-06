<?php
defined( 'ABSPATH' ) or exit;
$config = array
(
    'name'        => 'Molongui Authorship',
    'version'     => '4.2.17',
    'db'          => 17,
    'id'          => 'Authorship',
    'label'       => 'Authorship',
    'web'         => 'https://www.molongui.com/authorship/',
    'has_upgrade' => true,
);
$dir_path    = dirname( __DIR__ ).'/';
$dashed_id   = strtolower( str_replace( ' ', '-', $config['id'] ) );
$uscored_id  = strtolower( strtr( $config['id'], array( ' ' => '_', '-' => '_' ) ) );
$dashed_name = 'molongui-'.$dashed_id;
$db_prefix   = 'molongui_'.$uscored_id.'_';
$constants = array
(
    'TITLE'       => $config['name'],
    'LABEL'       => $config['label'],
    'VERSION'     => $config['version'],
    'SLUG'        => $dashed_name,
    'NAME'        => $dashed_name,
    'ID'          => $uscored_id,
    'HAS_UPGRADE' => $config['has_upgrade'],
    'WEB'         => $config['web'],
    'DB'                => $config['db'],
    'DB_PREFIX'         => $db_prefix,
    'DB_VERSION'        => $db_prefix.'db_version',
    'INSTALLATION'      => $db_prefix.'installation',
    'NOTICES'           => $db_prefix.'notices',
    'MAIN_SETTINGS'     => $db_prefix.'main',
    'BOX_SETTINGS'      => $db_prefix.'box',
    'BYLINE_SETTINGS'   => $db_prefix.'byline',
    'ARCHIVES_SETTINGS' => $db_prefix.'archives',
    'SEO_SETTINGS'      => $db_prefix.'seo',
    'COMPAT_SETTINGS'   => $db_prefix.'compat',
    'STRINGS_SETTINGS'  => $db_prefix.'strings',
    'DIR'       => $dir_path,
    'URL'       => plugins_url( '/', __DIR__ ),
    'BASENAME'  => plugin_basename( $dir_path ) . '/' . $dashed_name . '.php',
    'NAMESPACE' => str_replace( ' ', '', ucwords( $config['id'] ) ),
    'LICENSE'   => did_action( 'authorship/pro/loaded' ) ? 'pro' : 'free',
    'HAS_PRO'   => file_exists( WP_PLUGIN_DIR . '/molongui-authorship-pro/molongui-authorship-pro.php' ) ? true : false,
    'IS_PRO'    => did_action( 'authorship/pro/loaded' ),
);
if ( isset( $dont_load_constants ) and $dont_load_constants )
{
    unset( $dont_load_constants );
    return;
}
$constant_prefix = strtoupper( $db_prefix );
foreach ( $constants as $const => $value )
{
    $const = $constant_prefix . $const;
    if ( !defined( $const ) ) define( $const, $value );
}