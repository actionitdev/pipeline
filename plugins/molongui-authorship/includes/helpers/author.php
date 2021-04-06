<?php

use Molongui\Authorship\Includes\Author;
use Molongui\Authorship\Includes\Update_Post_Counters;
defined( 'ABSPATH' ) or exit;
if ( !function_exists( 'is_guest_author' ) )
{
    function is_guest_author()
    {
        global $wp_query;

        if ( !isset( $wp_query ) )
        {
            _doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
            return false;
        }

        return isset( $wp_query->is_guest_author ) ? $wp_query->is_guest_author : false;
    }
}
if ( !function_exists( 'molongui_is_guest' ) )
{
    function molongui_is_guest( $author = null )
    {
        if ( empty( $author ) ) return false;
        if ( $author instanceof \WP_User ) return false;
        if ( $author instanceof \WP_Post ) return true;
        if ( is_object( $author ) ) return ( ( !empty( $author->type ) and $author->type == 'guest' ) ? true : false );
        if ( is_string( $author ) ) if ( strncmp( $author, 'guest', strlen( 'guest' ) ) === 0 ) return true;
        return false;
    }
}
if ( !function_exists( 'has_local_avatar' ) )
{
    function has_local_avatar( $author_id = null, $author_type = 'user' )
    {
        if ( empty( $author_id ) ) return false;

        switch( $author_type )
        {
            case 'user':
                $img = get_user_meta( $author_id, 'molongui_author_image_url', true );
                return ( !empty( $img ) ? true : false );

            case 'guest':
                return ( has_post_thumbnail( $author_id ) ? true : false );
        }

        return false;
    }
}
if ( !function_exists( 'molongui_get_author_by' ) )
{
    function molongui_get_author_by( $field, $value, $type = 'user', $meta = true )
    {
        if ( $type == 'user' )
        {
            $user_query = new WP_User_Query
            (
                array
                (
                    'search'        => $value,
                    'search_fields' => array( $field ),
                )
            );
            $user = $user_query->get_results();

            return ( empty( $user['0'] ) ? false : $user['0'] );
        }
        elseif ( $type == 'guest' )
        {
            $config = include MOLONGUI_AUTHORSHIP_DIR . 'config/config.php';
            if ( $meta )
            {
                $args = array
                (
                    'post_type'  => $config['cpt']['guest'],
                    'meta_query' => array
                    (
                        array
                        (
                            'key'     => $field,
                            'value'   => $value,
                            'compare' => '=',
                        ),
                    ),
                    'site_id'    => get_current_blog_id(),
                    'language'   => molongui_get_language(),
                );
            }
            else
            {
                $args = array
                (
                    $field      => $value,
                    'post_type' => $config['cpt']['guest'],
                    'site_id'   => get_current_blog_id(),
                    'language'  => molongui_get_language(),
                );
            }
            $hash  = md5( serialize( $args ) );
            $key   = 'guests' . '_' . $hash;
            $guest = wp_cache_get( $key, MOLONGUI_AUTHORSHIP_NAME );
            if ( false === $guest )
            {
                $guest = new WP_Query( $args );
                wp_cache_set( $key, $guest, MOLONGUI_AUTHORSHIP_NAME );
                $db_key = MOLONGUI_AUTHORSHIP_DB_PREFIX . 'cache_guests';
                $hashes = get_option( $db_key, array() );
                $update = update_option( $db_key, !in_array( $hash, $hashes ) ? array_merge( $hashes, array( $hash ) ) : $hashes, true );
            }
            if ( $guest->have_posts() ) return ( empty( $guest->posts['0'] ) ? false : $guest->posts['0'] );
        }
        return false;
    }
}
if ( !function_exists( 'molongui_get_author_type_by_nicename' ) )
{
    function molongui_get_author_type_by_nicename( $nicename )
    {
        if ( $guest = molongui_get_author_by( 'name', $nicename, 'guest', false ) )
        {
            return 'guest';
        }
        elseif ( $author = molongui_get_author_by( 'user_nicename', $nicename ) )
        {
            return 'user';
        }
        return 'not_found';
    }
}
function molongui_find_authors()
{
    $authors = array();
    global $wp_query;
    global $post;
    if ( empty( $post ) or !$post->ID or $post->ID == 0 )
    {
        $post = $wp_query->get_queried_object();//$post = get_queried_object();
    }
    $post_type  = get_post_type( $post );
    $post_types = molongui_supported_post_types( MOLONGUI_AUTHORSHIP_ID, 'all' );
    if ( !empty( $wp_query->query_vars['guest-author-name'] ) )
    {
        if ( $guest = molongui_get_author_by( 'name', $wp_query->query_vars['guest-author-name'], 'guest', false ) )
        {
            $authors[0]       = new stdClass();
            $authors[0]->id   = (int)$guest->ID;
            $authors[0]->type = 'guest';
            $authors[0]->ref  = 'guest-'.$guest->ID;
        }
        else
        {
            if ( $user = molongui_get_author_by( 'user_nicename', $wp_query->query_vars['guest-author-name'] ) )
            {
                $authors[0]       = new stdClass();
                $authors[0]->id   = (int)$user->ID;
                $authors[0]->type = 'user';
                $authors[0]->ref  = 'user-'.$user->ID;
            }
        }
    }
    elseif ( is_author() and !empty( $wp_query->query_vars['author_name'] ) )
    {
        $authors[0]       = new stdClass();
        $authors[0]->id   = 0;
        $authors[0]->type = 'user';
        $authors[0]->ref  = 'user-0';
        if ( $user = molongui_get_author_by( 'user_nicename', $wp_query->query_vars['author_name'] ) )
        {
            $authors[0]->id  = (int)$user->ID;
            $authors[0]->ref = 'user-'.$user->ID;
        }
    }
    elseif ( in_array( $post_type, $post_types ) )
    {
        $authors = get_post_authors( $post->ID );
    }
    if ( empty( $authors ) or $authors[0]->id == 0 ) return false;
    return $authors;
}
if ( !function_exists( 'molongui_get_users' ) )
{
    function molongui_get_users( $args = null )
    {
        $defaults = array
        (
            'role__in' => apply_filters( 'authorship/user/roles', array( 'administrator', 'editor', 'author', 'contributor' ) ),
            'include'  => '',
            'exclude'  => '',
            'order'    => 'asc',
            'orderby'  => 'name',
            'site_id'  => get_current_blog_id(),
            'language' => molongui_get_language(),
        );

        $parsed_args = wp_parse_args( $args, $defaults );
        $parsed_args['order'] = strtolower( $parsed_args['order'] );
        $hash  = md5( serialize( $parsed_args ) );
        $key   = 'users' . '_' . $hash;
        $users = wp_cache_get( $key, MOLONGUI_AUTHORSHIP_NAME );

        if ( false === $users )
        {
            $users = get_users( $parsed_args ); // Array of WP_User objects.
            wp_cache_set( $key, $users, MOLONGUI_AUTHORSHIP_NAME );
            $db_key = MOLONGUI_AUTHORSHIP_DB_PREFIX . 'cache_users';
            $hashes = get_option( $db_key, array() );
            $update = update_option( $db_key, !in_array( $hash, $hashes ) ? array_merge( $hashes, array( $hash ) ) : $hashes, true );
        }
        if ( is_array( $parsed_args['role__in'] ) and in_array( 'molongui_no_role', $parsed_args['role__in'] ) )
        {
            $no_role_ids = wp_get_users_with_no_role(); // Array of user IDs as strings.

            if ( !empty( $no_role_ids ) )
            {
                $no_role_users = array();
                add_filter( '_authorship/filter/get_user_by', '__return_list_false' );
                foreach ( $no_role_ids as $no_role_id )
                {
                    $no_role_users[$no_role_id] = get_user_by( 'id', $no_role_id );
                }
                remove_filter( '_authorship/filter/get_user_by', '__return_list_false' );
                $users = array_merge( $users, $no_role_users );
                usort( $users, function($a, $b) use ( $parsed_args ) { return strcasecmp( $a->$parsed_args['orderby'], $b->$parsed_args['orderby'] ); } );
                if ( $parsed_args['order'] == 'desc' ) $authors = array_reverse( $users );
            }
        }
        return $users;
    }
}
if ( !function_exists( 'molongui_get_guests' ) )
{
    function molongui_get_guests( $args = null )
    {
        $config = require MOLONGUI_AUTHORSHIP_DIR . 'config/config.php';
        $defaults = array
        (
            'post_type'      => $config['cpt']['guest'],//'guest_author',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'post__in'       => '',
            'post__not_in'   => '',
            'fields'         => 'all',
            'order'          => 'ASC',
            'orderby'        => 'title',
            'no_found_rows'  => true,
            'dropdown'       => false,
            'site_id'        => get_current_blog_id(),
            'language'       => molongui_get_language(),
        );

        $parsed_args = wp_parse_args( $args, $defaults );
        $parsed_args['post_type'] = $config['cpt']['guest'];
        $hash   = md5( serialize( $parsed_args ) );
        $key    = 'guests' . '_' . $hash;
        $guests = wp_cache_get( $key, MOLONGUI_AUTHORSHIP_NAME );

        if ( false === $guests )
        {
            $guests = new WP_Query( $parsed_args );
            wp_cache_set( $key, $guests, MOLONGUI_AUTHORSHIP_NAME );
            $db_key = MOLONGUI_AUTHORSHIP_DB_PREFIX . 'cache_guests';
            $hashes = get_option( $db_key, array() );
            $update = update_option( $db_key, !in_array( $hash, $hashes ) ? array_merge( $hashes, array( $hash ) ) : $hashes, true );
        }
        if ( $parsed_args['dropdown'] )
        {
            global $post;
            $post_authors = get_post_authors( $post->ID, 'id' );
            $output = '';
            if ( $guests->have_posts() )
            {
                $output .= '<select name="_molongui_author" class="multiple">';
                foreach( $guests->posts as $guest )
                {
                    $output .= '<option value="' . $guest->ID . '" ' . ( in_array( $guest->ID, $post_authors ) ? 'selected' : '' ) . '>' . $guest->post_title . '</option>';
                }
                $output .= '</select>';
                $output .= '<div><ul id="molongui-authors" class="sortable"></ul></div>';
            }
            return $output;
        }
        return $guests->posts;
    }
}
if ( !function_exists( 'molongui_get_authors' ) )
{
    function molongui_get_authors( $type = 'authors', $include_users = array(), $exclude_users = array(), $include_guests = array(), $exclude_guests = array(), $order = 'asc', $orderby = 'name', $get_data = false, $with_posts = false, $post_types = array( 'post' ) )
    {
        $authors  = array();
        $settings = get_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS );
        if ( isset( $orderby ) and $orderby == 'post_count' ) $with_posts = true;
        if ( $with_posts ) $get_data = true;
        if ( $type == 'authors' or $type == 'users' )
        {
            $args = array
            (
                'include' => $include_users,
                'exclude' => $exclude_users,
                'order'   => $order,
                'orderby' => $orderby,
            );
            $users = molongui_get_users( $args ); // Array of WP_User objects.
            if ( $get_data )
            {
                foreach ( $users as $user )
                {
                    $author    = new Author( $user->ID, 'user', $user );
                    $authors[] = $author->get_data();
                    if ( $with_posts )
                    {
                        end( $authors );
                        $key = key( $authors );
                        if ( !authorship_author_has_posts( $authors[$key], $post_types ) )
                        {
                            unset( $authors[$key] );
                            continue;
                        }
                    }
                }
            }
            else
            {
                foreach ( $users as $user ) $authors[] = array( 'id' => $user->ID, 'type' => 'user', 'ref' => 'user-'.$user->ID, 'name' => $user->display_name );
            }
        }
        if ( ( $type == 'authors' or $type == 'guests' ) and !empty( $settings['enable_guest_authors'] ) )
        {
            if ( isset( $orderby ) and $orderby == 'include' ) $orderby = 'post__in';
            $guests = molongui_get_guests( array( 'post__in' => $include_guests, 'post__not_in' => $exclude_guests, 'order' => $order, 'orderby' => $orderby ) ); // Array of stdClass objects.
            if ( $get_data )
            {
                foreach ( $guests as $guest )
                {
                    $author    = new Author( $guest->ID, 'guest', $guest );
                    $authors[] = $author->get_data();
                    if ( $with_posts )
                    {
                        end( $authors );
                        $key = key( $authors );
                        if ( !authorship_author_has_posts( $authors[$key], $post_types ) )
                        {
                            unset( $authors[$key] );
                            continue;
                        }
                    }
                }
            }
            else
            {
                foreach ( $guests as $guest ) $authors[] = array( 'id' => $guest->ID, 'type' => 'guest', 'ref' => 'guest-'.$guest->ID, 'name' => $guest->post_title );
            }
        }
        if ( in_array( $orderby, array( 'include', 'post__in' ) ) ) return $authors;
        if ( $orderby == 'post_count' )
        {
            usort( $authors, function ( $a, $b ) use ( $orderby, $post_types )
            {
                return $a[$orderby][$post_types[0]] - $b[$orderby][$post_types[0]];
            });
        }
        else
        {
            usort( $authors, function ( $a, $b ) use ( $orderby )
            {
                return strcasecmp( $a[$orderby], $b[$orderby] );
            });
        }
        if ( $order == 'desc' ) $authors = array_reverse( $authors );
        return $authors;
    }
}
if ( !function_exists( 'authorship_author_has_posts' ) )
{
    function authorship_author_has_posts( $author, $post_types )
    {
        $has_posts = false;
        foreach ( $post_types as $post_type )
        {
            if ( !empty( $author['post_count'][$post_type] ) )
            {
                $has_posts = true;
                break;
            }
        }

        return $has_posts;
    }
}
if ( !function_exists( 'authorship_author_name_exists' ) )
{
    function authorship_author_name_exists( $id, $type )
    {
        global $wpdb;
        $user_displayname_check  = false;
        $guest_displayname_check = false;
        $config = require MOLONGUI_AUTHORSHIP_DIR . 'config/config.php';
        $author = new Author( $id, $type );
        $name   = $author->get_name();
        if ( $type == 'user' )
        {
            $user_displayname_check  = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->users WHERE display_name = %s AND ID != '{$id}' LIMIT 1", $name ) );
            $guest_displayname_check = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = '{$config['cpt']['guest']}' LIMIT 1", $name ) );
        }
        else
        {
            $user_displayname_check  = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->users WHERE display_name = %s LIMIT 1", $name ) );
            $guest_displayname_check = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = '{$config['cpt']['guest']}' AND ID != '{$id}' LIMIT 1", $name ) );
        }
        if ( !$user_displayname_check and !$guest_displayname_check ) return false;
        if (  $user_displayname_check and !$guest_displayname_check ) return 'user';
        if ( !$user_displayname_check and  $guest_displayname_check ) return 'guest';
        if (  $user_displayname_check and  $guest_displayname_check ) return 'both';
    }
}
if ( !function_exists( 'authorship_update_post_counters' ) )
{
    function authorship_update_post_counters( $post_types = 'enabled', $authors = null )
    {
        if ( is_array( $post_types ) )
        {
            $pt = $post_types;
        }
        elseif ( is_string( $post_types ) and !in_array( $post_types, array( 'all', 'enabled' ) ) )
        {
            $pt = array( $post_types );
        }
        else
        {
            switch ( $post_types )
            {
                case 'all':

                    $pt = \molongui_get_post_types();

                break;

                case 'enabled':
                default:

                    $pt = \molongui_supported_post_types( MOLONGUI_AUTHORSHIP_ID, 'all' );

                break;
            }
            if ( empty( $pt ) ) $pt = array( 'post', 'page' );
            else $pt = array_unique( array_merge( $pt, array( 'post', 'page' ) ) );
        }
        if ( !empty( $authors ) )
        {
            $updater = new Update_Post_Counters();
            $updater->handle_some( $pt, (array)$authors );
        }
        else
        {
            $updater = new Update_Post_Counters();
            $updater->handle_all( $pt );
        }
    }
}
if ( !function_exists( 'authorship_increment_post_counter' ) )
{
    function authorship_increment_post_counter( $post_types = null, $authors = null )
    {
        $updater = new Update_Post_Counters();
        $updater->increment_counter( $post_types, $authors );
    }
}
if ( !function_exists( 'authorship_decrement_post_counter' ) )
{
    function authorship_decrement_post_counter( $post_types = null, $authors = null )
    {
        $updater = new Update_Post_Counters();
        $updater->decrement_counter( $post_types, $authors );
    }
}