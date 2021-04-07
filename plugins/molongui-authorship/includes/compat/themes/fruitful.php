<?php
defined( 'ABSPATH' ) or exit;
add_filter( 'molongui_authorship_do_filter_name', function( $leave, &$args )
{
    if ( $leave ) return $leave;
    if ( isset( $args['dbt'][4]['function'] ) and ( $args['dbt'][4]['function'] == 'get_content_part' ) )
    {
        $args['display_name'] = $args['class']->filter_archive_title( $args['display_name'] );
        return true;
    }
    return false;
}, 10, 2 );
add_filter( 'molongui_authorship_do_filter_link', function( $leave, &$args )
{
    if ( $leave ) return $leave;
    if ( isset( $args['dbt'][4]['function'] ) and ( $args['dbt'][4]['function'] == 'get_content_part' ) )
    {
        $args['link'] = $args['class']->filter_archive_link( $args['link'] );
        return true;
    }
    return false;
}, 10, 2 );