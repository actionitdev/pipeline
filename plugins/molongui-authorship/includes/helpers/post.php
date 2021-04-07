<?php

use Molongui\Authorship\Includes\Author;
defined( 'ABSPATH' ) or exit;
if ( !function_exists( 'is_guest_post' ) )
{
    function is_guest_post( $post_id = null )
    {
        if ( empty( $post_id ) )
        {
            global $post;
            if ( empty( $post ) ) return false;
            $post_id = $post->ID;
        }
        $author = get_post_meta( $post_id, '_molongui_main_author', true );
        if ( !empty( $author ) ) if ( strncmp( $author, 'guest', strlen( 'guest' ) ) === 0 ) return true;
        return false;
    }
}
if ( !function_exists( 'has_guest_author' ) )
{
    function has_guest_author( $post_id = null )
    {
        if ( empty( $post_id ) )
        {
            global $post;
            if ( empty( $post ) ) return false;
            $post_id = $post->ID;
        }
        $authors = get_post_meta( $post_id, '_molongui_author', false );
        if ( empty( $authors ) ) return false;
        foreach ( $authors as $author )
        {
            $prefix = 'guest';
            if ( strncmp( $author, $prefix, strlen( $prefix ) ) === 0 ) return true;
        }
        return false;
    }
}
if ( !function_exists( 'is_multiauthor_post' ) )
{
    function is_multiauthor_post( $post_id = null )
    {
        if ( empty( $post_id ) )
        {
            global $post;
            if ( empty( $post ) ) return false;
            $post_id = $post->ID;
        }

        return ( count( get_post_meta( $post_id, '_molongui_author', false ) ) > 1 ? true : false );
    }
}
if ( !function_exists( 'is_multiauthor_link' ) )
{
    function is_multiauthor_link( $link )
    {
        $arg = '?molongui_byline=true';

        return ( strpos( $link, $arg ) !== false ? true : false );
    }
}
if ( !function_exists( 'get_main_author' ) )
{
    function get_main_author( $post_id )
    {
        $data = new stdClass();
        $ignore_guest = false;
        $main_author = get_post_meta( $post_id, '_molongui_main_author', true );
        if ( !empty( $main_author ) )
        {
            $split      = explode( '-', $main_author );
            $data->id   = $split[1];
            $data->type = $split[0];
            $data->ref  = $main_author;
            if ( $data->type == 'guest' and !molongui_authorship_is_feature_enabled( 'guest' ) )
            {
                $ignore_guest = true;
            }
        }
        if ( empty( $main_author ) or $ignore_guest )
        {
            if ( $post_author = get_post_field( 'post_author', $post_id ) )
            {
                $data->id   = $post_author;
                $data->type = 'user';
                $data->ref  = $data->type.'-'.$data->id;
            }
            else return false;
        }
        return $data;
    }
}
if ( !function_exists( 'get_post_authors' ) )
{
    function get_post_authors( $post_id = null, $key = '' )
    {
        if ( empty( $post_id ) or !is_integer( $post_id ) )
        {
            global $post;
            if ( empty( $post ) ) return false;
            $post_id = $post->ID;
        }
        $data = array();
        $main_author = get_main_author( $post_id );
        if ( empty( $main_author ) ) return false;
        if ( !molongui_authorship_is_feature_enabled( 'multi' ) ) return array( $main_author );
        $authors = get_post_meta( $post_id, '_molongui_author', false );
        if ( !empty( $authors) )
        {
            $guest_enabled = molongui_authorship_is_feature_enabled( 'guest' );

            foreach ( $authors as $author )
            {
                $split = explode( '-', $author );
                if ( $split[1] == $main_author->id ) continue;
                if ( $split[0] === 'guest' and !$guest_enabled ) continue;
                $data[] = (object) array( 'id' => (int)$split[1], 'type' => $split[0], 'ref' => $author );
            }
        }
        array_unshift( $data, $main_author );
        if ( !$key ) return $data;
        $values = array();
        foreach ( $data as $author ) $values[] = $author->$key;
        return $values;
    }
}
if ( !function_exists( 'get_byline' ) )
{
    function get_byline( $pid = null, $separator = '', $last_separator = '', $linked = false )
    {
        if ( is_null( $pid ) or !is_integer( $pid ) or !$pid )
        {
            global $post;
            if ( empty( $post ) ) return '';
            $pid = $post->ID;
        }
        if ( $authors = get_post_authors( $pid ) )
        {
            $settings = get_option( MOLONGUI_AUTHORSHIP_BYLINE_SETTINGS );
            switch ( $settings['byline_multiauthor_display'] )
            {
                case 'main':

                    $byline = mount_byline( $authors, '1', false, '', '', $linked );

                break;
                case '1':

                    $byline = mount_byline( $authors, '1', true, '', $last_separator, $linked );

                break;
                case '2':

                    $byline = mount_byline( $authors, '2', true, $separator, $last_separator, $linked );

                break;
                case '3':

                    $byline = mount_byline( $authors, '3', true, $separator, $last_separator, $linked );

                break;
                case 'all':
                default:

                    $byline = mount_byline( $authors, count( $authors ), false, $separator, $last_separator, $linked );

                break;
            }
        }
        return $byline;
    }
}
if ( !function_exists( 'mount_byline' ) )
{
    function mount_byline( $authors, $qty, $count = true, $separator = '', $last_separator = '', $linked = false )
    {
        if ( !$authors ) return;
        $string = '';
        $total  = count( $authors );
        $i = 0;
        $settings = get_option( MOLONGUI_AUTHORSHIP_BYLINE_SETTINGS );
        $separator      = ( !empty( $separator ) ? $separator : ( !empty( $settings['byline_multiauthor_separator'] ) ? $settings['byline_multiauthor_separator'] : ',' ) );
        $last_separator = ( !empty( $last_separator ) ? $last_separator : ( !empty( $settings['byline_multiauthor_last_separator'] ) ? $settings['byline_multiauthor_last_separator'] : __( 'and', 'molongui-authorship' ) ) );
        if ( $qty < $total )
        {
            for ( $j = 0; $j < $qty; $j++ )
            {
                $divider = ( $i == 0 ? '' : ( $i == ( $total - 1 ) ? ' '.$last_separator.' ' : $separator.' ' ) );
                $author_class = new Author( $authors[$j]->id, $authors[$j]->type );
                if ( $linked ) $item = $author_class->get_link();
                else $item = $author_class->get_name();
                $string .= $divider . $item;
                if ( ++$i == $qty ) break;
            }
            if ( $count ) $string .= ' '.sprintf( __( '%s %d more', 'molongui-authorship' ), $last_separator, $total - $qty );
        }
        else
        {
            foreach ( $authors as $author )
            {
                $divider = ( $i == 0 ? '' : ( $i == ( $total - 1 ) ? ' '.$last_separator.' ' : $separator.' ' ) );
                $author_class = new Author( $author->id, $author->type );
                if ( $linked ) $item = $author_class->get_link();
                else $item = $author_class->get_name();
                $string .= $divider . $item;
                if ( ++$i == $qty ) break;
            }
        }

        return $string;
    }
}
if ( !function_exists( 'get_coauthored_posts' ) )
{
    function get_coauthored_posts( $authors, $get_all = false, $exclude = array(), $entry = 'post', $meta_query = array() )
    {
        $settings = get_option( MOLONGUI_AUTHORSHIP_BOX_SETTINGS );
        switch ( $entry )
        {
            case 'all':
                $entries = molongui_get_post_types( 'all', 'names', false );
            break;

            case 'selected':
                $entries = molongui_supported_post_types( MOLONGUI_AUTHORSHIP_ID, 'all', false );
            break;

            case 'related':
                $entries = explode( ",", $settings['related_post_types'] );
            break;

            default:
                $entries = $entry;
            break;
        }
        if ( count( $authors ) > 1 )
        {
            $mq['authors']['relation'] = 'AND';
            foreach( $authors as $author )
            {
                $mq['authors'][] = array( 'key' => '_molongui_author', 'value' => $author->ref, 'compare' => '=' );
            }
        }
        else
        {
            $mq['authors'] = array( 'key' => '_molongui_author', 'value' => $authors, 'compare' => '=' );
        }
        if ( !empty( $meta_query ) )
        {
            $mq['authors']['relation'] = 'AND';
            $mq['authors'] = array
            (
                'key'   => $meta_query['key'],
                'value' => $meta_query['value'],
            );
        }
        $args = array
        (
            'post_type'      => $entries,
            'orderby'        => !empty( $settings['related_orderby'] ) ? $settings['related_orderby'] : 'date',
            'order'          => !empty( $settings['related_order'] )   ? $settings['related_order']   : 'DESC',
            'posts_per_page' => $get_all ? '-1' : $settings['related_items'],
            'post__not_in'   => $exclude,
            'meta_query'     => $mq,
            'site_id'        => get_current_blog_id(),
            'language'       => molongui_get_language(),
        );
        $hash = md5( serialize( $args ) );
        $key  = 'posts' . '_' . $hash;
        $data = wp_cache_get( $key, MOLONGUI_AUTHORSHIP_NAME );

        if ( false === $data )
        {
            $data = new WP_Query( $args );
            wp_cache_set( $key, $data, MOLONGUI_AUTHORSHIP_NAME );
            $db_key = MOLONGUI_AUTHORSHIP_DB_PREFIX . 'cache_posts';
            $hashes = get_option( $db_key, array() );
            $update = update_option( $db_key, !in_array( $hash, $hashes ) ? array_merge( $hashes, array( $hash ) ) : $hashes, true );
        }
        foreach ( $data->posts as $post ) $posts[] = $post;
        return ( !empty( $posts ) ? $posts : array() );
    }
}