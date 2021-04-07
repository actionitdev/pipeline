<?php
defined( 'ABSPATH' ) or exit;
add_filter( '_authorship/get_avatar_data/filter/author', function( $author, $id_or_email, $dbt )
{
    $i  = 5;
    $j  = 6;
    $fn = 'bp_wp_admin_bar_my_account_menu';
    if ( ( isset( $dbt[$i]['function'] ) and $dbt[$i]['function'] == $fn ) or
		 ( isset( $dbt[$j]['function'] ) and $dbt[$j]['function'] == $fn ) )

    {
        $author       = new stdClass();
        $author->type = 'user';
        $author->user = wp_get_current_user();
    }
    return $author;
}, 10, 3);