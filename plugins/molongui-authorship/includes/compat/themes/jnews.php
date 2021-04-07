<?php
defined( 'ABSPATH' ) or exit;
add_filter( 'molongui_authorship_bypass_original_user_id_if', function( $default )
{
    $dbt = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 10 );
    $fns = array( 'post_meta_1', 'jnews_the_author_link' );
    foreach ( $fns as $fn ) if ( array_search( $fn, array_column( $dbt, 'function' ) ) ) return true;
    return $default;
});
add_filter( 'molongui_authorship_filter_the_author_display_name_post_id', function( $post_id, $post, $display_name )
{
    $dbt = debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 8 );
    $i     = 7;
    $fn    = 'post_meta_1';
    $class = 'JNews\Module\ModuleViewAbstract';
    if ( isset( $dbt[$i]['function'] ) and $dbt[$i]['function'] == $fn and
         isset( $dbt[$i]['class'] ) and $dbt[$i]['class'] == $class and
         isset( $dbt[$i]['object'] )
    ){
        return (int) $dbt[$i]['args'][0]->ID;
    }
    return $post_id;
}, 10, 3 );
add_filter( 'molongui_authorship_filter_link_post_id', function( $post_id, $post, $link )
{
    $dbt = debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 9 );
    $i     = 8;
    $fn    = 'post_meta_1';
    $class = 'JNews\Module\ModuleViewAbstract';
    if ( isset( $dbt[$i]['function'] ) and $dbt[$i]['function'] == $fn and
         isset( $dbt[$i]['class'] ) and $dbt[$i]['class'] == $class and
         isset( $dbt[$i]['object'] ) and isset( $dbt[$i]['args'][0] ) and isset( $dbt[$i]['args'][0]->ID )
    )
        return (int) $dbt[$i]['args'][0]->ID;
    return $post_id;
}, 10, 3 );
add_filter( 'molongui_authorship_dont_render_author_box', function( $leave )
{
    if ( $leave ) return $leave;
    $dbt = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 10 );
    if ( in_the_loop() and isset( $dbt[7]['function'] ) and $dbt[7]['function'] == "render_footer" and isset( $dbt[7]['class'] ) and $dbt[7]['class'] == "JNews\Footer\FooterBuilder" ) return true;
    return false;
});
add_filter( 'jnews_default_query_args', function( $args )
{
    global $wp_query;
    if ( is_admin() or !$wp_query->is_main_query() ) return $args;
    $dbt = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 15 );
    if ( ( $wp_query->is_author() or array_key_exists( 'guest-author-name', $wp_query->query_vars ) )
         and
         isset( $dbt[9]['function'] ) and $dbt[9]['function'] == "render_content" and isset( $dbt[9]['class'] ) and $dbt[9]['class'] == "JNews\Archive\AuthorArchive"
    )
    {
        unset( $args['author__in'] );
        $args['meta_query'] = $wp_query->get( 'meta_query' );
    }
    return $args;
}, 99, 1 );