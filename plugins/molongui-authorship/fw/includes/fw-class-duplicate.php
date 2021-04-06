<?php

namespace Molongui\Authorship\Fw\Includes;
defined( 'ABSPATH' ) or exit;
if ( !class_exists( 'Molongui\Authorship\Fw\Includes\Duplicate' ) )
{
    class Duplicate
    {
        protected $status;
        public function __construct( $status = '' )
        {
            $this->status = $status;
        }
        public function clone_post_as_draft()
        {
            $this->clone_post( 'draft' );
        }
        public function clone_post( $status = '' )
        {
            global $wpdb;

            if ( !( isset( $_GET['post'] ) or isset( $_POST['post'] ) ) )
            {
                wp_die( 'No post to duplicate has been supplied!' );
            }
            $post_id = ( isset( $_GET['post'] ) ? $_GET['post'] : $_POST['post'] );
            $post = get_post( $post_id );
            $current_user    = wp_get_current_user();
            $new_post_author = $current_user->ID;
            if ( isset( $post ) and $post != null )
            {
                $args = array
                (
                    'comment_status' => $post->comment_status,
                    'ping_status'    => $post->ping_status,
                    'post_author'    => $new_post_author,
                    'post_content'   => $post->post_content,
                    'post_excerpt'   => $post->post_excerpt,
                    'post_name'      => $post->post_name,
                    'post_parent'    => $post->post_parent,
                    'post_password'  => $post->post_password,
                    'post_status'    => empty( $status ) ? $post->post_status : $status,
                    'post_title'     => $post->post_title,
                    'post_type'      => $post->post_type,
                    'to_ping'        => $post->to_ping,
                    'menu_order'     => $post->menu_order
                );
                $new_post_id = wp_insert_post( $args );
                $taxonomies = get_object_taxonomies( $post->post_type );
                foreach ( $taxonomies as $taxonomy )
                {
                    $post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
                    wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
                }
                $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id" );
                if ( count( $post_meta_infos ) != 0 )
                {
                    $sql_query = "INSERT INTO $wpdb->postmeta ( post_id, meta_key, meta_value) ";
                    foreach ( $post_meta_infos as $meta_info )
                    {
                        $meta_key = $meta_info->meta_key;
                        $meta_value = addslashes($meta_info->meta_value );
                        $sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
                    }
                    $sql_query .= implode( " UNION ALL ", $sql_query_sel );
                    $wpdb->query( $sql_query );
                }
                wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
                exit;
            }
            else
            {
                wp_die( 'Post duplication failed, could not find original post: ' . $post_id );
            }
        }

    } // class
} // class_exists
namespace Molongui\Fw\Includes;
if ( !class_exists( 'Molongui\Fw\Includes\Duplicate' ) )
{
    class Duplicate
    {
        protected $status;
        public function __construct( $status = '' )
        {
            $this->status = $status;
        }
        public function clone_post_as_draft()
        {
            $this->clone_post( 'draft' );
        }
        public function clone_post( $status = '' )
        {
            global $wpdb;

            if ( !( isset( $_GET['post'] ) or isset( $_POST['post'] ) ) )
            {
                wp_die( 'No post to duplicate has been supplied!' );
            }
            $post_id = ( isset( $_GET['post'] ) ? $_GET['post'] : $_POST['post'] );
            $post = get_post( $post_id );
            $current_user    = wp_get_current_user();
            $new_post_author = $current_user->ID;
            if ( isset( $post ) and $post != null )
            {
                $args = array
                (
                    'comment_status' => $post->comment_status,
                    'ping_status'    => $post->ping_status,
                    'post_author'    => $new_post_author,
                    'post_content'   => $post->post_content,
                    'post_excerpt'   => $post->post_excerpt,
                    'post_name'      => $post->post_name,
                    'post_parent'    => $post->post_parent,
                    'post_password'  => $post->post_password,
                    'post_status'    => empty( $status ) ? $post->post_status : $status,
                    'post_title'     => $post->post_title,
                    'post_type'      => $post->post_type,
                    'to_ping'        => $post->to_ping,
                    'menu_order'     => $post->menu_order
                );
                $new_post_id = wp_insert_post( $args );
                $taxonomies = get_object_taxonomies( $post->post_type );
                foreach ( $taxonomies as $taxonomy )
                {
                    $post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
                    wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
                }
                $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id" );
                if ( count( $post_meta_infos ) != 0 )
                {
                    $sql_query = "INSERT INTO $wpdb->postmeta ( post_id, meta_key, meta_value) ";
                    foreach ( $post_meta_infos as $meta_info )
                    {
                        $meta_key = $meta_info->meta_key;
                        $meta_value = addslashes($meta_info->meta_value );
                        $sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
                    }
                    $sql_query .= implode( " UNION ALL ", $sql_query_sel );
                    $wpdb->query( $sql_query );
                }
                wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
                exit;
            }
            else
            {
                wp_die( 'Post duplication failed, could not find original post: ' . $post_id );
            }
        }

    } // class
} // class_exists