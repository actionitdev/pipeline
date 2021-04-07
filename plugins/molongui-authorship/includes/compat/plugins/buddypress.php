<?php
defined( 'ABSPATH' ) or exit;
add_filter( '_authorship/filter/get_user_by', function( $data, $args )
{
    $i  = 5;
    $j  = 4;
    $fn_i = 'xprofile_filter_comments';
    $fn_j = 'bp_core_get_user_displaynames';
    list( $filter, $user ) = $data;
    if ( isset( $args['dbt'][$i]['function'] ) and $args['dbt'][$i]['function'] == $fn_i and isset( $args['dbt'][$j]['function'] ) and $args['dbt'][$j]['function'] == $fn_j ) $filter = false;
    return array( $filter, $user );
}, 10, 2 );
add_filter( 'molongui_authorship_dont_filter_the_author_display_name', function( $leave, $display_name, $user_id, $original_user_id, $post, $dbt )
{
    $fn = 'xprofile_filter_comments';
    if ( array_search( $fn, array_column( $dbt, 'function' ) ) ) return true;
    return false;
}, 10, 6 );
add_filter( '_authorship/filter/get_user_by', function( $data, $args )
{
    $i  = 2;
    $j  = 3;
    $k  = 4;
    $fn_i = 'get_the_author_meta';
    $fn_j = 'bp_core_get_username';
    $fn_k = 'bp_core_get_user_domain';
    list( $filter, $user ) = $data;
    if ( isset( $args['dbt'][$i]['function'] ) and $args['dbt'][$i]['function'] == $fn_i and
         isset( $args['dbt'][$j]['function'] ) and $args['dbt'][$j]['function'] == $fn_j and
         isset( $args['dbt'][$k]['function'] ) and $args['dbt'][$k]['function'] == $fn_k
       ) $filter = false;
    return array( $filter, $user );
}, 10, 2 );