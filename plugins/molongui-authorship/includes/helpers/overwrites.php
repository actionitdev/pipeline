<?php

use Molongui\Authorship\Includes\Post;
use Molongui\Authorship\Includes\Author;
defined( 'ABSPATH' ) or exit;
$settings = molongui_get_plugin_settings( MOLONGUI_AUTHORSHIP_ID, array( 'main', 'byline' ) );
if ( molongui_authorship_byline_takeover() )
{
    if ( !function_exists( 'get_user_by' ) )
    {
        function get_user_by( $field, $value )
        {
            $userdata = WP_User::get_data_by( $field, $value );

            if ( !$userdata )
            {
                global $wp_query;
                if ( isset( $wp_query ) and !empty( $wp_query->is_guest_author ) and !empty( $wp_query->guest_author_id ) )
                {
                    $user = new WP_User();
                    $user->ID = $wp_query->guest_author_id;
                    goto goto_label_guest_author_archive_page;
                }
                else return false;
            }

            $user = new WP_User;
            $user->init( $userdata );
            global $pagenow;
            if ( molongui_is_request( 'admin' ) or $pagenow == 'wp-login.php' or $field == 'login' ) return $user;
            global $in_comment_loop;
            if ( $in_comment_loop ) return $user;
            $dbt = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 8 );
            if ( empty( $dbt ) ) return $user;
            global $wp_query;
            global $post;
            $old_user = $user;
            $filter = true;
            $args = array( 'field' => $field, 'value' => $value, 'post' => $post, 'query' => $wp_query, 'dbt' => $dbt );

            /*!
             * PRIVATE FILTER.
             *
             * For internal use only. Not intended to be used by plugin or theme developers.
             * Future compatibility NOT guaranteed.
             *
             * Please do not rely on this filter for your custom code to work. As a private filter it is meant to be
             * used only by Molongui. It may be edited, renamed or removed from future releases without prior notice
             * or deprecation phase.
             *
             * If you choose to ignore this notice and use this filter, please note that you do so at on your own risk
             * and knowing that it could cause code failure.
             */
            list( $filter, $user ) = apply_filters( '_authorship/filter/get_user_by', array( true, $user ), $args );
            if ( !$filter ) return is_null( $user ) ? $old_user : $user;

            /*!
             * PRIVATE FILTER.
             *
             * For internal use only. Not intended to be used by plugin or theme developers.
             * Future compatibility NOT guaranteed.
             *
             * Please do not rely on this filter for your custom code to work. As a private filter it is meant to be
             * used only by Molongui. It may be edited, renamed or removed from future releases without prior notice
             * or deprecation phase.
             *
             * If you choose to ignore this notice and use this filter, please note that you do so at on your own risk
             * and knowing that it could cause code failure.
             */
            if ( apply_filters( '_authorship/cache_delete/get_user_by', true, $user, $args ) )
            {
                wp_cache_delete( $user->ID, 'users' );
                wp_cache_delete( $user->user_login, 'userlogins' );
                wp_cache_delete( $user->user_email, 'useremail' );
                wp_cache_delete( $user->user_nicename, 'userslugs' );
            }
            if ( is_object( $wp_query ) and is_guest_author() )
            {
                goto_label_guest_author_archive_page:
                if ( !isset( $args ) )
                {
                    $dbt = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 8 );
                    global $wp_query;
                    global $post;
                    $args = array( 'field' => $field, 'value' => $value, 'post' => $post, 'query' => $wp_query, 'dbt' => $dbt );
                }
                $guest_id = $wp_query->guest_author_id;
                $post_class = new Post();
                $author = new Author( $guest_id, 'guest' );
                $byline_name = false;
                if ( in_the_loop() and !empty( $post->ID ) ) $byline_name = true;

                /*!
                 * PRIVATE FILTER.
                 *
                 * For internal use only. Not intended to be used by plugin or theme developers.
                 * Future compatibility NOT guaranteed.
                 *
                 * Please do not rely on this filter for your custom code to work. As a private filter it is meant to be
                 * used only by Molongui. It may be edited, renamed or removed from future releases without prior notice
                 * or deprecation phase.
                 *
                 * If you choose to ignore this notice and use this filter, please note that you do so at on your own risk
                 * and knowing that it could cause code failure.
                 */
                $byline_name = apply_filters( '_authorship/get_user_by/guest/archive/loop', $byline_name, $args );

                if ( $byline_name )
                {
                    global $post;
                    $post_id = $post->ID;
                    $main_author = get_main_author( $post_id );
                    $author_m = new Author( $main_author->id, $main_author->type );
                }
                $user->guest_id         = $guest_id;
                $user->display_name     = $byline_name ? $post_class->filter_name( $post_id ) : $author->get_name();
                $user->user_url         = $author->get_meta( 'web' );
                $user->description      = $author->get_bio();
                $user->user_description = $user->description;
                $user->user_nicename    = $byline_name ? $author_m->get_slug() : $author->get_slug();
                $user->nickname         = $user->display_name;
                $user->user_email       = $author->get_mail();
                $user->first_name       = $author->get_meta( 'first_name' );
                $user->last_name        = $author->get_meta( 'last_name'  );
                $user->user_registered  = get_the_date( '', $guest_id );
                return $user;
            }
            elseif ( is_object( $wp_query ) and $wp_query->is_author /*and in_the_loop()*/ )
            {
                if ( !in_the_loop() )
                {
                    if ( !empty( $wp_query->query_vars['author'] ) ) $user = new WP_User( $wp_query->query_vars['author'] );

                    /*!
                     * PRIVATE FILTER.
                     *
                     * For internal use only. Not intended to be used by plugin or theme developers.
                     * Future compatibility NOT guaranteed.
                     *
                     * Please do not rely on this filter for your custom code to work. As a private filter it is meant to be
                     * used only by Molongui. It may be edited, renamed or removed from future releases without prior notice
                     * or deprecation phase.
                     *
                     * If you choose to ignore this notice and use this filter, please note that you do so at on your own risk
                     * and knowing that it could cause code failure.
                     */
                    if ( apply_filters( '_authorship/filter/get_user_by/archive/no_loop', true, $user, $args ) ) return $user;
                }
                global $post;
                if ( empty( $post ) or !$post->ID ) return $user;
                $post_id = apply_filters( 'authorship/override/get_user_by/archive/post_id', $post->ID, $post, $user, $dbt );
                $main_author = get_main_author( $post_id );
                $post_class = new Post();
                $author     = new Author( $main_author->id, $main_author->type );
                $user->ID            = $main_author->id; // We need to restore user ID that might have been altered above.
                $user->display_name  = $post_class->filter_name( $post_id );
                $user->user_nicename = $author->get_slug();
                $user->nickname      = $user->display_name;
                return $user;
            }
            elseif ( ( ( is_object( $wp_query ) and $wp_query->is_home ) )
                    or ( is_object( $wp_query ) and $wp_query->is_main_query() and get_option( 'page_for_posts' ) == $wp_query->get_queried_object_id() )
                    or ( is_object( $wp_query ) and $wp_query->is_singular and molongui_is_post_type_enabled( MOLONGUI_AUTHORSHIP_ID ) )
					or ( is_object( $wp_query ) and $wp_query->is_search )
                    or ( is_object( $wp_query ) and $wp_query->is_category )
                    or ( is_object( $wp_query ) and $wp_query->is_tag )
            ){
                global $post;
                if ( empty( $post ) or !$post->ID ) return $user;
                $post_id = apply_filters( 'molongui_authorship_override_get_user_by_post_id', $post->ID, $post, $user );
                $main_author = get_main_author( $post_id );
                $post_class   = new Post();
                $author_class = new Author( $main_author->id, $main_author->type );
                if ( is_multiauthor_post( $post_id ) )
                {
                    $user->display_name     = $post_class->filter_name( $post_id );
                    $user->user_url         = $post_class->filter_link( $user->user_url, $post_id );
                    $user->description      = '';
                    $user->user_description = $user->description;
                    $user->user_nicename    = $author_class->get_slug();
                    $user->nickname         = $user->display_name;
                    if ( isset( $dbt[1]['function'] ) and $dbt[1]['function'] != 'get_avatar_data' )
                    {
                        $user->user_email = $author_class->get_mail();
                    }
                    return $user;
                }
                elseif ( is_guest_post( $post_id ) )
                {
                    $user->guest_id         = $author_class->get_id();
                    $user->display_name     = $author_class->get_name();//$post_class->filter_name( $post_id );
                    $user->user_url         = $author_class->get_meta( 'web' );
                    $user->description      = $author_class->get_bio();
                    $user->user_description = $user->description;
                    $user->user_nicename    = $author_class->get_slug();
                    $user->nickname         = $user->display_name;
                    if ( isset( $dbt[1]['function'] ) and $dbt[1]['function'] != 'get_avatar_data' )
                    {
                        $user->user_email = $author_class->get_mail();
                    }
                    return $user;
                }
            }
            return $user;
        }
    }
}
if ( molongui_authorship_is_feature_enabled( 'multi' ) )
{
    add_action( 'pre_get_posts', 'molongui_filter_user_posts', 999 );
    function molongui_filter_user_posts( $wp_query )
    {
        if ( isset( $wp_query->is_guest_author )
             or ( molongui_is_request( 'admin' ) )
             or ( !$wp_query->is_main_query() and apply_filters_ref_array( 'molongui_edit_main_query_only', array( true, &$wp_query ) ) )
        ) return;
        if ( $wp_query->is_author() )
        {
            $meta_query = $wp_query->get( 'meta_query' );
            if ( !is_array( $meta_query ) and empty( $meta_query ) ) $meta_query = array();
            $author = get_users( array( 'nicename' => $wp_query->query_vars['author_name'] ) );
            if ( !$author ) return;
            $meta_query[] = array
            (
                'relation' => 'OR',
                array
                (
                    'key'     => '_molongui_author',
                    'compare' => 'NOT EXISTS',
                ),
                array
                (
                    'relation' => 'AND',
                    array
                    (
                        'key'     => '_molongui_author',
                        'compare' => 'EXISTS',
                    ),
                    array
                    (
                        'key'     => '_molongui_author',
                        'value'   => 'user-'.$author[0]->ID,
                        'compare' => '==',
                    ),
                ),
            );
            $wp_query->set( 'meta_query', $meta_query );
        }
    }
    add_filter( 'posts_where', 'molongui_remove_author_from_where_clause', 10, 2 );
    function molongui_remove_author_from_where_clause( $where, $wp_query )
    {
        if ( isset( $wp_query->is_guest_author )
             or ( molongui_is_request( 'admin' ) )
             or ( !$wp_query->is_main_query() and apply_filters_ref_array( 'molongui_edit_main_query_only', array( true, &$wp_query ) ) )
        ) return $where;
        if ( $wp_query->is_author() )
        {
            if ( !empty( $wp_query->query_vars['author'] ) )
            {
                global $wpdb;
                $where = str_replace( ' AND '.$wpdb->posts.'.post_author IN ('.$wp_query->query_vars['author'].')', '', $where );
                $where = str_replace( ' AND ('.$wpdb->posts.'.post_author = '.$wp_query->query_vars['author'].')' , '', $where );
                $where = str_replace( $wpdb->postmeta.'.post_id IS NULL ', '( '.$wpdb->postmeta.'.post_id IS NULL AND '.$wpdb->posts.'.post_author = '.$wp_query->query_vars['author'].' )', $where );
            }
        }
        return $where;
    }
}