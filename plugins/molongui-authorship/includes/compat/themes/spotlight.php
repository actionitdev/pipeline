<?php
defined( 'ABSPATH' ) or exit;
add_filter( '_authorship/filter/get_user_by/archive/no_loop', function( $default, $user, $args )
{
    $i  = 4;
    $fn = 'csco_get_post_meta';
    if ( isset( $args['dbt'][$i]['function'] ) and $args['dbt'][$i]['function'] == $fn ) return false;
    return $default;
}, 10, 3 );