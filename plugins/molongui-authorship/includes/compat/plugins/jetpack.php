<?php
defined( 'ABSPATH' ) or exit;
if ( defined( JETPACK__VERSION ) and version_compare( JETPACK__VERSION, '9.1.0', '>=' ) )
{
    add_filter( 'jetpack_content_options_featured_image_exclude_cpt', function( $excluded_post_types )
    {
        $excluded_post_types[] = 'guest_author';
        return $excluded_post_types;
    });
}
else
{
    add_action( 'init', function()
    {
        remove_filter( 'get_post_metadata', 'jetpack_featured_images_remove_post_thumbnail', true );
    }, 999 );
}