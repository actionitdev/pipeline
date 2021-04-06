<?php
defined( 'ABSPATH' ) or exit;
add_filter( 'molongui_authorship_dont_render_author_box', function( $leave )
{
    if ( $leave ) return $leave;
    $dbt = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 10 );
    if ( in_the_loop() and isset( $dbt[7]['function'] ) and $dbt[7]['function'] == "publisher_inject_location" ) return true;
    return false;
});
add_filter( 'molongui_authorship_dont_filter_link', function( $default, $link, $dbt )
{
    if ( is_guest_author() ) return $default;
    $i     = 4;
    $fn    = 'add_user_archive_items';
    $class = 'BF_Breadcrumb';
    if ( isset( $dbt[$i]['function'] ) and $dbt[$i]['function'] == $fn and isset( $dbt[$i]['class'] ) and $dbt[$i]['class'] == $class ) return true;
    return $default;
}, 10, 3 );
add_filter( 'authorship/byline/dom_tree', function()
{
   $dom_tree = '<i class="post-author author">{%ma_authorName}</i>';

   return $dom_tree;
});