<?php
defined( 'ABSPATH' ) or exit;
add_filter( '_authorship/filter/get_user_by', function( $data, $args )
{
    list( $filter, $user ) = $data;
    if ( isset( $args['dbt'][3]['function'] ) and $args['dbt'][3]['function'] == 'thrive_comments' ) $filter = false;
    return array( $filter, $user );
}, 10, 2 );
add_filter( 'molongui_authorship_dont_filter_the_author_display_name', function( $leave, $display_name, $user_id, $original_user_id, $post, $dbt )
{
    if ( $leave ) return $leave;
    if ( isset( $dbt[5]['function'] ) and $dbt[5]['function'] == 'thrive_comments' ) return true;
    return false;
}, 10, 6 );