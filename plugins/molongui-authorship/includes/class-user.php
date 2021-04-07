<?php

namespace Molongui\Authorship\Includes;

use Molongui\Authorship\Fw\Includes\Loader;
\defined( 'ABSPATH' ) or exit;
class User
{
    private $loader;
    private $vars;
    public function __construct()
    {
        $this->init();
        $this->loader->add_filter( 'manage_users_columns', $this, 'edit_columns' );
        $this->loader->add_action( 'manage_users_custom_column', $this, 'fill_columns', 10, 3 );
        $this->loader->add_action( 'edit_user_profile', $this, 'add_authorship_fields' ); // Edit user screen
        $this->loader->add_action( 'show_user_profile', $this, 'add_authorship_fields' ); // Profile screen
        $this->loader->add_action( 'profile_update', $this, 'save_authorship_fields' );  // Fires immediately after an existing user is updated.
        $this->loader->add_filter( 'user_profile_picture_description', $this, 'filter_user_profile_picture_description', 10, 2 );
        $this->loader->add_action( 'delete_user' , $this, 'delete' , 10, 2 );
        $this->loader->add_action( 'deleted_user', $this, 'deleted', 10, 2 );
        $this->loader->add_action( 'user_register' , $this, 'clear_object_cache', 0 ); // Fires immediately after a new user is registered.
        $this->loader->add_action( 'profile_update', $this, 'clear_object_cache', 0 ); // Fires immediately after an existing user is updated.
        $this->loader->add_action( 'deleted_user'  , $this, 'clear_object_cache', 0 ); // Fires immediately after a user is deleted from the database.
    }
    private function init()
    {
        $this->loader = Loader::get_instance();
        $this->vars = new \stdClass();
        $this->vars->networks = \molongui_authorship_get_social_networks( 'enabled' );
    }
	public function edit_columns( $column_headers )
	{
        unset( $column_headers['posts'] );
        $column_headers['molongui-entries'] = __( "Entries", 'molongui-authorship' );
        if ( \molongui_authorship_is_feature_enabled( 'box' ) )
        {
            $column_headers['molongui-box'] = __( "Author Box", 'molongui-authorship' );
        }
		$column_headers['user-id'] = __( 'ID' );

		return $column_headers;
	}
	public function fill_columns( $value, $column, $ID )
	{
		if ( $column == 'user-id' ) return $ID;
        elseif ( $column == 'molongui-entries' )
        {
            $html = '';
            $post_types = \molongui_supported_post_types( MOLONGUI_AUTHORSHIP_ID, 'all', true );
            $post_types_id = \array_column( $post_types, 'id' );
            foreach ( array( 'post', 'page' ) as $post_type )
            {
                if ( !\in_array( $post_type, $post_types_id ) )
                {
                    $post_type_obj = \get_post_type_object( $post_type );
                    $post_types    = \array_merge( $post_types, array( array( 'id' => $post_type, 'label' => $post_type_obj->label, 'singular' => $post_type_obj->labels->singular_name ) ) );
                }
            }
            foreach ( $post_types as $post_type )
            {
                $count = \get_user_meta( $ID, 'molongui_author_'.$post_type['id'].'_count', true );
                if ( $count > 0 )
                {
                    $html .= '<div>' . $count . ' ' . ( $count == 1 ? $post_type['singular'] : $post_type['label'] ) . '</div>';
                }
            }
            if ( !$html ) $html = __( 'None' );

            return $html;
        }
		elseif ( $column == 'molongui-box' )
		{
			switch ( \get_user_meta( $ID, 'molongui_author_box_display', true ) )
			{
				case 'show':

					return '<i data-tip="'.__( 'Show' ).'" class="m-a-icon-show m-tiptip"></i>';

				break;

				case 'hide':

					return '<i data-tip="'.__( 'Hide' ).'" class="m-a-icon-hide m-tiptip"></i>';

				break;

				case 'default':
				default:
                    $settings = \get_option( MOLONGUI_AUTHORSHIP_BOX_SETTINGS );

					if ( $settings['display'] == 'hide' )
                    {
                        return '<i data-tip="'.__( 'Hide' ).'" class="m-a-icon-hide m-tiptip"></i>';
                    }
					else return '<i data-tip="'.__( 'Show' ).'" class="m-a-icon-show m-tiptip"></i>';

				break;
			}
		}

		return $value;
	}
	public function add_authorship_fields( $user )
	{
		if ( \is_object( $user ) )
        {
	        if ( !\current_user_can( 'edit_user', $user->ID ) ) return;
            $match = \array_intersect( $user->roles, \apply_filters( 'authorship/user/roles', array( 'administrator', 'editor', 'author', 'contributor' ) ) );
            if ( empty( $match ) ) return;
        }
        else
        {
            if ( 'add-new-user' !== $user ) return;

            $user = new \stdClass();
	        $user->ID = 0;
        }
        if ( \molongui_authorship_is_feature_enabled( 'user_profile' ) ) include MOLONGUI_AUTHORSHIP_DIR . 'admin/views/user/html-plugin-fields.php';
		elseif ( \molongui_authorship_is_feature_enabled( 'avatar' ) )   include MOLONGUI_AUTHORSHIP_DIR . 'admin/views/user/html-profile-picture.php';
    }
	public function filter_user_profile_picture_description( $description, $profileuser )
    {
        $add = ' ';
        $user_profile = \molongui_authorship_is_feature_enabled( 'user_profile' );
        $local_avatar = \molongui_authorship_is_feature_enabled( 'avatar' );
        if ( $user_profile and $local_avatar )
        {
            $add .= \sprintf( __( 'Or you can upload a custom profile picture using %sMolongui Authorship field%s.', 'molongui-authorship' ), '<a href="#molongui-user-fields">', '</a>' );
        }
        elseif ( !$user_profile and $local_avatar )
        {
            $add .= __( 'Or you can upload a custom profile using the "Local Avatar" field below.', 'molongui-authorship' );
        }
        else
        {
            $add .= \sprintf( __( 'Or you can upload a custom profile picture enabling Molongui Authorship "Local Avatar" feature %shere%s.', 'molongui-authorship' ), '<a href="'.\admin_url().'admin.php?page=molongui-authorship" target="_blank">', '</a>' );
        }

        return $description . $add;
    }
	public function save_authorship_fields( $user_id )
	{
		if ( !\current_user_can( 'edit_user', $user_id ) ) return;
        if ( \molongui_authorship_is_feature_enabled( 'user_profile' ) )
        {
            \update_user_meta( $user_id, 'molongui_author_phone',        $_POST['molongui_author_phone']        );
            \update_user_meta( $user_id, 'molongui_author_job',          $_POST['molongui_author_job']          );
            \update_user_meta( $user_id, 'molongui_author_company',      $_POST['molongui_author_company']      );
            \update_user_meta( $user_id, 'molongui_author_company_link', $_POST['molongui_author_company_link'] );

            foreach ( $this->vars->networks as $id => $network )
            {
                if ( !empty( $_POST['molongui_author_'.$id] ) ) \update_user_meta( $user_id, 'molongui_author_'.$id, \sanitize_text_field( $_POST['molongui_author_'.$id] ) );
                else \delete_user_meta( $user_id, 'molongui_author_'.$id );
            }
            $checkboxes = array
            (
                'molongui_author_show_meta_mail',
                'molongui_author_show_meta_phone',
                'molongui_author_show_icon_mail',
                'molongui_author_show_icon_web',
                'molongui_author_show_icon_phone',
            );
            foreach ( $checkboxes as $checkbox )
            {
                if ( isset( $_POST[$checkbox] ) ) \update_user_meta( $user_id, $checkbox, \sanitize_text_field( $_POST[$checkbox] ) );
                else \delete_user_meta( $user_id, $checkbox );
            }
            \update_post_meta( $user_id, 'molongui_author_box_display', 'default' );
            \do_action( 'authorship/admin/user/save', $user_id, $_POST );
        }
        if ( \molongui_authorship_is_feature_enabled( 'avatar' ) )
        {
            if ( \current_user_can( 'upload_files', $user_id ) )
            {
                if ( isset( $_POST['molongui_author_image_id']   ) ) \update_user_meta( $user_id, 'molongui_author_image_id',   $_POST['molongui_author_image_id']   );
                if ( isset( $_POST['molongui_author_image_url']  ) ) \update_user_meta( $user_id, 'molongui_author_image_url',  $_POST['molongui_author_image_url']  );
                if ( isset( $_POST['molongui_author_image_edit'] ) ) \update_user_meta( $user_id, 'molongui_author_image_edit', $_POST['molongui_author_image_edit'] );
            }
        }
	}
    public function delete( $user_id, $reassign )
    {
        if ( $reassign === null ) return;

        $author     = new Author( $user_id, 'user' );
        $user_posts = $author->get_posts( array( 'fields' => 'ids', 'post_type' => 'all' ) );

        \add_filter( 'authorship/admin/user/delete', function() use ( $user_posts ) { return $user_posts; } );
    }
    public function deleted( $user_id, $reassign )
    {
        if ( $reassign === null ) return;
        $post_ids = \apply_filters( 'authorship/admin/user/delete', array() );
        if ( empty( $post_ids ) ) return;
        $old_usr = 'user-'.$user_id;
        $new_usr = 'user-'.$reassign;
        foreach ( $post_ids as $post_id )
        {
            \delete_post_meta( $post_id, '_molongui_author', $old_usr );
            if ( \get_post_meta( $post_id, '_molongui_main_author', true ) === $old_usr )
            {
                \update_post_meta( $post_id, '_molongui_main_author', $new_usr, $old_usr );
                \update_post_meta( $post_id, '_molongui_author', $new_usr );
            }
        }
        \authorship_update_post_counters( 'all', $new_usr );
    }
    public function clear_object_cache()
    {
        \molongui_clear_object_cache( 'users' );
        \molongui_clear_object_cache( 'posts' );
    }

} // class