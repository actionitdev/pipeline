<?php
defined( 'ABSPATH' ) or exit;
add_filter( '_authorship/filter/get_user_by', function( $data, $args )
{
    list( $filter, $user ) = $data;
    if ( ( isset( $args['dbt'][1]['function'] ) and $args['dbt'][1]['function'] == 'get_user_by' and isset( $args['dbt'][1]['class'] ) and $args['dbt'][1]['class'] == 'MeprUtils' ) ) $filter = false;
    return array( $filter, $user );
}, 10, 2 );
add_filter( '_authorship/filter/get_user_by', function( $data, $args )
{
    list( $filter, $user ) = $data;
    if ( array_intersect( array( 'MeprUser', 'MeprLoginCtrl', 'MeprAppCtrl' ), array_column( $args['dbt'], 'class' ) ) ) $filter = false;
    return array( $filter, $user );
}, 10, 2 );
if ( is_plugin_active( 'memberpress-corporate/main.php' ) )
{
    add_filter( '_authorship/filter/get_user_by', function( $data, $args )
    {
        list( $filter, $user ) = $data;
        if ( ( isset( $args['dbt'][5]['function'] ) and $args['dbt'][5]['function'] == "current_user_has_access" and isset( $args['dbt'][5]['class'] ) and $args['dbt'][5]['class'] == 'MPCA_Corporate_Account' ) ) $filter = false;
        return array( $filter, $user );
    }, 10, 2 );
}