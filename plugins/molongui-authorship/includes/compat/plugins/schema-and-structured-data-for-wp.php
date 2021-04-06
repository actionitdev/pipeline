<?php

use Molongui\Authorship\Includes\Post;
defined( 'ABSPATH' ) or exit;
add_filter( 'get_the_author_display_name', function( $default )
{
    $i  = 4;
    $fn = 'saswp_author_output';
    $dbt = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 6 );
    if ( isset( $dbt[$i]['function'] ) and ( $dbt[$i]['function'] == $fn ) )
    {
        $post = new Post();
        return $post->filter_archive_title( $default );
    }
    return $default;
}, 10, 2 );
add_filter( 'molongui_authorship_do_filter_link', function( $default, &$args )
{
    $i  = 4;
    $fn = 'saswp_author_output';
    if ( ( is_author() or is_guest_author() ) and isset( $args['dbt'][$i]['function'] ) and ( $args['dbt'][$i]['function'] == $fn ) )
    {
        $args['link'] = $args['class']->filter_archive_link( $args['link'] );
        return true;
    }
    return $default;
}, 10, 2 );
add_filter( 'saswp_modify_breadcrumb_output', function( $input )
{
    if ( is_author() or is_guest_author() )
    {
        $post = new Post();
        $input['@id'] = $post->filter_archive_link( $input['@id'] ).'#breadcrumb';
        $input['itemListElement']['1']['item']['@id']  = $post->filter_archive_link( $input['itemListElement']['1']['@id'] );
        $input['itemListElement']['1']['item']['name'] = $post->filter_archive_title( $input['itemListElement']['1']['name'] );
    }

    return $input;
});