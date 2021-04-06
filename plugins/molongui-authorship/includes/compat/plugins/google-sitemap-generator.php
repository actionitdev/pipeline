<?php
defined( 'ABSPATH' ) or exit;
add_filter( 'molongui_authorship_dont_filter_link', function( $default, $link, $dbt )
{
    if ( isset( $dbt[4]['function'] ) and $dbt[4]['function'] == 'BuildAuthors' and isset( $dbt[4]['class'] ) and $dbt[4]['class'] == 'GoogleSitemapGeneratorStandardBuilder' ) return true;
    return $default;
}, 10, 3 );