<?php
defined( 'ABSPATH' ) or exit;

add_filter( '_authorship/get_user_by/guest/archive/loop', function( $byline, $args )
{
    if ( is_author() or is_guest_author() )
    {
        $fn = 'generate_postdata';
        if ( $key = array_search( $fn, array_column( $args['dbt'], 'function' ) ) )
        {
            $byline = false;
        }
    }

    return $byline;
}, 10, 2 );