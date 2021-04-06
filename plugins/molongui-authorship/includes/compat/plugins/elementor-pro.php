<?php
defined( 'ABSPATH' ) or exit;
add_filter( '_authorship/filter/get_user_by/archive/no_loop', function( $default, $user, $args )
{
    $i     = 5;
    $fn    = 'render';
    $class = 'ElementorPro\Modules\Posts\Skins\Skin_Base';
    if ( isset( $args['dbt'][$i]['function'] ) and $args['dbt'][$i]['function'] == $fn and isset( $args['dbt'][$i]['class'] ) and $args['dbt'][$i]['class'] == $class ) return false;
    return $default;
}, 10, 3 );
/*
add_filter( '_authorship/filter/get_user_by/archive/no_loop', function( $default, $user, $args )
{
    $i     = 6;
    $fn    = 'render_avatar';
    $class = 'ElementorPro\Modules\Posts\Skins\Skin_Cards';
    if ( isset( $args['dbt'][$i]['function'] ) and $args['dbt'][$i]['function'] == $fn and isset( $args['dbt'][$i]['class'] ) and $args['dbt'][$i]['class'] == $class ) return false;
    return $default;
}, 10, 3 );
*/