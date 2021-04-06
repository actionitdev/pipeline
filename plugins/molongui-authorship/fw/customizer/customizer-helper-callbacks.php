<?php
defined( 'ABSPATH' ) or exit;
require_once molongui_get_constant( $this->plugin->id, 'DIR', true ) . 'includes/fw-helper-functions.php';
function molongui_sanitize_to_default( $input, $setting )
{
	return ( $this->plugin->is_pro ? $input : $setting->default );
}
function molongui_sanitize_color( $color )
{
	if ( empty( $color ) || is_array( $color ) ) return '';
	if ( false === strpos( $color, 'rgba' ) ) return sanitize_hex_color( $color );
	$color = str_replace( ' ', '', $color );
	sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
	return 'rgba('.$red.','.$green.','.$blue.','.$alpha.')';
}
function molongui_sanitize_premium_color( $input, $setting )
{
	$input = molongui_sanitize_color( $input );
	return molongui_sanitize_to_default( $input, $setting );
}
function molongui_sanitize_select( $input, $setting )
{
	$choices = $setting->manager->get_control( $setting->id )->choices;
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}
function molongui_sanitize_premium_select( $input, $setting )
{
	$input = molongui_sanitize_select( $input, $setting );
	return molongui_sanitize_to_default( $input, $setting );
}
function molongui_sanitize_radio( $input, $setting )
{
	return $input;
}
function molongui_sanitize_premium_radio( $input, $setting )
{
	$input = molongui_sanitize_radio( $input, $setting );
	return molongui_sanitize_to_default( $input, $setting );
}

// TODO...