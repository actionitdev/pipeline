<?php
defined( 'ABSPATH' ) or exit;
$fw_const = array
(
	'NAME'          => 'Molongui Common Framework',
	'ID'            => 'Common Framework',
	'VERSION'       => '1.6.0',
	'DIR'           => plugin_dir_path( plugin_dir_path( __FILE__ ) ),
	'URL'           => plugins_url( '/', plugin_dir_path( __FILE__ ) ),
	'URL_WEB'       => 'https://www.molongui.com/',
	'URL_DOCS'      => 'https://www.molongui.com/docs',
	'URL_SUPPORT'   => 'https://www.molongui.com/support',
    'URL_MYACCOUNT' => 'https://www.molongui.com/my-account',
	'URL_DEMOS'     => 'https://demos.molongui.com/',
	'MAIL_SUPPORT'  => 'support@molongui.com',
);
foreach( $fw_const as $const => $value )
{
    $const = $constant_prefix . 'FW_' . $const;
	if( !defined( $const ) ) define( $const, $value );
}