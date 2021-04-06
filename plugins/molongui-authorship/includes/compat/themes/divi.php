<?php
defined( 'ABSPATH' ) or exit;
add_filter( 'authorship/box/render/bypass_check', function()
{
    $dbt = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 8 );
    if ( isset( $dbt[7]['function'] ) and $dbt[7]['function'] == "et_theme_builder_frontend_render_post_content" ) return true;
    return false;
});
add_filter( 'molongui_authorship_do_filter_name', function( $leave, &$args )
{
    if ( $leave ) return $leave;
    if ( isset( $args['dbt'][3]['function'] ) and ( $args['dbt'][3]['function'] == 'get_the_author' ) and isset( $args['dbt'][4]['function'] ) and ( $args['dbt'][4]['function'] == 'et_builder_get_current_title' ) )
    {
        $args['display_name'] = $args['class']->filter_archive_title( $args['display_name'] );
        return true;
    }
    return false;
}, 10, 2 );
add_filter( 'molongui_edit_main_query_only', function( $default, &$query )
{
    if ( !$query->is_author() ) return $default;
    $dbt = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 11 );
    if ( empty( $dbt ) ) return $default;
    $i     = 9;
    $fn    = 'render';
    $class = 'ET_Builder_Module_Blog';
    if ( isset( $dbt[$i]['function'] ) and $dbt[$i]['function'] == $fn and isset( $dbt[$i]['class'] ) and $dbt[$i]['class'] == $class ) return false;
    return $default;
}, 10, 2 );