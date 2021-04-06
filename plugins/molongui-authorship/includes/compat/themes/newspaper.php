<?php
defined( 'ABSPATH' ) or exit;
add_filter( 'molongui_authorship_override_get_user_by_post_id', function( $post_id, $post, $user )
{
    $dbt = debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 7 );
    $i = 6;
    if ( isset( $dbt[$i]['function'] ) and $dbt[$i]['function'] == 'get_author' and isset( $dbt[$i]['class'] ) and $dbt[$i]['class'] == 'td_module' and isset( $dbt[$i]['object'] ) and isset( $dbt[$i]['object']->post ) and isset( $dbt[$i]['object']->post->ID ) ) return $dbt[$i]['object']->post->ID;
    return $post_id;
}, 10, 3 );
add_filter( 'molongui_authorship_filter_the_author_display_name_post_id', function( $post_id, $post, $display_name )
{
    $dbt = debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 8 );
    $i = 7;
    if ( isset( $dbt[$i]['function'] ) and $dbt[$i]['function'] == 'get_author' and isset( $dbt[$i]['class'] ) and $dbt[$i]['class'] == 'td_module' and isset( $dbt[$i]['object'] ) and isset( $dbt[$i]['object']->post ) and isset( $dbt[$i]['object']->post->ID ) ) return $dbt[$i]['object']->post->ID;
    return $post_id;
}, 10, 3 );
add_filter( 'molongui_authorship_bypass_original_user_id_if', function( $false )
{
    return true;
}, 10, 1 );
add_filter( 'molongui_authorship_filter_link_post_id', function( $post_id, $post, $link )
{
    $dbt = debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 9 );
    $i = 8;
    if ( isset( $dbt[$i]['function'] ) and $dbt[$i]['function'] == 'get_author' and isset( $dbt[$i]['class'] ) and $dbt[$i]['class'] == 'td_module' and isset( $dbt[$i]['object'] ) and isset( $dbt[$i]['object']->post ) and isset( $dbt[$i]['object']->post->ID ) ) return $dbt[$i]['object']->post->ID;
    return $post_id;
}, 10, 3 );
add_filter( 'molongui_authorship_dont_render_author_box', function( $leave )
{
    if ( $leave ) return $leave;
    if ( doing_action( 'tdc_footer' ) ) return true;
    return false;
});
/*
add_filter( '_authorship/filter/count/author_type', function( $type )
{
    if ( is_guest_author() ) return 'guest';
    return $type;
});*/