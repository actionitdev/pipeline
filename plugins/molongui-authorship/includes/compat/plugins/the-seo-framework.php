<?php
defined( 'ABSPATH' ) or exit;
add_filter( '_authorship/filter/get_user_by', function( $data, $args )
{
    $i     = 3;
    $fn    = 'get_author_canonical_url';
    $class = 'The_SEO_Framework\Generate_Url';
    list( $filter, $user ) = $data;
    if ( isset( $args['dbt'][$i]['function'] ) and $args['dbt'][$i]['function'] == $fn and isset( $args['dbt'][$i]['class'] ) and $args['dbt'][$i]['class'] == $class ) $filter = false;
    return array( $filter, $user );
}, 10, 2 );

add_filter( 'molongui_authorship_dont_filter_link', function( $default, $link, $dbt )
{
    $i     = 4;
    $fn    = 'get_author_canonical_url';
    $class = 'The_SEO_Framework\Generate_Url';
    if ( isset( $dbt[$i]['function'] ) and $dbt[$i]['function'] == $fn and isset( $dbt[$i]['class'] ) and $dbt[$i]['class'] == $class ) return true;
    return $default;
}, 10, 3 );
