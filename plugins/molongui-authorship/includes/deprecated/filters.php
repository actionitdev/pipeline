<?php

use Molongui\Authorship\Includes\Author;
defined( 'ABSPATH' ) or exit;
if ( version_compare( get_bloginfo( 'version' ),'4.6.0', '<' ) )
{
    if ( !function_exists( 'apply_filters_deprecated' ) )
    {
        function apply_filters_deprecated( $tag, $args, $version, $replacement = '', $message = '' )
        {
            if ( !has_filter( $tag ) )
            {
                return $args[0];
            }
            if ( WP_DEBUG  )
            {
                $message = empty( $message ) ? '' : ' ' . $message;

                if ( $replacement )
                {
                    trigger_error(
                        sprintf(
                            __( '%1$s is <strong>deprecated</strong> since version %2$s! Use %3$s instead.' ),
                            $tag,
                            $version,
                            $replacement
                        ) . $message,
                        E_USER_DEPRECATED
                    );
                }
                else
                {
                    trigger_error(
                        sprintf(
                            __( '%1$s is <strong>deprecated</strong> since version %2$s with no alternative available.' ),
                            $tag,
                            $version
                        ) . $message,
                        E_USER_DEPRECATED
                    );
                }
            }

            return apply_filters_ref_array( $tag, $args );
        }
    }
}
function authorship_deprecated_filter_get_author( $author, $author_id, $author_type )
{
    return apply_filters_deprecated( 'molongui_authorship_get_author', array( $author, $author_id, $author_type ), '4.2.0', 'authorship/author/get' );
}
add_filter( 'authorship/author/get', 'authorship_deprecated_filter_get_author', 0, 3 );
function authorship_deprecated_filter_get_name( $value, $author_id, $author_type, $author )
{
    return apply_filters_deprecated( 'molongui_authorship_get_author_name', array( $value, $author_id, $author_type, $author ), '4.2.0', 'authorship/author/name' );
}
add_filter( 'authorship/author/name', 'authorship_deprecated_filter_get_name', 0, 4 );
function authorship_deprecated_filter_get_slug( $value, $author_id, $author_type, $author )
{
    return apply_filters_deprecated( 'molongui_authorship_get_author_slug', array( $value, $author_id, $author_type, $author ), '4.2.0', 'authorship/author/slug' );
}
add_filter( 'authorship/author/slug', 'authorship_deprecated_filter_get_slug', 0, 4 );
function authorship_deprecated_filter_get_url( $value, $author_id, $author_type, $author, $opt )
{
    return apply_filters_deprecated( 'molongui_authorship_get_author_url', array( $value, $author_id, $author_type, $author, $opt ), '4.2.0', 'authorship/author/url' );
}
add_filter( 'authorship/author/url', 'authorship_deprecated_filter_get_url', 0, 5 );
function authorship_deprecated_filter_get_link( $value, $name, $url, $author_id, $author_type, $author )
{
    return apply_filters_deprecated( 'molongui_authorship_get_author_link', array( $value, $name, $url, $author_id, $author_type ), '4.2.0', 'authorship/author/link' );
}
add_filter( 'authorship/author/link', 'authorship_deprecated_filter_get_link', 0, 6 );
function authorship_deprecated_filter_get_bio( $value, $author_id, $author_type, $author )
{
    return apply_filters_deprecated( 'molongui_authorship_get_author_bio', array( $value, $author_id, $author_type, $author ), '4.2.0', 'authorship/author/bio' );
}
add_filter( 'authorship/author/bio', 'authorship_deprecated_filter_get_bio', 0, 4 );
function authorship_deprecated_filter_get_mail( $value, $author_id, $author_type, $author )
{
    return apply_filters_deprecated( 'molongui_authorship_get_author_mail', array( $value, $author_id, $author_type, $author ), '4.2.0', 'authorship/author/mail' );
}
add_filter( 'authorship/author/mail', 'authorship_deprecated_filter_get_mail', 0, 4 );
function authorship_deprecated_filter_get_meta( $value, $author_id, $author_type, $author, $key )
{
    return apply_filters_deprecated( 'molongui_authorship_get_author_meta', array( $value, $author_id, $author_type, $key ), '4.2.0', 'authorship/author/meta' );
}
add_filter( 'authorship/author/meta', 'authorship_deprecated_filter_get_meta', 0, 5 );
function authorship_deprecated_filter_get_post_count( $value, $author_id, $author_type, $author, $post_types )
{
    return apply_filters_deprecated( 'molongui_authorship_get_author_post_count', array( $value, $author_id, $author_type, $author ), '4.2.0', 'authorship/author/post_count' );
}
add_filter( 'authorship/author/post_count', 'authorship_deprecated_filter_get_post_count', 0, 5 );
function authorship_deprecated_filter_link_guest( $value, $guest_id )
{
    $author = new Author( $guest_id, 'guest' );
    $opt = get_option( MOLONGUI_AUTHORSHIP_ARCHIVES_SETTINGS );

    return apply_filters_deprecated( 'authorship/filter/link/guest', array( $value, $guest_id, 'guest', $author, $opt ), '4.2.0', 'authorship/author/url' );
}
add_filter( 'authorship/author/url', 'authorship_deprecated_filter_link_guest', 0, 2 );
function authorship_deprecated_filter_avatar_skip( $value, $args, $dbt )
{
    return apply_filters_deprecated( 'molongui_authorship_dont_filter_avatar', array( $value, $args, $dbt ), '4.2.0', 'authorship/get_avatar_data/skip' );
}
add_filter( 'authorship/get_avatar_data/skip', 'authorship_deprecated_filter_avatar_skip', 0, 3 );
function authorship_deprecated_filter_avatar_post_id( $value )
{
    return apply_filters_deprecated( 'molongui_authorship_filter_avatar_post_id', array( $value ), '4.2.0', '_authorship/get_avatar_data/filter/post_id' );
}
add_filter( '_authorship/get_avatar_data/filter/post_id', 'authorship_deprecated_filter_avatar_post_id', 0, 1 );
function authorship_deprecated_filter_avatar_author( $value, $id_or_email, $dbt )
{
    return apply_filters_deprecated( 'molongui_filter_avatar_author', array( $value, $id_or_email, $dbt ), '4.2.0', '_authorship/get_avatar_data/filter/author' );
}
add_filter( '_authorship/get_avatar_data/filter/author', 'authorship_deprecated_filter_avatar_author', 0, 3 );
function authorship_deprecated_filter_options_validate( $input, $option )
{
    return apply_filters_deprecated( 'authorship/admin/options/validate', array( $input, $option ), '4.2.16', 'authorship/options' );
}
add_filter( 'authorship/options', 'authorship_deprecated_filter_options_validate', 0, 2 );