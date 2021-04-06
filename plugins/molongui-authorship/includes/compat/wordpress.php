<?php
defined( 'ABSPATH' ) or exit;
add_filter( '_authorship/filter/get_user_by', function( $data, $args )
{
    list( $filter, $user ) = $data;
    $core = array
    (
        'setup_userdata',                    // wp-includes/user.php
        'wp_update_user',                    // wp-includes/user.php
        'wp_insert_user',                    // wp-includes/user.php
        'update_user_meta',                  // wp-includes/user.php
        'retrieve_password',                 // wp-login.php
        'get_pages',                         // wp-inlcudes/post.php
        'wp_validate_auth_cookie',           // wp-includes/pluggable.php
        'check_comment',                     // wp-includes/comment.php
        'get_user_locale',                   // wp-includes/I10n.php
        'wp_authenticate_username_password', // wp-includes/user.php
        'wp_authenticate_email_password',    // wp-includes/user.php
        'username_exists',                   // wp-includes/user.php
        'email_exists',                      // wp-includes/user.php
        'check_password_reset_key',          // wp-includes/user.php
        'wp_user_personal_data_exporter',    // wp-includes/user.php
        'wp_create_user_request',            // wp-includes/user.php
        'get_object_subtype',                // wp-includes/meta.php
        'wpmu_signup_blog_notification',     // wp-includes/ms-functions.php
        'wpmu_signup_user_notification',     // wp-includes/ms-functions.php
        'is_user_spammy',                    // wp-includes/ms-functions.php
        'get_posts',                         // wp-includes/class-wp-query.php
        'wp_media_personal_data_exporter',   // wp-includes/media.php
        'create_item',                       // wp-includes/rest-api/endpoints/class-wp-rest-users-controller.php
        'update_item',                       // wp-includes/rest-api/endpoints/class-wp-rest-users-controller.php
    );
    if ( array_intersect( $core, array_column( $args['dbt'], 'function' ) ) ) $filter = false;
    return array( $filter, $user );
}, 10, 2 );
add_filter( 'molongui_authorship_do_filter_link', function( $leave, &$args )
{
    if ( $leave ) return $leave;
    if ( ( is_author() or is_guest_author() ) and isset( $args['dbt'][4]['function'] ) and ( $args['dbt'][4]['function'] == 'get_author_feed_link' ) )
    {
        $args['link'] = $args['class']->filter_archive_link( $args['link'] );
        return true;
    }
    return false;
}, 10, 2 );
add_filter( 'authorship/get_avatar_data/skip', function( $default, $args, $dbt )
{
    $i  = 4;
    $fn = 'post_comment_form_avatar';
    if ( isset( $dbt[$i]['function'] ) and $dbt[$i]['function'] == $fn ) return true;
    return $default;
}, 10, 3 );
add_filter( 'authorship/get_avatar_data/skip', function( $default, $args, $dbt )
{
    if ( !is_admin() ) return $default;
    $i    = 4;
    $fn   = 'get_avatar';
    $file = '/wp-admin/options-discussion.php';
    if ( isset( $dbt[$i]['function'] ) and $dbt[$i]['function'] == $fn and
         isset( $dbt[$i]['file'] ) and substr_compare( $dbt[$i]['file'], $file, strlen( $dbt[$i]['file'] )-strlen( $file ), strlen( $file ) ) === 0
    ) return true;
    return $default;
}, 10, 3 );
add_filter( '_authorship/get_avatar_data/filter/author', function( $author, $id_or_email, $dbt )
{
    $i    = 5;
    $fn_1 = 'wp_admin_bar_my_account_menu';
    $fn_2 = 'wp_admin_bar_my_account_item';
    if ( ( isset( $dbt[$i]['function'] ) and ( $dbt[$i]['function'] == $fn_1 or $dbt[$i]['function'] == $fn_2 ) ) )
    {
        $author       = new stdClass();
        $author->type = 'user';
        $author->user = wp_get_current_user();
    }
    return $author;
}, 10, 3 );