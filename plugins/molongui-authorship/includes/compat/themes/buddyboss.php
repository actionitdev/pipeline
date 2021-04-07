<?php
defined( 'ABSPATH' ) or exit;
add_filter( '_authorship/get_avatar_data/filter/author', function( $author, $id_or_email, $dbt )
{
    $fn     = 'get_avatar';
    $file_1 = '/template-parts/header-aside.php';
    $file_2 = '/template-parts/header-mobile.php';
    $file_3 = '/themes/buddyboss-theme/comments.php';
    if ( $i = array_search( $fn, array_column( $dbt, 'function' ) ) )
    {
        if ( ( isset( $dbt[$i]['file'] ) and substr_compare( $dbt[$i]['file'], $file_1, strlen( $dbt[$i]['file'] )-strlen( $file_1 ), strlen( $file_1 ) ) === 0 ) or
             ( isset( $dbt[$i]['file'] ) and substr_compare( $dbt[$i]['file'], $file_2, strlen( $dbt[$i]['file'] )-strlen( $file_2 ), strlen( $file_2 ) ) === 0 ) or
             ( isset( $dbt[$i]['file'] ) and substr_compare( $dbt[$i]['file'], $file_3, strlen( $dbt[$i]['file'] )-strlen( $file_3 ), strlen( $file_3 ) ) === 0 )
        ) {
            if ( !is_admin() )
            {
                $author       = new stdClass();
                $author->type = 'user';
                $author->user = wp_get_current_user();
            }
        }
    }
    return $author;
}, 10, 3 );
add_filter( '_authorship/get_avatar_data/filter/author', function( $author, $id_or_email, $dbt )
{
    global $is_related_posts;
    $i      = 4;
    $fn     = 'get_avatar';
    $file_1 = '/template-parts/entry-meta.php';
    $file_2 = '/template-parts/author-box.php';
    if ( !$is_related_posts and
         !is_admin() and
         isset( $dbt[$i]['function'] ) and ( $dbt[$i]['function'] == $fn ) and
         ( ( isset( $dbt[$i]['file'] ) and substr_compare( $dbt[$i]['file'], $file_1, strlen( $dbt[$i]['file'] )-strlen( $file_1 ), strlen( $file_1 ) ) === 0 ) or
           ( isset( $dbt[$i]['file'] ) and substr_compare( $dbt[$i]['file'], $file_2, strlen( $dbt[$i]['file'] )-strlen( $file_2 ), strlen( $file_2 ) ) === 0 ) )
    )
    {
        global $post;
        if ( is_guest_post( $post->ID ) )
        {
            $main = get_main_author( $post->ID );
            $author_class = new \Molongui\Authorship\Includes\Author( $main->id, 'guest' );
            $author->type  = 'guest';
            $author->guest = $author_class->get();
            if ( isset( $author->user ) ) unset( $author->user );
        }
    }
    return $author;
}, 10, 3 );
add_filter( 'authorship/get_avatar_data/skip', function( $default, $args, $dbt )
{
    global $is_related_posts;
    $i    = 4;
    $fn   = 'get_avatar';
    $file = '/template-parts/entry-meta.php';
    if ( $is_related_posts and
         !is_admin() and
         isset( $dbt[$i]['function'] ) and ( $dbt[$i]['function'] == $fn ) and
         isset( $dbt[$i]['file'] ) and substr_compare( $dbt[$i]['file'], $file, strlen( $dbt[$i]['file'] )-strlen( $file ), strlen( $file ) ) === 0
    )
    {
        return true;
    }
    return $default;//$author;
}, 10, 3 );